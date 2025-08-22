<?php
namespace App\Jobs;

use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RewardDistributionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::with('sponcer')
            ->whereDate('created_at', '>=', Carbon::parse('2025-07-31 22:31:14'))
            ->get();

        foreach ($users as $user) {
            // if sponcer is a relationship that returns a collection (hasMany)
            $sponsor = $user->sponcer->first() ?? null;

            if ($sponsor) {
                Log::info("Sponsor ID: " . $sponsor->id);

                UserWallet::create([
                    'user_id'          => $sponsor->sponsor_id ?? null,
                    'month'            => '25-Aug',
                    'reward_points'    => 50,
                    'trans_type'       => 'credit',
                    'transaction_date' => $sponsor->created_at ?? now(),
                ]);
            } else {
                Log::warning("No sponsor found for user ID: {$user->id}");
            }
        }
    }

}
