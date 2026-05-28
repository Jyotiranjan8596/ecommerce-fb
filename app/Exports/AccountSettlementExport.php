<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccountSettlementExport implements FromCollection, WithHeadings, WithStyles
{
    protected $settlement;

    public function __construct($settlement)
    {
        $this->settlement = $settlement;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->settlement as $item) {

            // if array
            $item = (object) $item;

            $data[] = [
                $item->intiate_date ?? 'N/A',
                $item->total_transaction ?? 0,
                $item->total_billing_amount ?? 0,
                $item->by_cash ?? 0,
                $item->by_wallet ?? 0,
                $item->by_reward ?? 0,
                $item->pos_credit ?? 0,
                $item->pos_debit ?? 0,
                $item->status ?? 'N/A',
            ];
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Transaction',
            'Billing Amount',
            'Cash/UPI',
            'Wallet',
            'Reward',
            'Credit',
            'Debit',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // First row (headings)
        ];
    }
}
