<?php

namespace Tests\Feature;

use App\Http\Controllers\UserDashboardController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserDashboardWalletBalanceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('used_amount')->nullable();
            $table->string('reward_points')->nullable();
            $table->string('used_points')->nullable();
        });
    }

    public function test_wallet_balances_only_include_the_requested_users(): void
    {
        DB::table('user_wallets')->insert([
            ['user_id' => 1, 'wallet_amount' => '120', 'used_amount' => '20', 'reward_points' => '30', 'used_points' => '5'],
            ['user_id' => 2, 'wallet_amount' => '40', 'used_amount' => '5', 'reward_points' => '10', 'used_points' => '2'],
            ['user_id' => null, 'wallet_amount' => '999', 'used_amount' => '0', 'reward_points' => '999', 'used_points' => '0'],
        ]);

        $controller = new UserDashboardController();

        $this->assertEquals([
            'wallet_balance' => 100,
            'rewardBalance' => 25,
        ], $controller->get_total_wallet_amount(1));

        $this->assertEquals([
            'wallet_balance' => 135,
            'rewardBalance' => 33,
        ], $controller->get_total_wallet_amount(1, 2));
    }
}
