<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserAllTransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserAllTransactionController extends Controller
{
    public function allUserTransaction()
    {
        return view('admin.users.user_transactions');
    }

    public function userAllTransactions(Request $request)
    {
        $data = Wallet::getAllTransactions($request);
        $pagination = $data->links()->render();
        return response()->json([
            'data' => $data,
            'pagination' => $pagination
        ]);
    }

    public function userAllTransactionsExport(Request $request)
    {
        $data =  Wallet::export_user_transaction($request);
        return Excel::download(new UserAllTransactionExport($data), 'user_all_transaction.xlsx');
    }
}
