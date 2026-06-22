<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserAllTransactionExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection() {
        return collect($this->data)->map(function ($item) {
            return [
                $item->user_name,
                $item->mobile,
                $item->pos_name ?? 0,
                $item->invoice ?? 0,
                $item->billing_amount,
                $item->amount_wallet,
                $item->reward_amount,
                $item->amount ?? 0,
                $item->remaining_amount ?? 0,
                $item->remaining_reward,
                $item->trans_date
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Mobile',
            'POS',
            'Invoice',
            'Billing Amount',
            'Wallet Deduct',
            'Reward Deduct',
            'Net Pay',
            'Remaining Wallet',
            'Remaining Reward',
            'Transaction Date'
        ];
    }
}
