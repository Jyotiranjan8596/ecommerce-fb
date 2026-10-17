<?php

namespace Tests\Feature;

use App\Imports\AdminWalletImport;
use App\Models\UserWallet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminPaybackReportTest extends TestCase
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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('name')->nullable();
        });

        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->string('month')->nullable();
            $table->string('used_amount')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('reward_points')->nullable();
            $table->string('used_points')->nullable();
            $table->string('remaining_amount')->nullable();
            $table->string('remaining_points')->nullable();
            $table->string('pay_by')->nullable();
            $table->string('trans_type')->nullable();
            $table->string('source')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->timestamps();
        });
    }

    public function test_report_includes_admin_uploads_only_and_uses_transaction_date_for_month(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);

        $importId = $this->createWalletRow($userId, [
            'month' => 'Wrong Month',
            'wallet_amount' => '125.50',
            'reward_points' => '10',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $legacyId = $this->createWalletRow($userId, [
            'month' => 'Oct-26',
            'wallet_amount' => '40',
            'reward_points' => '5',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-12',
            'source' => 'legacy_admin_import',
        ]);
        $this->createWalletRow($userId, [
            'month' => 'Oct-26',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => null,
        ]);
        $this->createWalletRow($userId, [
            'month' => 'Oct-26',
            'wallet_amount' => '75',
            'reward_points' => '0',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'wallet_id' => 99,
            'source' => null,
        ]);

        $result = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'month' => '10',
            'year' => '2026',
        ]));

        $this->assertSame([$legacyId, $importId], $result->getCollection()->pluck('id')->all());
        $this->assertSame('October,26', $result->first()->month);
    }

    public function test_admin_upload_uses_transaction_date_month_and_tags_imported_rows(): void
    {
        DB::table('users')->insert([
            'user_id' => 'U-1',
            'name' => 'Example',
        ]);

        $row = (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '2026-10-14',
            'month' => 'Wrong Month',
            'wallet_amount' => '125.50',
            'reward_point' => '10',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);

        $this->assertSame('2026-10-14', $row->transaction_date);
        $this->assertSame('Oct-26', $row->month);
        $this->assertSame('admin_import', $row->source);
    }

    public function test_admin_upload_parses_day_first_two_digit_year_dates(): void
    {
        DB::table('users')->insert([
            'user_id' => 'U-1',
            'name' => 'Example',
        ]);

        $row = (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '01-06-26',
            'wallet_amount' => '125.50',
            'reward_point' => '10',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);

        $this->assertSame('2026-06-01', $row->transaction_date);
        $this->assertSame('Jun-26', $row->month);
    }

    public function test_admin_upload_accepts_plural_reward_points_heading(): void
    {
        DB::table('users')->insert([
            'user_id' => 'U-1',
            'name' => 'Example',
        ]);

        $row = (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '2026-10-14',
            'wallet_amount' => '125.50',
            'reward_points' => '10',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);

        $this->assertSame('10', $row->reward_points);
    }

    public function test_admin_upload_reports_missing_reward_points_heading(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The CSV must include a REWARD POINT or REWARD POINTS column.');

        (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '2026-10-14',
            'wallet_amount' => '125.50',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);
    }

    public function test_export_uses_the_same_origin_date_and_filter_rules_as_the_report(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $matchingId = $this->createWalletRow($userId, [
            'month' => '26-Oct',
            'wallet_amount' => '80',
            'reward_points' => '15',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $this->createWalletRow($userId, [
            'month' => 'Oct-25',
            'wallet_amount' => '80',
            'reward_points' => '15',
            'trans_type' => 'credit',
            'transaction_date' => '2025-10-14',
            'source' => 'admin_import',
        ]);
        $legacyId = $this->createWalletRow($userId, [
            'month' => 'Oct-26',
            'wallet_amount' => '90',
            'reward_points' => '20',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => 'legacy_admin_import',
        ]);
        $this->createWalletRow($userId, [
            'month' => 'Oct-26',
            'wallet_amount' => '100',
            'reward_points' => '25',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => null,
        ]);

        $result = UserWallet::getAdminWalletExport(Request::create('/', 'POST', [
            'month' => '10',
            'year' => '2026',
            'trans_type' => 'credit',
            'pay_by' => 'wallet',
        ]));

        $this->assertSame([$legacyId, $matchingId], $result->pluck('id')->all());
        $this->assertSame('October,26', $result->first()->month);
    }

    public function test_wallet_and_reward_filters_use_uploaded_amounts_for_credit_and_debit_rows(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $walletCreditId = $this->createWalletRow($userId, [
            'wallet_amount' => '100.00',
            'reward_points' => '0.00',
            'used_points' => '50.00',
            'trans_type' => 'credit',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $rewardDebitId = $this->createWalletRow($userId, [
            'wallet_amount' => '0.00',
            'reward_points' => '35.00',
            'used_points' => null,
            'trans_type' => 'debit',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $this->createWalletRow($userId, [
            'wallet_amount' => '0.00',
            'reward_points' => '0.00',
            'used_points' => '20.00',
            'trans_type' => 'debit',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);

        $walletRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'pay_by' => 'wallet',
            'type' => 'credit',
        ]));
        $rewardRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'pay_by' => 'reward',
            'type' => ' Debit ',
        ]));

        $this->assertSame([$walletCreditId], $walletRows->getCollection()->pluck('id')->all());
        $this->assertSame([$rewardDebitId], $rewardRows->getCollection()->pluck('id')->all());
        $this->assertEquals(35, $rewardRows->first()->rounded_reward_point);
        $this->assertEquals(0, $rewardRows->first()->rounded_wallet_amount);
    }

    private function createWalletRow(int $userId, array $values): int
    {
        return DB::table('user_wallets')->insertGetId(array_merge([
            'user_id' => $userId,
            'wallet_id' => null,
            'month' => null,
            'used_amount' => null,
            'wallet_amount' => null,
            'reward_points' => null,
            'used_points' => null,
            'remaining_amount' => null,
            'remaining_points' => null,
            'pay_by' => null,
            'trans_type' => null,
            'source' => null,
            'transaction_date' => null,
            'mobilenumber' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $values));
    }
}
