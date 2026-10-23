<?php

namespace Tests\Feature;

use App\Http\Controllers\UserDashboardController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
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
            $table->string('user_id')->nullable();
            $table->integer('pos_id')->nullable();
            $table->string('trans_type')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('used_amount')->nullable();
            $table->string('reward_points')->nullable();
            $table->string('used_points')->nullable();
            $table->string('remaining_amount')->nullable();
            $table->string('remaining_points')->nullable();
        });

        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->integer('sponsor_id')->nullable();
        });

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
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

    public function test_scan_modal_renders_without_page_specific_balance_data(): void
    {
        $user = new User();
        $user->setAttribute('id', 1);
        $user->setAttribute('user_id', 'U-1');
        $user->setAttribute('mobilenumber', '0912345678');
        $user->setAttribute('is_prime_member', false);
        $this->actingAs($user);

        DB::table('user_wallets')->insert([
            'user_id' => 1,
            'wallet_amount' => '120',
            'used_amount' => '20',
            'reward_points' => '30',
            'used_points' => '5',
        ]);

        $html = view('frontend.dashboard.includes.scanmodal')->render();

        $this->assertStringContainsString('name="user_id" value="1"', $html);
        $this->assertStringContainsString('id="wallet_balance">100</span>', $html);
        $this->assertStringContainsString('id="reward_balance">25</span>', $html);
    }

    public function test_payback_history_shows_only_credits_within_the_selected_date_range(): void
    {
        $includedId = DB::table('user_wallets')->insertGetId([
            'user_id' => '1',
            'trans_type' => 'Credit',
            'transaction_date' => '2026-05-15',
            'wallet_amount' => '100',
            'used_amount' => '0',
            'reward_points' => '25',
            'used_points' => '0',
            'remaining_amount' => '100',
            'remaining_points' => '25',
        ]);
        DB::table('user_wallets')->insert([
            [
                'user_id' => '1',
                'trans_type' => 'debit',
                'transaction_date' => '2026-05-20',
                'wallet_amount' => '0',
                'used_amount' => '20',
                'reward_points' => '0',
                'used_points' => '0',
            ],
            [
                'user_id' => '1',
                'trans_type' => 'credit',
                'transaction_date' => '2026-06-01',
                'wallet_amount' => '50',
                'used_amount' => '0',
                'reward_points' => '0',
                'used_points' => '0',
            ],
            [
                'user_id' => '2',
                'trans_type' => 'credit',
                'transaction_date' => '2026-05-20',
                'wallet_amount' => '75',
                'used_amount' => '0',
                'reward_points' => '0',
                'used_points' => '0',
            ],
        ]);

        $user = new User();
        $user->setAttribute('id', 1);
        $user->setAttribute('user_id', 'U-1');
        $this->actingAs($user);

        $response = app(UserDashboardController::class)->wallet(Request::create('/user/my-pay-back', 'GET', [
            'from_date' => '2026-05-01',
            'to_date' => '2026-05-31',
        ]));
        $history = $response->getData()['userWallet'];

        $this->assertSame([$includedId, $includedId], $history->pluck('id')->all());
        $this->assertSame(['credit', 'credit'], array_map(
            fn ($type) => strtolower($type),
            $history->pluck('trans_type')->all()
        ));
    }
}
