<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DsrTransactionExport implements FromCollection, WithHeadings
{
    public function __construct(private Collection $transactions)
    {
    }

    public function collection(): Collection
    {
        return $this->transactions->values()->map(function ($transaction, $index) {
            $userWallet = $transaction->userWallets->first();

            return [
                $index + 1,
                $transaction->invoice ?: '—',
                $transaction->getPos?->user_id ?: $transaction->pos_id,
                $transaction->mobilenumber ?: '—',
                $transaction->user?->name ?: '—',
                (float) ($transaction->billing_amount ?? 0),
                (float) ($transaction->amount_wallet ?? 0),
                (float) ($transaction->reward_amount ?? 0),
                (float) ($transaction->amount ?? 0),
                (float) ($userWallet?->used_amount ?? 0),
                (float) ($userWallet?->remaining_points ?? 0),
                $transaction->transaction_date
                    ? Carbon::parse($transaction->transaction_date)->format('d/m/Y')
                    : '—',
                $transaction->status == 1 ? 'Verified' : 'Unverified',
                $transaction->statusChanges->first()?->actor_label ?? 'History unavailable',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl. No.',
            'Invoice',
            'POS ID',
            'Mobile',
            'Name',
            'Billing Amount',
            'Wallet Deduct',
            'Reward Deduct',
            'Net Pay',
            'Remaining Wallet',
            'Remaining Reward',
            'Transaction Date',
            'Status',
            'Last status change by',
        ];
    }
}
