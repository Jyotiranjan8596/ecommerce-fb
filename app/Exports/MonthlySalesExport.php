<?php

namespace App\Exports;


use App\Models\Wallet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlySalesExport implements FromQuery, WithHeadings
{
    protected $searchTerm;

    public function __construct($searchTerm = null)
    {
        $this->searchTerm = $searchTerm;
    }

    public function query()
    {
        $query = Wallet::select('user_id', 'mobilenumber')
            ->selectRaw('SUM(billing_amount) as total_billing_amount')
            ->groupBy('user_id', 'mobilenumber');

        if ($this->searchTerm) {
            $query->where('mobilenumber', 'LIKE', "%{$this->searchTerm}%");
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Mobile Number',
            'Total Billing Amount',
            'Sponcer Expenditure'
        ];
    }
}
