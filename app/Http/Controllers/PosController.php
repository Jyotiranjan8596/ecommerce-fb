<?php

namespace App\Http\Controllers;

use App\Models\PosModel;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    public function index()
    {
        $user_profile = auth()->user();
        $userId       = $user_profile->id;
        $posId        = $user_profile->user_id;

        $pos = PosModel::where('user_id', $posId)->first();

        if (!$pos) {
            return view('pos.index', compact('userId', 'user_profile'))->withErrors(['POS not found']);
        }

        $pos_id = $pos->id;
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

    public function userList(Request $request)
    {
        $query = DB::table('users')->where('role', 3);
        if ($request->filled('search_by') && $request->filled('search_term')) {
            $searchBy = $request->input('search_by');
            $searchTerm = $request->input('search_term');

            if ($searchBy == 'user_id') {
                $query->where('user_id', $searchTerm);
            } elseif ($searchBy == 'name') {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            } elseif ($searchBy == 'mobilenumber') {
                $query->where('mobilenumber', 'like', '%' . $searchTerm . '%');
            }
        }

        $users = $query->orderBy('id', 'desc')->simplePaginate(15);

        return view('pos.user_list', compact('users'));
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
        $newpass = Auth::user();
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
            $pos = PosModel::where('user_id', $user->user_id)->first();

            if (!$pos) {
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
                'code' => 200,
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
}
