<?php

namespace App\Http\Controllers;

use App\Exports\AccountLedgerExport;
use App\Exports\AccountSettlementExport;
use App\Jobs\AccountLedgerJob;
use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Models\PosModel;
use App\Models\Wallet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PaymentSummaryController extends Controller
{
    public function saveSummaryData(Request $request)
    {
        try {
            $data   = $request->all();
            $result = PaymentSummary::store_summary($data);
            if ($result === 2) {
                return response()->json([
                    'success' => true,
                    'code'    => 200,
                    'message' => 'Payment summary saved successfully!',
                ]);
            } elseif ($result === 1) {
                return response()->json([
                    'success' => false,
                    'code'    => 409,
                    'message' => 'Payment already Submitted for this date',
                ], 409);
            } else {
                return response()->json([
                    'success' => false,
                    'code'    => 500,
                    'message' => 'Unable to save the payment summary. Please try again.',
                ], 500);
            }
        } catch (\Throwable $e) {
            Log::error('Payment summary submission failed.', [
                'message' => $e->getMessage(),
                'user_id' => auth()->user()?->user_id,
            ]);
            return response()->json([
                'success' => false,
                'code'    => 500,
                'message' => 'Unable to save the payment summary. Please try again.',
            ], 500);
        }
    }

    public function export_settlement()
    {
        try {
            $result = PaymentSummary::fetch_summary_pos();
            if ($result) {
                return Excel::download(new AccountSettlementExport($result), 'AccountSettlement_' . now()->format('d,m,y') . '.csv');
                // return response()->json([
                //     'status' => 'success',
                //     'code'   => 200,
                //     'data'   => $result,
                // ]);
            } else {
                return response()->json([
                    'status'  => 'failed',
                    'code'    => 500,
                    'message' => 'Something Went Wrong!',
                ]);
            }
        } catch (\Exception $e) {
            Log::info("Summary Controller" . $e->getMessage());
            return response()->json([
                'status'  => 'failed',
                'code'    => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function fetch_summary_pos()
    {
        try {
            $result = PaymentSummary::fetch_summary_pos();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'code'   => 200,
                    'data'   => $result,
                ]);
            } else {
                return response()->json([
                    'status'  => 'failed',
                    'code'    => 500,
                    'message' => 'Something Went Wrong!',
                ]);
            }
        } catch (\Exception $e) {
            Log::info("Summary Controller" . $e->getMessage());
            return response()->json([
                'status'  => 'failed',
                'code'    => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function sattlement_index()
    {
        $settlements = PaymentSummary::fetch_summary_pos();
        return view('pos.sattlement_index', compact('settlements'));
    }

    public function admin_settlemt_index()
    {
        $settlements = PaymentSummary::fetch_summary_admin();
        $all_pos = PosModel::getAll();
        return view('admin.settlement_payment.settlement', compact('settlements', 'all_pos'));
    }

    public function summaryIndex(Request $request)
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'pos_id' => ['nullable', 'integer', 'exists:pos_systems,id'],
        ]);

        $summaries = PaymentSummary::with('pos_system')
            ->withExists([
                'payment as has_generated_payment' => fn ($query) => $query->where('remark', 'Auto Generated'),
            ])
            ->when(!empty($filters['date']), fn ($query) => $query->whereDate('date', $filters['date']))
            ->when(!empty($filters['pos_id']), fn ($query) => $query->where('pos_id', $filters['pos_id']))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();
        $all_pos = PosModel::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.payment.summaries', [
            'summaries' => $summaries,
            'all_pos' => $all_pos,
            'selectedDate' => $filters['date'] ?? '',
            'selectedPosId' => $filters['pos_id'] ?? '',
        ]);
    }

    public function settleSummary(int $id)
    {
        $result = PaymentSummary::settleGeneratedSummary($id, (string) auth()->user()->user_id);

        if ($result === 'settled') {
            return redirect()->back()->with('success', 'Payment summary settled and posted to the POS account ledger.');
        }

        $message = match ($result) {
            'already_settled' => 'This payment summary has already been settled.',
            'missing_pos' => 'The POS account for this payment summary could not be found.',
            'missing_generated_entry' => 'The generated payment entry is missing, so this summary cannot be settled.',
            'payment_incomplete' => 'The POS has not paid the full amount due for this summary.',
            default => 'Unable to settle this payment summary.',
        };

        return redirect()->back()->with('error', $message);
    }

    public function verify_settlement(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:payment_summaries,id'],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ]);
        $res = PaymentSummary::update_settlement($request);
        if ($res) {
            return response()->json([
                'success' => true,
                'code'    => 200,
                'message' => 'Payment Verified',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'code'    => 500,
                'message' => 'Verification Failed',
            ]);
        }
    }

    public function reject_settlement($id)
    {
        try {
            $settlement = PaymentSummary::findOrFail($id);

            if ($settlement->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending settlements can be rejected.',
                ], 400);
            }

            $settlement->status = 'rejected';
            $settlement->save();

            return response()->json([
                'success' => true,
                'message' => 'Settlement rejected successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadInvoice($id)
    {
        $settlement = PaymentSummary::getinvoice($id);
        // dd($settlement);
        $pdf = Pdf::loadView('admin.settlement_payment.invoice', compact('settlement'))
            ->setPaper('a4');

        return $pdf->download('Invoice-' . $settlement['pos_name'] . '-' . $settlement['pos_user_id'] . '_' . now()->format('d,m,y') . '.pdf');
    }

    public function downloadPosInvoice($id)
    {
        $settlement = PaymentSummary::getposinvoice($id);
        // dd($settlement);
        $pdf = Pdf::loadView('admin.settlement_payment.invoice', compact('settlement'))
            ->setPaper('a4');

        return $pdf->download('Invoice-' . $settlement['pos_name'] . '-' . $settlement['pos_user_id'] . '_' . now()->format('d,m,y') . '.pdf');
    }

    public function ledger_index()
    {
        $posList = PosModel::query()
            ->select(['id', 'user_id', 'name'])
            ->orderBy('name')
            ->get();

        return view('admin.payment.account_ledger', compact('posList'));
    }

    public function pos_ledger_index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        return view('pos.payment.ledger', compact('userId'));
    }

    public function payment_index()
    {
        return view('admin.payment.payment');
    }

    public function create_payment(Request $request)
    {
        $request->validate([
            'pos_id' => ['required', 'integer', 'exists:pos_systems,id'],
            'summary_date' => ['required', 'date'],
            'paying_amount' => ['required', 'numeric', 'gt:0'],
            'pay_by' => ['required', 'in:0,1'],
            'reference_number' => [
                $request->boolean('is_pos') ? 'nullable' : 'required',
                'string',
                'max:255',
            ],
            'remark' => ['nullable', 'string', 'max:2000'],
            'is_pos' => ['sometimes', 'boolean'],
        ]);

        if ((string) auth()->user()->role === '4') {
            $ownPosId = PosModel::where('user_id', auth()->user()->user_id)->value('id');
            abort_unless((int) $request->pos_id === (int) $ownPosId, 403, 'You can only submit payments for your own POS account.');
        }

        $res = Payment::create_payment($request);
        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Payment Succesfull'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No pending payment summary was found, or the payment could not be recorded.'
            ], 422);
        }
    }

    public function generatePosPaymentSummaries(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => ['required', 'date'],
        ]);
        $date = $validated['transaction_date'];
        $posIdsWithPendingTransactions = Wallet::query()
            ->whereDate('transaction_date', $date)
            ->pendingDsrVerification()
            ->pluck('pos_id')
            ->map(fn ($posId) => (int) $posId);
        $dailyPosData = PosModel::getWalletDetails($date)
            ->filter(fn ($item) => (int) $item['total_transactions'] > 0
                && ! $posIdsWithPendingTransactions->contains((int) $item['pos_id']))
            ->values();

        $createdCount = 0;
        $repairedCount = 0;
        $existingCount = 0;
        $failures = [];
        foreach ($dailyPosData as $item) {
            $result = PaymentSummary::store_summary([
                'pos_id' => $item['pos_id'],
                'date' => $date,
                'total_transactions' => $item['total_transactions'],
                'billing_amount' => $item['billing_amount'],
                'cash_upi' => $item['payByCashOrUpi'],
                'wallet' => $item['payByWallet'],
                'reward' => $item['payByReward'],
                'credit_amount' => $item['creditAmount'],
                'debit_amount' => $item['debitAmount'],
            ]);

            if ($result === 2) {
                $createdCount++;
            } elseif ($result === 4) {
                $repairedCount++;
            } elseif ($result === 1) {
                $existingCount++;
            } else {
                $failures[] = $item['pos_name'];
                Log::error('POS payment summary generation failed.', [
                    'pos_id' => $item['pos_id'],
                    'date' => $date,
                    'result' => $result,
                ]);
            }
        }

        $posIds = $dailyPosData->pluck('pos_id');
        $summaries = PaymentSummary::with('pos_system')
            ->whereDate('date', $date)
            ->whereIn('pos_id', $posIds)
            ->get()
            ->keyBy('pos_id');
        $summaryRows = $dailyPosData->map(function ($item) use ($summaries) {
            $summary = $summaries->get($item['pos_id']);

            return [
                'pos_id' => $item['pos_id'],
                'pos_user_id' => $summary?->pos_system?->user_id,
                'pos_name' => $item['pos_name'],
                'total_transactions' => $summary?->total_transaction ?? $item['total_transactions'],
                'total_billing_amount' => $summary?->total_billing_amount ?? $item['billing_amount'],
                'by_cash' => $summary?->by_cash ?? $item['payByCashOrUpi'],
                'by_wallet' => $summary?->by_wallet ?? $item['payByWallet'],
                'by_reward' => $summary?->by_reward ?? $item['payByReward'],
                'pos_credit' => $summary?->pos_credit ?? $item['creditAmount'],
                'pos_debit' => $summary?->pos_debit ?? $item['debitAmount'],
                'status' => $summary?->status ?? 'failed',
            ];
        });

        if ($failures !== []) {
            return response()->json([
                'success' => false,
                'message' => 'Some POS payment summaries could not be generated.',
                'failed_pos' => $failures,
                'summaries' => $summaryRows,
                'created_count' => $createdCount,
                'repaired_count' => $repairedCount,
                'existing_count' => $existingCount,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $dailyPosData->isEmpty()
                ? 'No POS with fully verified transactions was found for this date.'
                : 'Payment summaries generated for verified transactions.',
            'summaries' => $summaryRows,
            'created_count' => $createdCount,
            'repaired_count' => $repairedCount,
            'existing_count' => $existingCount,
        ]);
    }

    public function getledger(Request $request)
    {
        $rules = [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ];
        if ($request->routeIs('admin.get.ledger')) {
            $rules['pos_id'] = ['required', 'integer', 'exists:pos_systems,id'];
        }
        $request->validate($rules);

        $pos = $request->routeIs('admin.get.ledger')
            ? PosModel::findOrFail($request->integer('pos_id'))
            : null;
        $ledger = Payment::getLedgerData($request, $pos?->user_id);
        // $pagination = $ledger->links()->render();
        return response()->json(['data' => $ledger]);
    }

    //pos stuffs

    public function payment_index_pos()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $name = $user_profile->name;
        return view('pos.payment.payment', compact('userId', 'name'));
    }

    public function ledgerExport(Request $request)
    {
        $rules = [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ];
        if ($request->routeIs('admin.ledger.export')) {
            $rules['pos_id'] = ['required', 'integer', 'exists:pos_systems,id'];
        }
        $request->validate($rules);

        $pos = $request->routeIs('admin.ledger.export')
            ? PosModel::findOrFail($request->integer('pos_id'))
            : null;
        $data = Payment::getLedgerDataExport($request, $pos?->user_id, $pos?->name);
        $accountName = $pos?->name ?? auth()->user()->name;
        $filename = 'AccountLedger_' . \Illuminate\Support\Str::slug($accountName) . '_' . now()->format('Ymd') . '.xlsx';

        return Excel::download(new AccountLedgerExport($data), $filename);
    }

    public function getReceipt(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $result = Payment::getReceipt($request);
        if ($result) {
            return response()->json([
                'status' => 'success',
                'code'   => 200,
                'data'   => $result,
            ]);
        } else {
            return response()->json([
                'status'  => 'failed',
                'code'    => 500,
                'message' => 'Something Went Wrong!',
            ]);
        }
    }

    public function store_receipt(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:9999999999999.99'],
            'pay_by' => ['required', 'in:0,1'],
            'pay_to' => ['required', 'string', 'exists:pos_systems,user_id'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:2000'],
        ]);

        $res = Payment::store_receipt($request);
        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Receipt recorded successfully.',
                'code'   => 200,
            ]);
        } else {
            return response()->json([
                'success'  => false,
                'code'    => 500,
                'message' => 'The receipt could not be recorded. Please try again.',
            ]);
        }
    }
}
