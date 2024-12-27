<?php

namespace App\Exports;

use App\Models\Wallet;
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
            $sponsor_id = 'N/A';
            if ($item->user) {
                if ($item->user->sponsor_id) {
                    $sponsorUser = \App\Models\User::where('id', $item->user->sponsor_id)->first();
                    $sponsor_id = $sponsorUser ? $sponsorUser->user_id : 'N/A';
                } else {
                    $sponsor_id = 'N/A';
                }
            }

            return [
                'month' => Carbon::parse($item->transaction_month)->format('F Y'),
                'mobilenumber' => $item->mobilenumber,
                'sponsor_id' => $sponsor_id,
                'total_billing_amount' => $item->total_billing_amount ?? 0,
                'sponsor_expenditure' => $item->sponsor_expenditure ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'MONTH',
            'MOBILE NUMBER',
            'SPONSOR ID',
            'TOTAL BILLING AMOUNT',
            'SPONSOR EXPENDITURE'
        ];
    }
}
