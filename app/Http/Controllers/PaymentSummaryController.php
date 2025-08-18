<?php
namespace App\Http\Controllers;

use App\Models\PaymentSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
