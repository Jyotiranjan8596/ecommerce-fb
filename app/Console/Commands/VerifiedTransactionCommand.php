<?php

namespace App\Console\Commands;

use App\Jobs\VerifiedTransactionsJob;
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
        $yesterday = Carbon::now();
        $yesterday = $yesterday->toDateString();

        $wallet_data = PosModel::getWalletDetails($yesterday);
        
        if ($wallet_data) {
            VerifiedTransactionsJob::dispatch($wallet_data,$yesterday)->onQueue('verified_transaction');
        }
    }
}
