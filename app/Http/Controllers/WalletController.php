<?php

namespace App\Http\Controllers;

use App\Exports\MsrExport;
use App\Exports\WalletExport;
use App\Imports\WalletImport;
use App\Models\PosModel;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WalletController extends Controller
{
    public function walletManage(Request $request, $id)
    {
        $user = User::find($id);
        $userId = Auth::user()->user_id;
        // dd($userId);
        $pos = PosModel::where('user_id', $userId)->first();

        $walletBalanceQuery = Wallet::where('user_id', $user->id);
        $transactionQuery = Wallet::where('user_id', $user->id);

        if ($request->has('balance_date') && $request->balance_date != null) {
            $walletBalanceQuery->whereDate('transaction_date', $request->balance_date);
        }

        if ($request->has('transaction_date') && $request->transaction_date != null) {
            $transactionQuery->whereDate('transaction_date', $request->transaction_date);
        }

        $walletBalance = $walletBalanceQuery->first();
        $walletList = $transactionQuery->orderBy('id', 'desc')->get();
        // dd($walletList);

        $balanceNotFound = $request->has('balance_date') && !$walletBalance;
        $transactionsNotFound = $request->has('transaction_date') && $walletList->isEmpty();

        $userId = $user->id;
        $posId = $pos->id;

        $walletBalance = self::get_total_wallet_amount($userId);


        return view('pos.wallet_manage', compact('user', 'walletList', 'walletBalance', 'balanceNotFound', 'transactionsNotFound'));
    }

    public function get_total_wallet_amount($userId)
    {
        // dd($userId);
        $userwallet = UserWallet::where('user_id', $userId)->get();
        $totalusedAmount = UserWallet::where('user_id', $userId)->sum('used_amount');
        if ($userwallet) {
            $walletamount = $userwallet->sum('wallet_amount');
            $walletBalance = $walletamount - $totalusedAmount;
            return $walletBalance <= 0 ? 0 : $walletBalance;
        } else {
            $walletBalance = 0;
        }

        if ($walletBalance <= 0) {
            $walletBalance = 0;
        }
    }

    public function dsr(Request $request)
    {
        $posId = auth()->user()->user_id;
        $pos = PosModel::where('user_id', $posId)->first();

        // Check if POS exists
        if (!$pos) {
            return redirect()->back()->with('error', 'POS not found.');
        }

        $query = Wallet::where('pos_id', $pos->id);

        // Optional: Filter by start_date and end_date
        if (
            $request->has('start_date') && !empty($request->start_date) &&
            $request->has('end_date') && !empty($request->end_date)
        ) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('insert_date', [$startDate, $endDate]);
        } else {
            // Show only current date transactions when no start and end date are provided
            $query->whereDate('insert_date', now()->toDateString());
        }

        // Search by mobile number if search term is provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('mobilenumber', 'LIKE', "%{$searchTerm}%");
        }

        // Fetch the results
        $wallets = $query->orderBy('id', 'desc')->simplePaginate(15);

        // Retain filters in pagination
        $wallets->appends($request->only(['search', 'start_date', 'end_date']));

        return view('pos.dsr', compact('wallets'));
    }




    public function export(Request $request)
    {
        $startDate = $request->has('start_date') && !empty($request->start_date) ? $request->start_date : null;
        $endDate = $request->has('end_date') && !empty($request->end_date) ? $request->end_date : null;

        return Excel::download(new WalletExport($startDate, $endDate), 'daily_sales_report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            Excel::import(new WalletImport, $request->file('file'));

            return redirect()->back()->with('success', 'DSR File Uploaded Successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function dsrList(Request $request)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        $userId = Auth::user()->user_id;
        //  dd($userId);
        $pos = PosModel::where('user_id', $userId)->first();
        $randomNumber = mt_rand(1, 99999);
        $invoiceNumber = $randomNumber + 1;
        $invoice = 'S' . str_pad($invoiceNumber, 6, '0', STR_PAD_LEFT);
        $amount = $request->amount;
        $mobilenumber = $request->mobilenumber;
        $transaction_date = $request->transaction_date;
        $user_id = $request->user_id;
        $pay_by = $request->pay_by;
        $alt_pay_by = $request->alternative_pay_by;

        // dd($pos->transaction_charge);
        $transaction_charge = $pos ? $pos->transaction_charge : 0;
        $transaction_amount = $amount * ($transaction_charge / 100);

        $walletUsedAmount = 0;

        $userWalletEntry = new UserWallet();
        $userWalletEntry->invoice = $invoice;
        $userWalletEntry->user_id = $user_id;
        $userWalletEntry->mobilenumber = $mobilenumber;
        $userWalletEntry->transaction_date = $transaction_date;
        $userWalletEntry->pay_by = 'wallet';
        $userWalletEntry->trans_type = 'debit';
        $userWalletEntry->pos_id = $pos->id ?? null;

        if ($pay_by == 'wallet') {
            $userWallet = UserWallet::where('user_id', $user_id)->get();
            $walletBalance = $userWallet ? $userWallet->sum('wallet_amount') - UserWallet::where('user_id', $user_id)->sum('used_amount') : 0;

            if ($walletBalance <= 0) {
                return redirect()->back()->with('error', 'Wallet is empty. Payment cannot be processed.');
            }

            if ($walletBalance > 0) {
                $walletUsedAmount = min($amount, $walletBalance);
                $remainingAmount = $amount - $walletUsedAmount;

                $userWalletEntry->used_amount = $walletUsedAmount;
                $userWalletEntry->save();

                $walletEntry = new Wallet();
                $walletEntry->user_id = $user_id;
                $walletEntry->invoice = $invoice;
                $walletEntry->mobilenumber = $mobilenumber;
                $walletEntry->transaction_date = $transaction_date;
                $walletEntry->billing_amount = $amount;
                $walletEntry->transaction_amount = $transaction_amount;

                if ($walletBalance >= $amount) {
                    $walletEntry->amount_wallet = $amount;
                    $walletEntry->pay_by = 'wallet';
                    $walletEntry->tran_type = 'debit';
                    $walletEntry->amount = 0;
                } else {
                    $walletEntry->amount_wallet = $walletUsedAmount;
                    $walletEntry->pay_by = $alt_pay_by;
                    $walletEntry->tran_type = 'credit';
                    $walletEntry->status = 1;
                    $walletEntry->amount = $remainingAmount;
                }

                $walletEntry->pos_id = $pos->id ?? null;
                $walletEntry->insert_date = now();
                $walletEntry->save();
            }
        } else {
            $remainingAmount = $amount;
            $amount_wallet = $amount * (5 / 100);
            $currentwalletBalance = self::get_total_wallet_amount($user_id);
            if ($currentwalletBalance <= $amount_wallet) {
                $amount_wallet = $currentwalletBalance;
            }
            $dsrlist = new Wallet();
            $dsrlist->invoice = $invoice;
            $dsrlist->user_id = $user_id;
            $dsrlist->amount = $request->paying_amount;
            $dsrlist->pay_by = $pay_by;
            $dsrlist->mobilenumber = $mobilenumber;
            $dsrlist->transaction_date = $transaction_date;
            $dsrlist->tran_type = 'credit';
            $dsrlist->billing_amount = $amount;
            $dsrlist->amount_wallet = $amount_wallet;
            $dsrlist->transaction_amount = $transaction_amount;
            $dsrlist->pos_id = $pos->id ?? null;
            $dsrlist->insert_date = now();
            $dsrlist->status = 1;
            $dsrlist->save();

            $userWalletEntry->used_amount = $amount_wallet;
            $userWalletEntry->save();
        }

        // if ($pay_by == 'cash' || $pay_by == 'upi') {
        // }

        return redirect()->back()->with('success', 'Payment successfully.');
    }

    public function msr(Request $request)
    {
        $posId = auth()->user()->user_id;
        $pos = PosModel::where('user_id', $posId)->first();

        // Check if POS exists
        if (!$pos) {
            return redirect()->back()->with('error', 'POS not found.');
        }

        // Find the most recent month dynamically if no filter is provided
        $defaultMonth = Wallet::where('pos_id', $pos->id)
            ->whereNotNull('transaction_date')
            ->orderBy('transaction_date', 'desc')
            ->value(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'));

        // Fallback to previous month if no transaction exists
        $selectedMonth = $request->input('month') ?: Carbon::now()->subMonth()->format('Y-m');
        // dd($selectedMonth);

        // Build Query
        $query = Wallet::select(
            'user_id',
            'mobilenumber',
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
            DB::raw('SUM(billing_amount) as total_billing_amount')
        )
            ->whereNotNull('transaction_date')
            ->where('pos_id', $pos->id)
            ->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$selectedMonth])
            ->groupBy('user_id', 'mobilenumber', 'transaction_month');

        $monthlySales = $query->orderBy('transaction_month', 'desc')->simplePaginate(15);

        return view('pos.msr', compact('pos', 'monthlySales'));
    }


    public function exportMsr(Request $request)
    {
        $posId = auth()->user()->user_id;
        $pos = PosModel::where('user_id', $posId)->first();
        // if ($pos) {
        //     $posId = $pos->id;
        // }

        $query = Wallet::select(
            'user_id',
            'mobilenumber',
            DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as transaction_month'),
            DB::raw('SUM(billing_amount) as total_billing_amount')
        )
            ->whereNotNull('transaction_date')
            ->where('pos_id', $pos->id)
            ->groupBy('user_id', 'mobilenumber', 'transaction_month');

        if ($request->has('month') && !empty($request->month)) {
            $selectedMonth = $request->month;
            $query->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$selectedMonth]);
        }

        $filteredData = $query->get();

        return Excel::download(new MsrExport($filteredData), 'MonthlySalesReport.csv');
    }

    public function journal(Request $request)
    {
        if ($request->has(['start_date', 'end_date'])) {

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $transactions = Wallet::whereBetween('insert_date', [$request->start_date, $request->end_date])->get();
        } else {
            $transactions = collect();
        }
        $notransactions = $transactions->isEmpty();
        return view('pos.journal', compact('transactions', 'notransactions'));
    }

    public function unverified(Request $request)
    {
        $posId = auth()->user()->user_id;
        // dd($posId); 
        $pos = PosModel::where('user_id', $posId)->first();
        // dd($pos);
        if ($pos) {
            $posId = $pos->id;
            // dd($posId);
        }
        $DsrList = Wallet::with(['user', 'getPos'])
            ->whereHas('getPos', function ($query) use ($posId) {
                $query->where('pos_id', $posId);
            })->orderBy('id', 'desc')->simplePaginate(15);

        // dd($DsrList);
        return view('pos.unverified_user', compact('DsrList'));
    }

    public function updateStatus($id)
    {
        $wallet = Wallet::find($id);
        $wallet->status = $wallet->status == 0 ? 1 : 0;
        $wallet->save();

        return redirect()->back()->with('success', 'Verified successfully!');
    }
    public function update(Request $request, $id)
    {
        $customerWallet = Wallet::find($id);

        if (!$customerWallet) {
            return redirect()->back()->with('error', 'Customer not found!');
        }
        $customerWallet->billing_amount = $request->input('billing_amount');
        $customerWallet->amount = $request->input('amount');
        $customerWallet->amount_wallet = $request->input('amount_wallet');

        $customerWallet->save();

        return redirect()->back()->with('success', 'Updated successfully!');
    }
}
