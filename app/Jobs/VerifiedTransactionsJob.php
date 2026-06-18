<?php

namespace App\Jobs;

use App\Exports\PaymentSummaryExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class VerifiedTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $wallet_data;
    public function __construct($wallet_data)
    {
        $this->wallet_data = $wallet_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileName = 'paymentsummary/payment_summary_' . time() . '.xlsx';

        Excel::store(new PaymentSummaryExport($this->wallet_data),$fileName);

        $filePath = storage_path('app/' . $fileName);

        Mail::raw('Please find attached payment summary.', function ($msg) use ($filePath) {

            $msg->to('satshreemarketing@gmail.com')
                ->subject('Payment Summary Report')
                ->attach($filePath);
        });
    }
}
