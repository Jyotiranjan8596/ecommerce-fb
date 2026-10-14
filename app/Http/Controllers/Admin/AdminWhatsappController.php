<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsappMessageService;
use App\Support\AdminUserAudience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdminWhatsappController extends Controller
{
    public function index(): View
    {
        return view('admin.whatsapp.index');
    }

    public function store(Request $request, WhatsappMessageService $whatsapp): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:pos,prime,non_prime,all'],
            'delivery' => ['required', 'in:individual,all'],
            'user_id' => ['nullable', 'required_if:delivery,individual', 'string', 'max:30'],
            'template_name' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/'],
            'language' => ['required', 'string', 'max:20', 'regex:/^[a-z]{2,3}(?:_[A-Z]{2})?$/'],
            'body_parameters' => ['nullable', 'string', 'max:2000'],
        ], [
            'template_name.regex' => 'Use the approved WhatsApp template name (lowercase letters, numbers, and underscores).',
            'language.regex' => 'Enter a valid template language code, such as en or en_US.',
        ]);

        $bodyParameters = $this->parseBodyParameters($validated['body_parameters'] ?? '');
        if (count($bodyParameters) > 20) {
            return back()->withInput()->withErrors([
                'body_parameters' => 'Enter no more than 20 body parameters.',
            ]);
        }

        $recipients = AdminUserAudience::query($validated['category'])
            ->whereNotNull('mobilenumber')
            ->where('mobilenumber', '<>', '');

        if ($validated['delivery'] === 'individual') {
            $recipient = (clone $recipients)->where('user_id', $validated['user_id'])->first();
            if (! $recipient) {
                return back()
                    ->withInput()
                    ->withErrors(['user_id' => 'No user with a WhatsApp number exists in the selected category for that ID.']);
            }

            try {
                $whatsapp->sendApprovedTemplate(
                    $recipient->mobilenumber,
                    $validated['template_name'],
                    $validated['language'],
                    $this->parametersForRecipient($bodyParameters, $recipient)
                );
            } catch (Throwable $exception) {
                Log::warning('Admin WhatsApp template message failed.', [
                    'category' => $validated['category'],
                    'exception' => $exception->getMessage(),
                ]);

                return back()->withInput()->with('error', 'WhatsApp could not send the message: ' . $exception->getMessage());
            }

            return back()->with('success', 'WhatsApp template sent to ' . $recipient->name . '.');
        }

        $recipientCount = (clone $recipients)->count();
        if ($recipientCount === 0) {
            return back()->withInput()->with('error', 'There are no users with WhatsApp numbers in the selected category.');
        }

        $sent = 0;
        $failed = 0;
        $recipients->chunkById(100, function ($users) use (
            $whatsapp,
            $validated,
            $bodyParameters,
            &$sent,
            &$failed
        ) {
            foreach ($users as $recipient) {
                try {
                    $whatsapp->sendApprovedTemplate(
                        $recipient->mobilenumber,
                        $validated['template_name'],
                        $validated['language'],
                        $this->parametersForRecipient($bodyParameters, $recipient)
                    );
                    $sent++;
                } catch (Throwable $exception) {
                    $failed++;
                    Log::warning('Admin WhatsApp template message failed.', [
                        'category' => $validated['category'],
                        'exception' => $exception->getMessage(),
                    ]);
                }
            }
        });

        $summary = 'WhatsApp template sent to ' . number_format($sent) . ' of '
            . number_format($recipientCount) . ' ' . AdminUserAudience::label($validated['category']) . '.';
        if ($failed > 0) {
            return back()->with('warning', $summary . ' ' . number_format($failed) . ' message(s) failed; see the application log for details.');
        }

        return back()->with('success', $summary);
    }

    /**
     * @return array<int, string>
     */
    private function parseBodyParameters(string $input): array
    {
        if (trim($input) === '') {
            return [];
        }

        return array_map(
            static fn (string $parameter): string => trim($parameter),
            preg_split('/\r\n|\r|\n/', trim($input)) ?: []
        );
    }

    /**
     * @param array<int, string> $parameters
     * @return array<int, string>
     */
    private function parametersForRecipient(array $parameters, User $recipient): array
    {
        return array_map(static function (string $parameter) use ($recipient): string {
            return str_replace(
                ['{{name}}', '{{user_id}}'],
                [$recipient->name ?? '', $recipient->user_id ?? ''],
                $parameter
            );
        }, $parameters);
    }
}
