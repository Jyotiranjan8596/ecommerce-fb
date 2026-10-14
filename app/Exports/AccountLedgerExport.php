<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccountLedgerExport implements FromCollection, WithHeadings, WithStyles
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
        $rows = collect([[
            '',
            '',
            'Opening Balance — ' . $this->data['name'],
            'Period: ' . ($this->data['from'] ?: 'Beginning') . ' to ' . ($this->data['to'] ?: 'Today'),
            '',
            '',
            '',
            '',
            '',
            '',
            number_format($this->data['opening_balance'], 2, '.', '') . ' ' . $this->data['opening_balance_type'],
        ]]);

        foreach ($this->data['rows'] as $row) {
            $details = collect([$row->account_details, $row->remark])
                ->filter()
                ->implode(' | ');

            $rows->push([
                $row->date,
                $row->voucher_number ?? '',
                $row->account_name ?? '',
                $details,
                $row->reference_number ?? '',
                $row->payment_method ?? '',
                $row->debit,
                $row->credit,
                $row->due,
                $row->gst_no,
                number_format($row->balance, 2, '.', '') . ' ' . $row->balance_type,
            ]);
        }

        if ($this->data['rows']->isEmpty()) {
            $rows->push([
                '',
                '',
                'No transactions in selected period',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ]);
        }

        $rows->push([
            '',
            '',
            'Period Total / Closing Balance',
            '',
            '',
            '',
            $this->data['total_debit'],
            $this->data['total_credit'],
            '',
            '',
            $this->data['total_balance'] . ' ' . $this->data['total_balance_type'],
        ]);

        return $rows;
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
            'Account',
            'Details',
            'Reference No',
            'Payment Method',
            'Debit',
            'Credit',
            'Due',
            'GST No',
            'Running Balance',
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
