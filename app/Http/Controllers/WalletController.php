<?php
namespace App\Http\Controllers;

use App\Exports\PosMsrExport;
use App\Exports\WalletExport;
use App\Imports\WalletImport;
use App\Models\PosModel;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Wallet;
use App\Services\AiSensyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class WalletController extends Controller
{
    public function walletManage(Request $request)
    {
        $pos_id = $request->query('user_id');
        $user   = User::where('user_id', $pos_id)->first();
        $userId = Auth::user()->user_id;
        // dd($userId);
        $pos = PosModel::where('user_id', $userId)->first();

        // Transactions for display
        $transactionQuery = Wallet::where('user_id', $user->id);

        if ($request->filled('transaction_date')) {
            $transactionQuery->whereDate('transaction_date', $request->transaction_date);
        }

        $walletList = $transactionQuery->orderByDesc('id')->get();

        // Check if specific-date balance was requested
        $walletBalance   = null;
        $balanceNotFound = false;

        if ($request->filled('balance_date')) {
            $walletBalance = Wallet::where('user_id', $user->id)
                ->whereDate('transaction_date', $request->balance_date)
                ->first();

            $balanceNotFound = ! $walletBalance;
        }

        // If no date filter, or balance found, get total
        if (! $balanceNotFound) {
            $walletBalance = self::get_total_wallet_amount($user->id);
        }

        $transactionsNotFound = $request->filled('transaction_date') && $walletList->isEmpty();

        return view('pos.wallet_manage', compact(
            'user',
            'walletList',
            'walletBalance',
            'balanceNotFound',
            'transactionsNotFound'
        ));
    }

    public function get_total_wallet_amount($userId)
    {
        $userwallet = UserWallet::where('user_id', $userId)->get();
        if ($userwallet->isEmpty()) {
            return 0;
        }

        $walletAmount = $userwallet->sum('wallet_amount');
        $usedAmount   = $userwallet->sum('used_amount'); // Use the same collection instead of hitting DB again

        $walletBalance = $walletAmount - $usedAmount;

        return $walletBalance > 0 ? $walletBalance : 0;
    }

    public function dsr(Request $request)
    {
        $posId = auth()->user()->user_id;
        $pos   = PosModel::where('user_id', $posId)->first();

        // Check if POS exists
        if (! $pos) {
            return redirect()->back()->with('error', 'POS not found.');
        }

        $query = Wallet::where('pos_id', $pos->id);

        // Optional: Filter by start_date and end_date
        if (
            $request->has('start_date') && ! empty($request->start_date) &&
            $request->has('end_date') && ! empty($request->end_date)
        ) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate   = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('insert_date', [$startDate, $endDate]);
        } else {
            // Show only current date transactions when no start and end date are provided
            $query->whereDate('insert_date', now()->toDateString());
        }

        // Search by mobile number if search term is provided
        if ($request->has('search') && ! empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('mobilenumber', 'LIKE', "%{$searchTerm}%");
        }

        // Fetch the results
        $wallets = $query->orderBy('id', 'desc')->simplePaginate(15);

        // Retain filters in pagination
        $wallets->appends($request->only(['search', 'start_date', 'end_date']));
        // dd($wallets->toArray());
        return view('pos.dsr', compact('wallets'));
    }

    public function export(Request $request)
    {
        $startDate = $request->has('start_date') && ! empty($request->start_date) ? $request->start_date : null;
        $endDate   = $request->has('end_date') && ! empty($request->end_date) ? $request->end_date : null;

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
        $pos              = PosModel::where('user_id', $userId)->first();
        $randomNumber     = mt_rand(1, 99999);
        $invoiceNumber    = $randomNumber + 1;
        $invoice          = 'S' . str_pad($invoiceNumber, 6, '0', STR_PAD_LEFT);
        $amount           = $request->amount;
        $mobilenumber     = $request->mobilenumber;
        $transaction_date = $request->transaction_date;
        $user_id          = $request->user_id;
        $pay_by           = $request->pay_by;
        $alt_pay_by       = $request->alternative_pay_by;

        // dd($pos->transaction_charge);
        $transaction_charge = $pos ? $pos->transaction_charge : 0;
        $transaction_amount = $amount * ($transaction_charge / 100);

        $walletUsedAmount = 0;

        $userWalletEntry                   = new UserWallet();
        $userWalletEntry->invoice          = $invoice;
        $userWalletEntry->user_id          = $user_id;
        $userWalletEntry->mobilenumber     = $mobilenumber;
        $userWalletEntry->transaction_date = $transaction_date;
        $userWalletEntry->pay_by           = 'wallet';
        $userWalletEntry->trans_type       = 'debit';
        $userWalletEntry->pos_id           = $pos->id ?? null;

        if ($pay_by == 'wallet') {
            $userWallet    = UserWallet::where('user_id', $user_id)->get();
            $walletBalance = $userWallet ? $userWallet->sum('wallet_amount') - UserWallet::where('user_id', $user_id)->sum('used_amount') : 0;

            if ($walletBalance <= 0) {
                return redirect()->back()->with('error', 'Wallet is empty. Payment cannot be processed.');
            }

            if ($walletBalance > 0) {
                $walletUsedAmount = min($amount, $walletBalance);
                $remainingAmount  = $amount - $walletUsedAmount;

                $userWalletEntry->used_amount = $walletUsedAmount;
                $userWalletEntry->save();

                $walletEntry                     = new Wallet();
                $walletEntry->user_id            = $user_id;
                $walletEntry->invoice            = $invoice;
                $walletEntry->mobilenumber       = $mobilenumber;
                $walletEntry->transaction_date   = $transaction_date;
                $walletEntry->billing_amount     = $amount;
                $walletEntry->transaction_amount = $transaction_amount;

                if ($walletBalance >= $amount) {
                    $walletEntry->amount_wallet = $amount;
                    $walletEntry->pay_by        = 'wallet';
                    $walletEntry->tran_type     = 'debit';
                    $walletEntry->amount        = 0;
                } else {
                    $walletEntry->amount_wallet = $walletUsedAmount;
                    $walletEntry->pay_by        = $alt_pay_by;
                    $walletEntry->tran_type     = 'credit';
                    $walletEntry->status        = 1;
                    $walletEntry->amount        = $remainingAmount;
                }

                $walletEntry->pos_id      = $pos->id ?? null;
                $walletEntry->insert_date = now();
                $walletEntry->save();
            }
        } else {
            $remainingAmount      = $amount;
            $amount_wallet        = $amount * (5 / 100);
            $currentwalletBalance = self::get_total_wallet_amount($user_id);
            if ($currentwalletBalance <= $amount_wallet) {
                $amount_wallet = $currentwalletBalance;
            }
            $dsrlist                     = new Wallet();
            $dsrlist->invoice            = $invoice;
            $dsrlist->user_id            = $user_id;
            $dsrlist->amount             = $request->paying_amount;
            $dsrlist->pay_by             = $pay_by;
            $dsrlist->mobilenumber       = $mobilenumber;
            $dsrlist->transaction_date   = $transaction_date;
            $dsrlist->tran_type          = 'credit';
            $dsrlist->billing_amount     = $amount;
            $dsrlist->amount_wallet      = $amount_wallet;
            $dsrlist->transaction_amount = $transaction_amount;
            $dsrlist->pos_id             = $pos->id ?? null;
            $dsrlist->insert_date        = now();
            $dsrlist->status             = 1;
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
        $pos   = PosModel::where('user_id', $posId)->first();

        if (! $pos) {
            return redirect()->back()->with('error', 'POS not found.');
        }

        // Get the latest transaction month or fallback to previous month
        $defaultMonth = Wallet::where('pos_id', $pos->id)
            ->whereNotNull('transaction_date')
            ->orderByDesc('transaction_date')
            ->value(DB::raw('DATE_FORMAT(transaction_date, "%Y-%m")'));

        $selectedMonth = $request->input('month') ?: Carbon::now()->subMonth()->format('Y-m');

        // Extract year and month from selectedMonth
        [$year, $month] = explode('-', $selectedMonth);

        $monthlySales = Wallet::select(
            DB::raw('SUM(transaction_amount) as total_transaction_amount'),
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('COUNT(*) as total_transactions'),
            DB::raw('SUM(billing_amount) as total_billing_amount'),
            DB::raw('SUM(amount_wallet) as credit')
        )
            ->where('pos_id', $pos->id)
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->orderByDesc('date')
            ->simplePaginate(15)
            ->through(function ($item) {
                $item->debit  = max(0, $item->total_transaction_amount - $item->credit);
                $item->dates  = Carbon::parse($item->date)->format('d-M-Y');
                $item->status = $item->debit == 0 ? 'Paid' : 'Pending';
                return $item;
            });

        return view('pos.msr', compact('pos', 'monthlySales'));
    }

    public function exportMsr(Request $request)
    {
        $posId = auth()->user()->user_id;
        $pos   = PosModel::where('user_id', $posId)->first();
        // if ($pos) {
        //     $posId = $pos->id;
        // }

        $query = Wallet::select(
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('COUNT(*) as total_transactions'),
            DB::raw('SUM(billing_amount) as total_billing_amount'),
            DB::raw('SUM(amount_wallet) as credit')
        )->where('pos_id', $pos->id)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->orderBy('date', 'desc');

        if ($request->has('month') && ! empty($request->month)) {
            $selectedMonth = $request->month;
            $query->whereRaw('DATE_FORMAT(transaction_date, "%Y-%m") = ?', [$selectedMonth]);
        }

        $filteredData = $query->get()->map(function ($item) {
            $transaction_debit = ($item->total_billing_amount) * (5 / 100);
            $item->debit       = $item->credit >= $transaction_debit ? 0 : $transaction_debit - $item->credit;
            $item->dates       = Carbon::parse($item->date)->format('d-M-Y');
            $item->status      = $item->debit == 0 ? 'Paid' : 'Pending';
            // dd($item);
            return $item;
        });
        // dd($filteredData);
        return Excel::download(new PosMsrExport($filteredData), 'MonthlySalesReport.csv');
    }

    public function journal(Request $request)
    {
        if ($request->has(['start_date', 'end_date'])) {

            $request->validate([
                'start_date' => 'required|date',
                'end_date'   => 'required|date|after_or_equal:start_date',
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
        $dsrLists = Wallet::with(['user', 'getPos'])
            ->whereHas('getPos', function ($query) use ($posId) {
                $query->where('pos_id', $posId);
            })->orderBy('id', 'desc')->simplePaginate(15)
            ->through(function ($data) {
                $transaction_charge = $data->billing_amount * (5 / 100);
                if ($data->amount_wallet > $transaction_charge) {
                    $data->credit = $data->amount_wallet - $transaction_charge;
                    $data->debit  = 0;
                } elseif ($data->amount_wallet == 0) {
                    $data->debit  = $transaction_charge;
                    $data->credit = 0;
                } else {
                    $data->debit  = $transaction_charge - $data->amount_wallet;
                    $data->credit = 0;
                }
                return $data;
            });

        // dd($DsrList);
        return view('pos.unverified_user', compact('dsrLists'));
    }

    public function updateStatus($id)
    {
        $user   = Auth::user();
        $wallet = Wallet::getWallet($id);
        // dd($user);
        if ($wallet->status == 1) {
            return redirect()->back()->with('info', 'Already Verified');
        }
        $params = [
            (string) $wallet->user->name,
            (string) $wallet->billing_amount,
            (string) $user ? $user->name : "Not Available",
            (string) $wallet->invoice,
            (string) $wallet->amount,
            (string) $wallet->amount_wallet,
            (string) $wallet->reward_amount,
        ];

        $whatsapp  = new AiSensyService();
        $msg_reslt = $whatsapp->sendTransactionMessage($wallet->user->mobilenumber, $params);
        Log::info('Message result', [$msg_reslt]);
        $wallet->status = $wallet->status = 1;
        $wallet->save();
        return redirect()->back()->with('success', 'Verified Successfully!');
    }
    public function update(Request $request, $id)
    {
        $customerWallet = Wallet::find($id);

        if (! $customerWallet) {
            return redirect()->back()->with('error', 'Customer not found!');
        }
        $customerWallet->billing_amount = $request->input('billing_amount');
        $customerWallet->amount         = $request->input('amount');
        $customerWallet->amount_wallet  = $request->input('amount_wallet');

        $customerWallet->save();

        return redirect()->back()->with('success', 'Updated successfully!');
    }
}
