<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccountLedgerExport implements FromCollection, WithHeadings
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
                $wallet->date,
                $wallet->voucher_number ?? '',
                $wallet->reference_number ?? '',
                $wallet->debit,
                $wallet->credit
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
            'Date',
            'Voucher No',
            'Ref No',
            'Debit',
            'Credit'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // First row (headings)
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
