<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentSummaryPosExport implements FromArray, WithHeadings, WithStyles
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
            'INVOICE',
            'POS ID',
            'MOBILE',
            'NAME',
            'BILLING AMOUNT',
            'Wallet Deduct',
            'Reward Deduct',
            'Net Pay',
            'Remaining Wallet',
            'Remaining Reward',
            'TRANSACTION DATE',
            'STATUS',
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
