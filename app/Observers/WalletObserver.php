<?php

namespace App\Observers;

use App\Models\Wallet;
use App\Services\AiSensyService;
use App\Services\WhatsappMessageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletObserver
{
    /**
     * Handle the Wallet "created" event.
     */
    public function created(Wallet $wallet): void
    {
        Log::info("Working Observer");
        $user   = Auth::user();
        $pos = $wallet->getPos;
        // dd($wallet->getPos);
        $params = [
            'pos_name' => (string) $pos ? $pos->name : 'NA',
            'user_id' => (string) $user->id,
            'billing_amount' => (string) $wallet->billing_amount,
            'user_name' => (string) $user ? $user->name : "Not Available",
            'trans_id' => (string) $wallet->invoice,
            'date' => (string) now()->format('d-m-Y'),
            'billing_amount' => (string) $wallet->billing_amount,
        ];
        Log::info($pos->mobilenumber);
        $whatsapp  = new WhatsappMessageService();
        $msg_reslt = $whatsapp->user_transaction($wallet->user->mobilenumber, $params);
        $pos_reslt = $whatsapp->pos_transaction($pos->mobilenumber, $params);
        Log::info('Message result from observer', [$msg_reslt]);
        Log::info('Message result from observer', [$pos_reslt]);
    }

    /**
     * Handle the Wallet "updated" event.
     */
    public function updated(Wallet $wallet): void {}

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
