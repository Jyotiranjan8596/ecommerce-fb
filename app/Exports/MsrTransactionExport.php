<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MsrTransactionExport implements FromCollection, WithHeadings
{
    public function __construct(private Collection $transactions)
    {
    }

    public function collection(): Collection
    {
        return $this->transactions->values()->map(function ($transaction, $index) {
            return [
                $index + 1,
                $transaction->invoice ?: '—',
                $transaction->user->name ?? '—',
                $transaction->mobilenumber ?: '—',
                (float) ($transaction->billing_amount ?? 0),
                (float) ($transaction->amount_wallet ?? 0),
                (float) ($transaction->reward_amount ?? 0),
                (float) ($transaction->amount ?? 0),
                $transaction->transaction_date
                    ? Carbon::parse($transaction->transaction_date)->format('d/m/Y')
                    : '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Invoice',
            'Customer',
            'Mobile Number',
            'Billing Amount',
            'Wallet Deduct',
            'Reward Deduct',
            'Net Pay',
            'Transaction Date',
        ];
    }
}
