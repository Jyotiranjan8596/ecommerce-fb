<?php

namespace App\Providers;

use App\Models\CustomerOrder;
use App\Models\PaymentSummary;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\Wallet;
use App\Observers\SponsorObserver;
use App\Observers\TransactionStatusObserver;
use App\Observers\UserObserver;
use App\Observers\WalletObserver;
use App\Services\UserWalletBalanceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Wallet::observe(WalletObserver::class);
        Wallet::observe(TransactionStatusObserver::class);
        CustomerOrder::observe(TransactionStatusObserver::class);
        PaymentSummary::observe(TransactionStatusObserver::class);
        User::observe(UserObserver::class);
        Sponsor::observe(SponsorObserver::class);       
        View::composer('frontend.dashboard.includes.scanmodal', function (ViewInstance $view): void {
            $user = auth()->user();
            abort_unless($user, 401);

            $balances = app(UserWalletBalanceService::class)->getBalancesForUser((int) $user->id);

            $view->with([
                'user_profile' => $user,
                'walletBalance' => round($balances['wallet_balance']),
                'rewardBalance' => round($balances['rewardBalance']),
            ]);
        });
        // DB::listen(function ($query) {
        //     Log::info('SQL: ' . $query->sql);
        //     // Optional: Log bindings and time
        //     Log::info('Bindings: ' . json_encode($query->bindings));
        //     Log::info('Time: ' . $query->time . ' ms');
        // });
    }
}
