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
            ->whereDate('created_at', '>=', Carbon::parse('2025-08-02 09:37:41'))
            ->get();
        foreach ($users as $user) {
            $sponser = $user->sponcer->first();
            Log::info($sponser->id);
            UserWallet::create([
                'user_id'          => $sponser->sponsor_id,
                'month'            => '25-Aug',
                'reward_points'    => 50,
                'trans_type'       => 'credit',
                'transaction_date' => $sponser->created_at,
            ]);
        }
        // Log::info($users->toArray());
    }
}
