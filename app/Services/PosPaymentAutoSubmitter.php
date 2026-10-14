<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PosPaymentAutoSubmitter
{
    public function submitWhenReady(int $posId, string $date): string
    {
        return DB::transaction(function () use ($posId, $date) {
            $summary = PaymentSummary::query()
                ->where('pos_id', $posId)
                ->whereDate('date', $date)
                ->lockForUpdate()
                ->first();

            if (! $summary) {
                throw new RuntimeException("Payment summary is missing for POS {$posId} on {$date}.");
            }

            if (Wallet::query()
                ->where('pos_id', $posId)
                ->whereDate('transaction_date', $date)
                ->pendingDsrVerification()
                ->exists()) {
                return 'pending_transactions';
            }

            if ($summary->status !== 'pending') {
                return 'already_settled';
            }

            $posUserId = $summary->pos_system()->value('user_id');
            if (! $posUserId) {
                throw new RuntimeException("POS {$posId} has no linked user for payment submission.");
            }

            $generatedEntry = Payment::query()
                ->where('payment_summury_id', $summary->id)
                ->where('remark', 'Auto Generated')
                ->first();
            if (! $generatedEntry) {
                throw new RuntimeException("Generated payment is missing for POS {$posId} on {$date}.");
            }

            $amountCents = (int) round((float) $summary->admin_credit * 100);
            if ($amountCents <= 0) {
                return 'no_payment_due';
            }

            $submittedCents = (int) round((float) Payment::query()
                ->where('payment_summury_id', $summary->id)
                ->where('from', $posUserId)
                ->where('to', '666666')
                ->where(function ($query) {
                    $query->whereNull('remark')
                        ->orWhere('remark', '!=', 'Auto Generated');
                })
                ->sum('amount') * 100);
            $remainingCents = max(0, $amountCents - $submittedCents);
            if ($remainingCents === 0) {
                return 'already_submitted';
            }

            $voucherNumber = Helper::generateVoucherNumber();
            Payment::query()->insert([
                'payment_summury_id' => $summary->id,
                'transaction_date' => now()->toDateString(),
                'voucher_number' => $voucherNumber,
                'reference_number' => $generatedEntry->reference_number,
                'account_details' => null,
                'pay_by' => '1',
                'due' => '0.00',
                'amount' => number_format($remainingCents / 100, 2, '.', ''),
                'to' => '666666',
                'from' => $posUserId,
                'created_by' => $posUserId,
                'updated_by' => $posUserId,
                'remark' => 'Auto Submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return 'submitted';
        });
    }
}
