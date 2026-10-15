<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserAllTransactionExport;
use App\Http\Controllers\Controller;
use App\Models\PosModel;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserAllTransactionController extends Controller
{
    public function allUserTransaction()
    {
        return view('admin.users.user_transactions');
    }

    public function userAllTransactions(Request $request)
    {
        $request->validate([
            'transaction_date' => ['nullable', 'date'],
            'user_search' => ['nullable', 'string', 'max:100'],
        ]);
        $data = Wallet::getAllTransactions($request);
        $pagination = $data->links()->render();
        return response()->json([
            'data' => $data,
            'pagination' => $pagination
        ]);
    }

    public function userAllTransactionsExport(Request $request)
    {
        $request->validate([
            'transaction_date' => ['nullable', 'date'],
            'user_search' => ['nullable', 'string', 'max:100'],
        ]);
        $data =  Wallet::export_user_transaction($request);

        $userIds = collect();
        if ($request->filled('user_search')) {
            $search = $request->input('user_search');
            $pattern = "%{$search}%";

            $userIds = User::query()
                ->where(function ($query) use ($pattern) {
                    $query->where('user_id', 'LIKE', $pattern)
                        ->orWhere('mobilenumber', 'LIKE', $pattern);
                })
                ->pluck('user_id')
                ->merge(
                    PosModel::query()
                        ->where(function ($query) use ($pattern) {
                            $query->where('user_id', 'LIKE', $pattern)
                                ->orWhere('mobilenumber', 'LIKE', $pattern)
                                ->orWhereHas('user', function ($userQuery) use ($pattern) {
                                    $userQuery->where('user_id', 'LIKE', $pattern)
                                        ->orWhere('mobilenumber', 'LIKE', $pattern);
                                });
                        })
                        ->pluck('user_id')
                )
                ->filter()
                ->unique()
                ->values();
        }

        $userId = match ($userIds->count()) {
            0 => 'All_Users',
            1 => Str::slug($userIds->first(), '_'),
            default => 'Multiple_Users',
        };
        $date = $request->input('transaction_date', today()->toDateString());
        $filename = "Today's_Sales_{$userId}_" . Carbon::parse($date)->format('d,m,Y') . '.xlsx';

        return Excel::download(new UserAllTransactionExport($data), $filename);
    }

    public function updateTransaction(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'billing_amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'amount_wallet' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'reward_amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ]);

        $wallet->update($validated);

        return response()->json(['message' => 'Transaction amounts updated successfully.']);
    }

    public function deleteTransaction(Wallet $wallet)
    {
        DB::transaction(function () use ($wallet) {
            $wallet->delete();
        });

        return response()->json(['message' => 'Transaction deleted successfully.']);
    }
}
