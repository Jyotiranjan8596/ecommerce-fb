<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class UserAllTransactionController extends Controller
{
    public function allUserTransaction(){
        return view('admin.users.user_transactions');
    }

    public function userAllTransactions(Request $request){
        $data = Wallet::getAllTransactions($request);
        $pagination = $data->links()->render();
        return response()->json([
            'data'=>$data,
            'pagination'=> $pagination
        ]);
    }
}
