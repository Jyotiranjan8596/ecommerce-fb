<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\GeneralNotification;
use App\Support\AdminUserAudience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:pos,prime,non_prime,all'],
            'delivery' => ['required', 'in:individual,all'],
            'user_id' => ['nullable', 'required_if:delivery,individual', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2000'],
            'type' => ['required', 'in:info,success,warning,danger'],
        ]);

        $notification = new GeneralNotification(
            $validated['title'],
            $validated['message'],
            $validated['type']
        );

        if ($validated['delivery'] === 'individual') {
            $recipient = AdminUserAudience::query($validated['category'])
                ->where('user_id', $validated['user_id'])
                ->first();

            if (! $recipient) {
                return back()
                    ->withInput()
                    ->withErrors(['user_id' => 'No user with that ID exists in the selected category.']);
            }

            $recipient->notify($notification);

            return back()->with('success', 'Notification sent to ' . $recipient->name . '.');
        }

        $recipientCount = AdminUserAudience::query($validated['category'])->count();
        if ($recipientCount === 0) {
            return back()->withInput()->with('error', 'There are no users in the selected category.');
        }

        AdminUserAudience::query($validated['category'])->chunkById(500, function ($users) use ($notification) {
            Notification::send($users, $notification);
        });

        return back()->with(
            'success',
            'Notification sent to ' . number_format($recipientCount) . ' ' . AdminUserAudience::label($validated['category']) . '.'
        );
    }
}
