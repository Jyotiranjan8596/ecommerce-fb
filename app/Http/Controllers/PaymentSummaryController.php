<?php

namespace App\Http\Controllers;

use App\Exports\AccountLedgerExport;
use App\Exports\AccountSettlementExport;
use App\Jobs\AccountLedgerJob;
use App\Models\Payment;
use App\Models\PaymentSummary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PaymentSummaryController extends Controller
{
    public function saveSummaryData(Request $request)
    {
        try {
            $data   = request()->all();
            $result = PaymentSummary::store_summary($data);
            if ($result != 1) {
                return response()->json([
                    'success' => true,
                    'code'    => 200,
                    'message' => 'Payment summary saved successfully!',
                ]);
            } else if ($result == 1) {
                return response()->json([
                    'success' => false,
                    'code'    => 500,
                    'message' => 'Payment already Submitted for this date',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code'    => 500,
                    'message' => 'Something went wrong while submit payment.!',
                ]);
            }
        } catch (\Exception $e) {
            Log::info("Summary Controller" . $e->getMessage());
            return response()->json([
                'success' => false,
                'code'    => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function export_settlement()
    {
        try {
            $result = PaymentSummary::fetch_summary_pos();
            if ($result) {
                return Excel::download(new AccountSettlementExport($result), 'AccountSettlement.csv');
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
        return view('admin.settlement_payment.settlement', compact('settlements'));
    }

    public function verify_settlement(Request $request)
    {
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

        return $pdf->download('Invoice-' . $settlement['pos_name'] . $settlement['pos_user_id'] . '.pdf');
    }

    public function downloadPosInvoice($id)
    {
        $settlement = PaymentSummary::getposinvoice($id);
        // dd($settlement);
        $pdf = Pdf::loadView('admin.settlement_payment.invoice', compact('settlement'))
            ->setPaper('a4');

        return $pdf->download('Invoice-' . $settlement['pos_name'] . $settlement['pos_user_id'] . '.pdf');
    }

    public function ledger_index()
    {
        return view('admin.payment.account_ledger');
    }

    public function payment_index()
    {
        return view('admin.payment.payment');
    }

    public function create_payment(Request $request)
    {
        $res = Payment::create_payment($request);
        if ($res) {
            return response()->json([
                'success' => true,
                'message' => 'Payment Succesfull'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Payment Failed'
            ]);
        }
    }

    public function getledger(Request $request)
    {
        $ledger = Payment::getLedgerData($request);
        $pagination = $ledger->links()->render();
        return response()->json([
            'data' => $ledger,
            'pagination' => $pagination
        ]);
    }

    //pos stuffs

    public function payment_index_pos()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->user_id;
        $name = $user_profile->name;
        return view('pos.payment.payment', compact('userId', 'name'));
    }

    public function pos_ledger_index()
    {
        return view('pos.payment.ledger');
    }

    public function ledgerExport(Request $request)
    {
        $data = Payment::getLedgerDataExport($request);
        // dd($data->toArray());
        return Excel::download(new AccountLedgerExport($data), 'account_ledger_freebazar.xlsx');
    }
}
