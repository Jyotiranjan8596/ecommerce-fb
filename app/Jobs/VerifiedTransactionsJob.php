<?php

namespace App\Jobs;

use App\Exports\PaymentSummaryExport;
use App\Exports\PaymentSummaryPosExport;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class VerifiedTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $wallet_data;
    protected $date;
    public function __construct($wallet_data, $date)
    {
        $this->wallet_data = $wallet_data;
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileName = 'paymentsummary/payment_summary_' . time() . '.xlsx';

        Excel::store(new PaymentSummaryExport($this->wallet_data), $fileName);

        $filePath = storage_path('app/' . $fileName);

        Mail::raw('Please find attached payment summary.', function ($msg) use ($filePath) {

            $msg->to('satshreemarketing@gmail.com')
                ->subject('Payment Summary Report')
                ->attach($filePath);
        });

        $posData = Wallet::with('getPos')
            ->whereDate('transaction_date', $this->date)
            ->select('pos_id')
            ->groupBy('pos_id')
            ->get()
            ->map(function ($wallet) {
                return [
                    'pos_id' => $wallet->pos_id,
                    'email' => $wallet->getPos->email ?? null
                ];
            });
        Log::info($posData);

        foreach ($posData as $pos) {
            $pos_trans = Wallet::export_user_transaction_of_pos('2026-05-20', $pos['pos_id']);
            $pos_fileName = 'paymentsummary/UserTransactions_' . time() . '.xlsx';
            Excel::store(new PaymentSummaryPosExport($pos_trans), $pos_fileName);
            $pos_filePath = storage_path('app/' . $pos_fileName);
            $trans_date = $this->date;
            Mail::raw('Please find attached payment summary.', function ($msg) use ($pos_filePath, $pos, $trans_date) {
                $msg->to('sahoorinku63@gmail.com')
                    ->subject('Transactions for ' . $trans_date)
                    ->attach($pos_filePath);
            });
        }
    }
}
