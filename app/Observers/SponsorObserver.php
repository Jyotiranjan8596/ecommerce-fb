<?php

namespace App\Observers;

use App\Models\Sponsor;
use App\Models\User;
use App\Models\UserWallet;
use App\Services\WhatsappMessageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SponsorObserver
{
    /**
     * Handle the Sponsor "created" event.
     */
    public function created(Sponsor $sponsor): void
    {
        $month            = Carbon::now()->format('M-d');   // e.g., Apr-19
        $transaction_date = Carbon::now()->format('Y-m-d'); // e.g., 2024-12-31
        $res              = UserWallet::create([
            'user_id'          => $sponsor->sponsor_id, // Use the id from the Users table
            'month'            => $month,
            'wallet_amount'    => 0,
            'reward_points'    => 50,
            'trans_type'       => 'credit',
            'transaction_date' => $transaction_date,
        ]);
        $id = $sponsor->sponsor_id;
        $user_mob = User::where('id', $id)->value('mobilenumber');
        $whatsapp  = new WhatsappMessageService();
        $msg_reslt = $whatsapp->sponsor_message($user_mob);
        Log::info('Sponsor message result', [$msg_reslt]);
    }

    /**
     * Handle the Sponsor "updated" event.
     */
    public function updated(Sponsor $sponsor): void
    {
        //
    }

    /**
     * Handle the Sponsor "deleted" event.
     */
    public function deleted(Sponsor $sponsor): void
    {
        //
    }

    /**
     * Handle the Sponsor "restored" event.
     */
    public function restored(Sponsor $sponsor): void
    {
        //
    }

    /**
     * Handle the Sponsor "force deleted" event.
     */
    public function forceDeleted(Sponsor $sponsor): void
    {
        //
    }
}
