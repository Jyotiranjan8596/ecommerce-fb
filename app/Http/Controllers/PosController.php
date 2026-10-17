<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Models\PosModel;
use App\Models\User;
use App\Services\PaymentSummaryGenerator;
use App\Support\Notify;
use App\Models\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $posId        = $user_profile->user_id;

        $pos = PosModel::where('user_id', $posId)->first();

        if (! $pos) {
            return view('pos.index', [
                'userId' => $userId,
                'user_profile' => $user_profile,
                'posLocation' => null,
                'msales' => 0,
                'dsales' => 0,
                'currentMonthWalletCredit' => 0,
                'salesTrendLabels' => [],
                'salesTrendValues' => [],
                'salesSummaryValues' => [0, 0, 0],
            ])->withErrors(['POS not found']);
        }

        $pos_id       = $pos->id;
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;
        $today        = Carbon::today();

        $walletQuery = Wallet::where('pos_id', $pos_id);

        $msales = (clone $walletQuery)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('billing_amount');

        $dsales = (clone $walletQuery)
            ->whereDate('transaction_date', $today)
            ->sum('billing_amount');

        $currentMonthWalletCredit = (clone $walletQuery)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount_wallet');

        $trendStart = Carbon::today()->subDays(6);
        $todayEnd = Carbon::today()->endOfDay();
        $dailySales = (clone $walletQuery)
            ->whereBetween('transaction_date', [$trendStart, $todayEnd])
            ->selectRaw('DATE(transaction_date) as sale_date, SUM(billing_amount) as total')
            ->groupByRaw('DATE(transaction_date)')
            ->pluck('total', 'sale_date');

        $trendDates = collect(range(6, 0))->map(fn ($daysBack) => Carbon::today()->subDays($daysBack));
        $salesTrendLabels = $trendDates->map(fn ($date) => $date->format('D j'))->all();
        $salesTrendValues = $trendDates->map(fn ($date) => (float) ($dailySales[$date->toDateString()] ?? 0))->all();

        $salesSummaryValues = [
            (float) $msales,
            (float) $dsales,
            (float) $currentMonthWalletCredit,
        ];

        $posLocation = $pos->only(['name', 'address', 'city', 'state', 'zip', 'mobilenumber', 'latitude', 'longitude']);

        return view('pos.index', compact(
            'userId',
            'user_profile',
            'posLocation',
            'msales',
            'dsales',
            'currentMonthWalletCredit',
            'salesTrendLabels',
            'salesTrendValues',
            'salesSummaryValues'
        ));
    }

    public function initiate_payment(Request $request)
    {
        if ($request->hasFile('screenshot')) {
            $file     = $request->file('screenshot');
            $response = Http::attach(
                'file',
                file_get_contents($file),
                'image.jpg'
            )->post('https://api.ocr.space/parse/image', [
                'apikey'            => env('OCR_API_KEY'),
                'language'          => 'eng',
                'isOverlayRequired' => 'false',
            ]);

            $result = $response->json();
            dd($result['ParsedResults']);
            return response()->json([
                'extracted_text' => $text,
            ]);
        }
    }

    public function pos_profile_index()
    {
        $user = auth()->user();
        $user_profile = PosModel::where('user_id', $user->user_id)->first();
        // dd($user_profile);
        return view('pos.profile', compact('user_profile'));
    }

    public function updateprofile(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:10'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'upi_id' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delivery_orders_requested' => ['nullable', 'boolean'],
        ]);

        $update_profile = User::findOrFail(auth()->id());
        $pos = PosModel::where('user_id', $update_profile->user_id)->firstOrFail();
        $hadDeliveryRequest = $pos->delivery_orders_requested;

        $update_profile->email = $validated['email'];
        $update_profile->address = $validated['address'] ?? null;
        $update_profile->city = $validated['city'] ?? null;
        $update_profile->state = $validated['state'] ?? null;
        $update_profile->zip = $validated['zip'] ?? null;

        if ($request->hasFile('image')) {
            $imageName = uniqid('pos_', true) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $update_profile->image = $imageName;
            $pos->image = $imageName;
        }
        $pos->email = $validated['email'];
        $pos->address = $validated['address'] ?? null;
        $pos->city = $validated['city'] ?? null;
        $pos->state = $validated['state'] ?? null;
        $pos->zip = $validated['zip'] ?? null;
        $pos->latitude = $validated['latitude'] ?? null;
        $pos->longitude = $validated['longitude'] ?? null;
        $pos->upi_id = $validated['upi_id'] ?? null;
        if (! $pos->delivery_orders_enabled) {
            $pos->delivery_orders_requested = $request->boolean('delivery_orders_requested');
        }

        DB::transaction(function () use ($update_profile, $pos) {
            $update_profile->save();
            $pos->save();
        });

        if (! $pos->delivery_orders_enabled && $pos->delivery_orders_requested && ! $hadDeliveryRequest) {
            Notify::admins(
                'POS delivery request submitted',
                $pos->name . ' requested approval to receive customer orders.',
                'warning',
                route('admin.pos_system.index')
            );
        } elseif ($hadDeliveryRequest && ! $pos->delivery_orders_requested) {
            Notify::admins(
                'POS delivery request cancelled',
                $pos->name . ' cancelled its delivery order request.',
                'info',
                route('admin.pos_system.index')
            );
        }

        return redirect()->route('pos.profile.index')->with(
            'success',
            $pos->delivery_orders_requested
                ? 'Profile updated. Your delivery service request is awaiting admin approval.'
                : 'Profile updated successfully.'
        );
    }

    public function changepassword()
    {
        return view('pos.changepassword');
    }

    public function newpassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('pos.change.password')
            ->with('success', 'Password changed successfully. You are still signed in.');
    }

    public function verifyAllCustomer()
    {
        try {
            $user = auth()->user();
            $pos  = PosModel::where('user_id', $user->user_id)->first();

            if (! $pos) {
                return response()->json([
                    'success' => false,
                    'message' => 'POS not found for the user.',
                ], 404);
            }

            $datesToGenerate = Wallet::query()
                ->where('pos_id', $pos->id)
                ->whereNotNull('transaction_date')
                ->pendingDsrVerification()
                ->selectRaw('DATE(transaction_date) as transaction_date')
                ->distinct()
                ->pluck('transaction_date');

            $updatedRows = 0;
            Wallet::query()
                ->where('pos_id', $pos->id)
                ->whereNotNull('transaction_date')
                ->pendingDsrVerification()
                ->orderBy('id')
                ->chunkById(200, function ($wallets) use (&$updatedRows): void {
                    foreach ($wallets as $wallet) {
                        $wallet->status = 1;
                        $wallet->save();
                        $updatedRows++;
                    }
                });
            $summaryGenerationFailures = $this->generateCompletedPaymentSummaries(
                (int) $pos->id,
                $datesToGenerate->all()
            );

            $message = $updatedRows > 0
                ? "Customers have been successfully verified."
                : "Already Verified";
            if ($summaryGenerationFailures !== []) {
                $message .= ' Some payment summaries could not be generated; please retry from Admin DSR Summary.';
            }

            return response()->json([
                'code'    => 200,
                'message' => $message,
                'payment_summary_generation_failures' => $summaryGenerationFailures,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying customers.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyDsr(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:50'],
        ]);

        $posId = PosModel::where('user_id', auth()->user()->user_id)->value('id');
        if (! $posId) {
            return response()->json([
                'success' => false,
                'message' => 'POS not found for the user.',
            ], 404);
        }

        try {
            $transactionsQuery = Wallet::query()
                ->where('pos_id', $posId)
                ->when(
                    ! empty($validated['start_date']) && ! empty($validated['end_date']),
                    fn ($query) => $query->whereBetween('transaction_date', [
                        Carbon::parse($validated['start_date'])->startOfDay(),
                        Carbon::parse($validated['end_date'])->endOfDay(),
                    ]),
                    fn ($query) => $query->whereDate('transaction_date', now()->toDateString())
                )
                ->when(
                    ! empty($validated['search']),
                    fn ($query) => $query->where('mobilenumber', 'LIKE', '%' . $validated['search'] . '%')
                )
                ->whereNotNull('transaction_date')
                ->pendingDsrVerification();

            $walletIds = (clone $transactionsQuery)->pluck('id')->all();
            $datesToGenerate = (clone $transactionsQuery)
                ->selectRaw('DATE(transaction_date) as transaction_date')
                ->distinct()
                ->pluck('transaction_date');

            $count = Wallet::verifyDsr($walletIds, (int) $posId);
            $summaryGenerationFailures = $this->generateCompletedPaymentSummaries(
                (int) $posId,
                $datesToGenerate->all()
            );
            $message = $count > 0
                ? 'Transactions have been successfully verified.'
                : 'No transactions are available to verify. Pending transactions are automatically verified at 12 noon the following day.';
            if ($summaryGenerationFailures !== []) {
                $message .= ' Payment summary generation failed for: '
                    . implode(', ', $summaryGenerationFailures)
                    . '. Please retry from Admin DSR Summary.';
            }

            return response()->json([
                'count' => $count,
                'success' => true,
                'code' => 200,
                'message' => $message,
                'payment_summary_generation_failures' => $summaryGenerationFailures,
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying transactions.',
            ], 500);
        }
    }

    private function generateCompletedPaymentSummaries(int $posId, array $dates): array
    {
        $failures = [];
        $generator = app(PaymentSummaryGenerator::class);

        foreach (array_unique($dates) as $date) {
            try {
                $generator->generateWhenComplete($posId, Carbon::parse($date)->toDateString());
            } catch (\Throwable $e) {
                report($e);
                $failures[] = Carbon::parse($date)->toDateString();
            }
        }

        return $failures;
    }

    public function verifyAllPos(Request $request)
    {
        try {
            // dd($request->all());
            $response = PosModel::getPosByUpi($request->name);
            if ($response) {
                return response()->json([
                    'success' => true,
                    'name'    => $response->name,
                    'id'      => $response->id,
                    'upi_id'  => $response->upi_id,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Qr",
                ]);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function terms_conditions()
    {
        return view('pos.terms_conditions');
    }

    public function pos_payment_details(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'pos_id' => 'nullable|integer',
        ]);
        $transaction_data = Wallet::pos_transactions($request->transaction_date, $request->pos_id);
        $payment_data = PaymentSummary::getWalletDetails($request->transaction_date, $request->pos_id);
        $ledger_data = Payment::getLedgerData($request);
        // dd($ledger_data);
        if ($payment_data) {
            if ($request->routeIs('pos.get.payment.details')) {
                $pos = PosModel::where('user_id', auth()->user()->user_id)->firstOrFail();
                abort_unless((string) $request->pos_id === (string) $pos->user_id, 403);

                $generatedEntry = $payment_data->ensureGeneratedPaymentEntry();
                if (!$generatedEntry) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to prepare the payment reference for this summary.',
                    ], 500);
                }

                $payment_data->load('payment');
                $submittedAmount = $payment_data->payment
                    ->where('from', $pos->user_id)
                    ->where('to', '666666')
                    ->reject(fn ($payment) => $payment->remark === 'Auto Generated')
                    ->sum('amount');
                $payment_data->setAttribute(
                    'pos_payment_remaining',
                    max(0, round((float) $payment_data->admin_credit - (float) $submittedAmount, 2))
                );
            }

            return response()->json([
                'success' => $transaction_data->isNotEmpty(),
                'data' => $transaction_data,
                'payment_data' => $payment_data,
                'total_credit' => $ledger_data ? $ledger_data['total_balance'] : '',
                'balance_type' => $ledger_data ? $ledger_data['total_balance_type'] : '',
                'message' => $transaction_data->isNotEmpty()
                    ? 'Data fetched successfully.'
                    : 'No records found.',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => $transaction_data,
                'payment_data' => null,
                'message' => $payment_data = false
                    ? 'Data fetched successfully.'
                    : 'No records found.',
            ]);
        }
    }
}
