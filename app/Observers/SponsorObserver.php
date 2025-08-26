<?php
namespace App\Observers;

use App\Models\Sponsor;
use App\Models\UserWallet;
use Carbon\Carbon;

class SponsorObserver
{
    /**
     * Handle the Sponsor "created" event.
     */
    public function created(Sponsor $sponsor): void
    {
        $month            = Carbon::now()->format('d-M');   // e.g., Apr-19
        $transaction_date = Carbon::now()->format('Y-m-d'); // e.g., 2024-12-31
        $res              = UserWallet::create([
            'user_id'          => $sponsor->sponsor_id, // Use the id from the Users table
            'month'            => $month,
            'wallet_amount'    => 0,
            'reward_points'    => 50,
            'trans_type'       => 'credit',
            'transaction_date' => $transaction_date,
        ]);
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
