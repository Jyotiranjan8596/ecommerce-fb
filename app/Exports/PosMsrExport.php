<?php

namespace App\Exports;

use App\Models\Wallet;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PosMsrExport implements FromCollection, WithHeadings
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
        return collect($this->data)->map(function ($item) {
            return [
                Carbon::parse($item->transaction_date)->format('d-M-Y'),
                $item->total_billing_amount,
                $item->total_transactions,
                $item->credit ?? 0,
                $item->debit ?? 0,
                $item->status,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Transaction Date',
            'Billing Amount',
            'Total Transactions',
            'Credit',
            'Debit',
            'Status'
        ];
    }
}
