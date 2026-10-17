<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MsrExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            $user = $item->user;
            $sponsorId = 'N/A';
            if ($user?->sponsor_id) {
                $sponsorId = \App\Models\User::whereKey($user->sponsor_id)->value('user_id') ?: 'N/A';
            }

            return [
                Carbon::createFromFormat('Y-m', $item->transaction_month)->format('y-M'),
                $item->mobilenumber,
                $user?->user_id ?? '',
                $sponsorId,
                $item->total_billing_amount ?? 0,
                $item->reward_points ?? 0,
                $item->wallet_amount ?? 0,
                $item->transaction_date
                    ? Carbon::parse($item->transaction_date)->format('m-d-y')
                    : '',
                $item->payment_mode ?: 'N/A',
                $item->sponsor_expenditure ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'MONTH',
            'MOBILE NUMBER',
            'USER ID',
            'SPONSOR ID',
            'TOTAL BILLING AMOUNT',
            'Reward Point',
            'Wallet Amount',
            'Transaction Date',
            'Payment Mode',
            'SPONSOR EXPENDITURE',
        ];
    }
}
