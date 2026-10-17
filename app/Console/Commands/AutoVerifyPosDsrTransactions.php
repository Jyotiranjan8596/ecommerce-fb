<?php

namespace App\Console\Commands;

use App\Services\PaymentSummaryGenerator;
use App\Services\PosPaymentAutoSubmitter;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoVerifyPosDsrTransactions extends Command
{
    protected $signature = 'app:verify-pos-dsr-transactions';

    protected $description = 'Automatically verify pending POS DSR transactions after the next-day noon deadline';

    public function handle(): int
    {
        $now = now();
        $todayAtNoon = $now->copy()->startOfDay()->setTime(12, 0);

        if ($now->lt($todayAtNoon)) {
            $this->info('Automatic verification is scheduled for 12:00 noon.');

            return self::SUCCESS;
        }

        $verifiedCount = 0;
        $verificationFailures = [];
        $cutoff = $now->copy()->startOfDay();

        Wallet::query()
            ->whereNotNull('transaction_date')
            ->where('transaction_date', '<', $cutoff)
            ->pendingDsrVerification()
            ->orderBy('id')
            ->chunkById(200, function ($wallets) use (&$verifiedCount, &$verificationFailures): void {
                foreach ($wallets as $wallet) {
                    try {
                        $wallet->status = 1;
                        $wallet->save();
                        $verifiedCount++;
                    } catch (\Throwable $e) {
                        report($e);
                        Log::error('Scheduled POS transaction verification failed.', [
                            'wallet_id' => $wallet->id,
                            'message' => $e->getMessage(),
                        ]);
                        $verificationFailures[] = (string) $wallet->id;
                    }
                }
            });

        $dailyGroups = Wallet::query()
            ->whereNotNull('transaction_date')
            ->where('transaction_date', '<', $cutoff)
            ->where('status', 1)
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('payment_summaries')
                    ->whereColumn('payment_summaries.pos_id', 'wallets.pos_id')
                    ->whereRaw('DATE(payment_summaries.date) = DATE(wallets.transaction_date)')
                    ->where('payment_summaries.status', '!=', 'pending');
            })
            ->selectRaw('pos_id, DATE(transaction_date) as transaction_date')
            ->groupBy('pos_id')
            ->groupByRaw('DATE(transaction_date)')
            ->orderByRaw('DATE(transaction_date)')
            ->get()
            ->groupBy(fn ($wallet) => Carbon::parse($wallet->transaction_date)->toDateString());

        $generationFailures = [];
        $generator = app(PaymentSummaryGenerator::class);
        $submitter = app(PosPaymentAutoSubmitter::class);
        $submissionCount = 0;
        $submissionFailures = [];

        foreach ($dailyGroups as $date => $posGroups) {
            $readyPosIds = [];
            foreach ($posGroups as $group) {
                $posId = (int) $group->pos_id;
                try {
                    $result = $generator->generateWhenComplete($posId, $date);
                    if (in_array($result, ['created', 'repaired', 'existing'], true)) {
                        $readyPosIds[] = $posId;
                        continue;
                    }

                    $generationFailures[] = $posId . ' (' . $date . ')';
                    Log::error('Scheduled POS payment summary is not ready.', [
                        'pos_id' => $posId,
                        'date' => $date,
                        'result' => $result,
                    ]);
                } catch (\Throwable $e) {
                    report($e);
                    Log::error('Scheduled POS payment summary generation failed.', [
                        'pos_id' => $posId,
                        'date' => $date,
                        'message' => $e->getMessage(),
                    ]);
                    $generationFailures[] = $posId . ' (' . $date . ')';
                }
            }

            if (count($readyPosIds) !== $posGroups->count()) {
                continue;
            }

            foreach ($readyPosIds as $posId) {
                try {
                    if ($submitter->submitWhenReady($posId, $date) === 'submitted') {
                        $submissionCount++;
                    }
                } catch (\Throwable $e) {
                    report($e);
                    Log::error('Scheduled POS payment submission failed.', [
                        'pos_id' => $posId,
                        'date' => $date,
                        'message' => $e->getMessage(),
                    ]);
                    $submissionFailures[] = $posId . ' (' . $date . ')';
                }
            }
        }

        $this->info("Automatically verified {$verifiedCount} pending POS transactions.");
        $this->info("Automatically submitted {$submissionCount} POS payments.");
        if ($verificationFailures !== []) {
            $this->error('POS transactions could not be verified: ' . implode(', ', $verificationFailures));
        }
        if ($generationFailures !== []) {
            $this->error('Payment summaries could not be generated for: ' . implode(', ', $generationFailures));
        }
        if ($submissionFailures !== []) {
            $this->error('POS payments could not be submitted for: ' . implode(', ', $submissionFailures));
        }

        return $verificationFailures === [] && $generationFailures === [] && $submissionFailures === []
            ? self::SUCCESS
            : self::FAILURE;
    }
}
