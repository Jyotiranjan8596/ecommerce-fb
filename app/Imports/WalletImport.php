<?php

namespace App\Imports;

use App\Models\PosModel;
use App\Models\Wallet;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WalletImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        
        // $existingWallet = Wallet::where('invoice', $row['voucher_no'])->first();
        // if ($existingWallet) {
            // throw new \Exception("This file has already been uploaded.");
        //     return $existingWallet;
        // }
        // if (!empty($row['mobile'])) {
        //     $user_id = User::where('mobilenumber', $row['mobile'])->value('id');
            // Log::info($user_id);
        // } else {
        //     $user_id = null;
        // }
        // $wallet = Wallet::create([
        //     'invoice'            => $row['voucher_no'],
        //     'billing_amount'     => $row['amount'],
        //     'pos_id'             => $row['branch'],
        //     'amount'             => $row['amount_paid_by_1'],
        //     'pay_by'             => $row['paid_by_1'],
        //     // 'transaction_amount' => $row['transaction_amount'],
        //     'pay_by_wallet'      => $row['paid_by_2'],
        //     'amount_wallet'      => $row['amount_paid_by_2'] ?? 0,
        //     'user_id'          => $user_id,
        //     'mobilenumber'       => $row['mobile'] ?? null,
        //     'transaction_date'   => date('Y-m-d', strtotime($row['date'])),
        //     'insert_date'        => date('Y-m-d H:m:s')
        // ]);
        // $wallet->save();

        // if (!is_null($row['amount_paid_by_2'])) {
        //     $userWallet = new UserWallet([
        //         'user_id'         => $wallet->user_id, 
        //         'pos_id'          => $row['branch'],
        //         'invoice'         => $row['voucher_no'],
        //         'used_amount'     => $row['amount_paid_by_2'],
        //         'pay_by'          => $row['paid_by_2'],
        //         'mobilenumber'    => $row['mobile_number'] ?? null,
        //         'transaction_date' => date('Y-m-d', strtotime($row['date'])),
        //         'trans_type'      => 'debit',
        //     ]);
        //     $userWallet->save();
        // }

        // return $wallet;
     
        return Wallet::create([
            'invoice'            => $row['voucher_no'],
            'billing_amount'     => $row['amount'],
            'pos_id'             => $row['branch'],
            'amount'             => $row['amount_paid_by_1'],
            'pay_by'             => $row['paid_by_1'],
            'pay_by_wallet'      => $row['paid_by_2'],
            'amount_wallet'      => $row['amount_paid_by_2'] ?? 0,
            'user_id'            => User::where('mobilenumber', $row['mobile'])->value('id'),
            'mobilenumber'       => $row['mobile'] ?? null,
            'transaction_date'   => Carbon::parse($row['date'])->format('Y-m-d'),
            'insert_date'        => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    
    }
}
