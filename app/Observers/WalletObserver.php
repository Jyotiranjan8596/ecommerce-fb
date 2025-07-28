<?php
namespace App\Observers;

use App\Models\Wallet;
use App\Services\AiSensyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletObserver
{
    /**
     * Handle the Wallet "created" event.
     */
    public function created(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "updated" event.
     */
    public function updated(Wallet $wallet): void
    {
        Log::info("Working Observer");
        $user   = Auth::user();
        $params = [
            (string) $wallet->user->name,
            (string) $wallet->billing_amount,
            (string) $user ? $user->name : "Not Available",
            (string) $wallet->invoice,
            (string) $wallet->amount,
            (string) $wallet->amount_wallet,
            (string) $wallet->reward_amount,
        ];

        $whatsapp  = new AiSensyService();
        $msg_reslt = $whatsapp->sendTransactionMessage($wallet->user->mobilenumber, $params);
        Log::info('Message result from observer', [$msg_reslt]);
    }

    /**
     * Handle the Wallet "deleted" event.
     */
    public function deleted(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "restored" event.
     */
    public function restored(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "force deleted" event.
     */
    public function forceDeleted(Wallet $wallet): void
    {
        //
    }
}
