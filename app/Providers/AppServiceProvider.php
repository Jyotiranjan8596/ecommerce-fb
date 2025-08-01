<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Wallet;
use App\Observers\UserObserver;
use App\Observers\WalletObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        User::observe(UserObserver::class);        
        // DB::listen(function ($query) {
        //     Log::info('SQL: ' . $query->sql);
        //     // Optional: Log bindings and time
        //     Log::info('Bindings: ' . json_encode($query->bindings));
        //     Log::info('Time: ' . $query->time . ' ms');
        // });
    }
}
