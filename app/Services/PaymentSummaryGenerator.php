<?php

namespace App\Services;

use App\Models\PaymentSummary;
use App\Models\PosModel;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentSummaryGenerator
{
    public function generateWhenComplete(int $posId, string $date): string
    {
        $hasPendingTransactions = Wallet::query()
            ->where('pos_id', $posId)
            ->whereDate('transaction_date', $date)
            ->pendingDsrVerification()
            ->exists();

        if ($hasPendingTransactions) {
            return 'pending';
        }

        $pos = PosModel::find($posId);
        if (! $pos) {
            throw new RuntimeException("POS {$posId} was not found while generating a payment summary.");
        }

        $summaryData = PosModel::getWalletDetails($date, $pos->user_id)
            ->first(fn (array $item) => (int) $item['pos_id'] === $posId
                && (int) $item['total_transactions'] > 0);

        if (! $summaryData) {
            return 'empty';
        }

        $result = PaymentSummary::store_summary([
            'pos_id' => $summaryData['pos_id'],
            'date' => $date,
            'total_transactions' => $summaryData['total_transactions'],
            'billing_amount' => $summaryData['billing_amount'],
            'cash_upi' => $summaryData['payByCashOrUpi'],
            'wallet' => $summaryData['payByWallet'],
            'reward' => $summaryData['payByReward'],
            'credit_amount' => $summaryData['creditAmount'],
            'debit_amount' => $summaryData['debitAmount'],
        ]);

        if ($result === 2) {
            return 'created';
        }

        if ($result === 4) {
            return 'repaired';
        }

        if ($result === 1) {
            return 'existing';
        }

        Log::error('Automatic POS payment summary generation failed.', [
            'pos_id' => $posId,
            'date' => $date,
            'result' => $result,
        ]);

        throw new RuntimeException("Payment summary generation failed for POS {$posId} on {$date}.");
    }
}
