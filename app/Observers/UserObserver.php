<?php
namespace App\Observers;

use App\Models\User;
use App\Models\UserWallet;
use App\Services\AiSensyService;
use Carbon\Carbon;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $month            = Carbon::now()->format('M-d');   // e.g., Apr-19
        $transaction_date = Carbon::now()->format('Y-m-d'); // e.g., 2024-12-31
        $res              = UserWallet::create([
            'user_id'          => $user->id, // Use the id from the Users table
            'month'            => $month,
            'wallet_amount'    => 0,
            'reward_points'    => 50,
            'trans_type'       => 'credit',
            'mobilenumber'     => $user->mobilenumber,
            'transaction_date' => $transaction_date,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
