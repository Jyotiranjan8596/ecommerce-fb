<?php

namespace App\Console\Commands;

use App\Jobs\VerifiedTransactionsJob;
use App\Models\PaymentSummary;
use App\Models\PosModel;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerifiedTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verified-transaction-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $wallet_data = PosModel::getWalletDetails($yesterday);
        if (!$wallet_data || $wallet_data->isEmpty()) {
            Log::info('Store Summary', [
                'message' => 'No wallet data found',
                'date' => $yesterday
            ]);

            // VerifiedTransactionsJob::dispatch(null, null)->onQueue('verified_transaction');
            return;
        }

        $res = PaymentSummary::store_summary($wallet_data[0]);
        if ($res == 1) {
            Log::info('Store Summary', ['message' => 'already created']);
            return;
        } elseif ($res == 2) {
            Log::info('Store Summary', ['message' => 'Summuary Stored']);
            VerifiedTransactionsJob::dispatch($wallet_data, $yesterday)->onQueue('verified_transaction');
        } elseif ($res == false) {
            Log::info('Store Summary', ['message' => 'Something went wrong']);
            return;
        }
    }
}
