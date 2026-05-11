<?php

namespace App\Exports;

use App\Models\PaymentSummary;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentSummaryExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = [$data]; // single row
    }
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Pos Id',
            'Pos Name',
            'Transaction Date',
            'Total Transactions',
            'Total Billing Amount',
            'Pay be Cash/Upi',
            'Pay by Wallet',
            'Pay by Reward',
            'Payble Amount',
            'Receivable Amount'
            //this excel report is sending to admin so the "Payble Amount" is "Receivable Amount" and "Receivable Amount" is "Payble Amount"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // First row
                'font' => ['bold' => true],
            ],
        ];
    }
}
