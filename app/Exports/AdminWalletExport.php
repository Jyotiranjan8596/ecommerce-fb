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
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return collect($this->data)->map(function ($wallet) {
            return [
                $wallet->month,
                $wallet->user_data?->user_id ?? '',
                $wallet->user_data?->name ?? '',
                $wallet->mobilenumber,
                $wallet->trans_type,
                $wallet->rounded_wallet_amount ?? 0,
                $wallet->rounded_reward_point ?? 0,
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
            'Month',
            'User ID',
            'Name',
            'Mobile Number',
            'Payment Mode',
            'Wallet Amount',
            'Reward Points',
        ];
    }
}
