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
            'user_id' => (string) $user->user_id,
            'billing_amount' => (string) $wallet->billing_amount,
            'user_name' => (string) $user ? $user->name : "Not Available",
            'trans_id' => (string) $wallet->invoice,
            'date' => (string) now()->format('d-m-Y'),
            'billing_amount' => (string) $wallet->billing_amount,
            'pay_by' => strtoupper($wallet->pay_by),
            'paid_amount' => $wallet->pay_by == 'reward' ? $wallet->reward_amount : $wallet->amount_wallet
        ];

        $pos_params = [
            'pos_name' => (string) $pos ? $pos->name : 'NA',
            'user_id' => (string) $user->user_id,
            'billing_amount' => (string) $wallet->billing_amount,
            'user_name' => (string) $user ? $user->name : "Not Available",
            'trans_id' => (string) $wallet->invoice,
            'date' => (string) now()->format('d-m-Y'),
            'billing_amount' => (string) $wallet->billing_amount,
            'by_cash' => (string) $wallet->amount,
            'paid_amount' => $wallet->pay_by == 'reward' ? $wallet->reward_amount : $wallet->amount_wallet,
            'transaction_amount' => (string)$wallet->transaction_amount
        ];
        Log::info($pos->mobilenumber);
        $whatsapp  = new WhatsappMessageService();
        $msg_reslt = $whatsapp->user_transaction($wallet->user->mobilenumber, $params);
        $pos_reslt = $whatsapp->pos_transaction($pos->mobilenumber, $pos_params);
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
