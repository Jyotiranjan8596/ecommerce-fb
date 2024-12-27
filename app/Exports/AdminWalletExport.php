<?php

namespace App\Exports;

use App\Models\UserWallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminWalletExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return UserWallet::whereNotNull('wallet_amount')
            ->with('user')
            ->get()
            ->map(function ($wallet) {
                return [
                    'User ID' => $wallet->user->user_id,
                    'Month' => $wallet->month, 
                    'Wallet Amount' => $wallet->wallet_amount,
                    'Payment Mode' => $wallet->trans_type,
                    'Mobile Number' => $wallet->mobilenumber
                   
                ];
            });
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'User ID',
            'Month',
            'Wallet Amount',
            'Payment Mode',
            'Mobile Number'
            
        ];
    }
}
