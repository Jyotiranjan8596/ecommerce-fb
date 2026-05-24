<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use App\Models\User;
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
            return view('pos.index', compact('userId', 'user_profile'))->withErrors(['POS not found']);
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

        return view('pos.index', compact('userId', 'user_profile', 'msales', 'dsales', 'currentMonthWalletCredit'));
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
        // dd($request->all());
        $update_profile = User::find(auth()->id());
        $pos = PosModel::where('user_id', $update_profile->user_id)->first();
        if ($request->mobilenumber !== $update_profile->mobilenumber) {
            $update_profile->requestMobileNumberUpdate($request->mobilenumber);

            flash()->addSuccess('Your mobile number update request has been submitted and is awaiting admin approval.');
            return redirect()->route('user.index');
        }

        $update_profile->email         = $request->email;
        $update_profile->address       = $request->address;
        $update_profile->city          = $request->city;
        $update_profile->state         = $request->state;
        $update_profile->zip           = $request->zip;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $update_profile->image = $imageName;
            $pos->image = $imageName;
        }
        $pos->email         = $request->email;
        $pos->address       = $request->address;
        $pos->city          = $request->city;
        $pos->state         = $request->state;
        $pos->zip           = $request->zip;
        $pos->upi_id           = $request->upi_id;
        if ($update_profile->save() && $pos->save()) {
            flash()->addSuccess('Profile Update successfully.');
            return redirect()->route('user.index');
        }
    }
    public function changepassword()
    {
        return view('pos.changepassword');
    }
    public function newpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:5|confirmed',
        ]);
        $newpass           = Auth::user();
        $newpass->password = Hash::make($request->password);
        if ($newpass->save()) {
            flash()->addSuccess('Your password has been successfully updated.');
            return redirect()->route('pos.index');
        }
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

            $updatedRows = Wallet::where('pos_id', $pos->id)->update(['status' => 1]);

            $message = $updatedRows > 0
                ? "Customers have been successfully verified."
                : "Already Verified";

            return response()->json([
                'code'    => 200,
                'message' => $message,
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
        try {
            if ($request->wallet_ids) {
                $res     = Wallet::verifyDsr($request->wallet_ids);
                $message = $res > 0
                    ? "Transaction have been successfully verified."
                    : "No pending transactions to verify.";

                return response()->json([
                    'count'   => $res,
                    'success' => true,
                    'code'    => 200,
                    'message' => $message,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'code'    => 200,
                    'message' => "No transactions found for verification.",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying customers.',
                'error'   => $e->getMessage(),
            ], 200);
        }
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
}
