<?php

namespace Tests\Feature;

use App\Imports\AdminWalletImport;
use App\Http\Controllers\WalletController;
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
            $table->string('mobilenumber')->nullable();
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

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('transaction_date')->nullable();
        });

        Schema::create('transaction_status_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->string('subject_type');
        });
    }

    public function test_report_includes_admin_uploads_only_and_uses_uploaded_month(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);

        $importId = $this->createWalletRow($userId, [
            'month' => 'Aug-26',
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
            'month' => '8',
            'year' => '2026',
        ]));

        $this->assertSame([$importId], $result->getCollection()->pluck('id')->all());
        $importedRow = $result->getCollection()->firstWhere('id', $importId);
        $this->assertSame('Aug-26', $importedRow->month);
        $this->assertSame('August,26', $importedRow->display_month);
    }

    public function test_report_includes_rows_with_nonzero_wallet_or_reward_amount_only(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $walletOnlyId = $this->createWalletRow($userId, [
            'wallet_amount' => '25',
            'reward_points' => null,
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $rewardOnlyId = $this->createWalletRow($userId, [
            'wallet_amount' => null,
            'reward_points' => '15',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $negativeAmountId = $this->createWalletRow($userId, [
            'wallet_amount' => '-5',
            'reward_points' => '0',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);
        $this->createWalletRow($userId, [
            'wallet_amount' => '0',
            'reward_points' => '0.00',
            'transaction_date' => '2026-10-14',
            'source' => 'admin_import',
        ]);

        $result = UserWallet::getAdminWallet(Request::create('/', 'POST'));

        $this->assertEqualsCanonicalizing(
            [$walletOnlyId, $rewardOnlyId, $negativeAmountId],
            $result->getCollection()->pluck('id')->all()
        );
    }

    public function test_report_includes_legacy_uploaded_reward_rows_using_the_uploaded_month(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $februaryUploadId = $this->createWalletRow($userId, [
            'month' => '26-Feb',
            'wallet_amount' => '0',
            'reward_points' => '55',
            'transaction_date' => '2026-03-01',
        ]);
        $marchUploadId = $this->createWalletRow($userId, [
            'month' => '26-Mar',
            'wallet_amount' => '0',
            'reward_points' => '70',
            'transaction_date' => '2026-04-01',
        ]);
        $uploadedFiftyPointRowId = $this->createWalletRow($userId, [
            'month' => '26-Feb',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'transaction_date' => '2026-03-01',
        ]);
        $this->createWalletRow($userId, [
            'month' => '25-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2025-08-23',
        ]);
        $this->createWalletRow($userId, [
            'month' => '31-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2025-08-31',
        ]);
        $this->createWalletRow($userId, [
            'month' => 'Feb-05',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'transaction_date' => '2026-02-05',
        ]);

        $februaryRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'month' => '2',
            'year' => '2026',
        ]))->getCollection();
        $marchRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'month' => '3',
            'year' => '2026',
        ]))->getCollection();

        $this->assertSame([$uploadedFiftyPointRowId, $februaryUploadId], $februaryRows->pluck('id')->all());
        $this->assertSame('February,26', $februaryRows->first()->display_month);
        $this->assertSame([$marchUploadId], $marchRows->pluck('id')->all());
        $this->assertSame('March,26', $marchRows->first()->display_month);
    }

    public function test_report_uses_transaction_year_when_stored_month_is_day_and_month(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $dayMonthId = $this->createWalletRow($userId, [
            'month' => '31-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'transaction_date' => '2025-08-31',
            'source' => 'admin_import',
        ]);
        $yearMonthId = $this->createWalletRow($userId, [
            'month' => '26-Feb',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'transaction_date' => '2026-03-01',
            'source' => 'admin_import',
        ]);
        $referralRewardId = $this->createWalletRow($userId, [
            'month' => '31-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2025-08-31',
        ]);

        $rows = UserWallet::getAdminWallet(Request::create('/', 'POST'))->getCollection();

        $this->assertSame('August,25', $rows->firstWhere('id', $dayMonthId)->display_month);
        $this->assertSame('February,26', $rows->firstWhere('id', $yearMonthId)->display_month);
        $this->assertNull($rows->firstWhere('id', $referralRewardId));

        $augustRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'month' => '8',
            'year' => '2025',
        ]))->getCollection();
        $wrongYearRows = UserWallet::getAdminWallet(Request::create('/', 'POST', [
            'month' => '8',
            'year' => '2031',
        ]))->getCollection();

        $this->assertSame([$dayMonthId], $augustRows->pluck('id')->all());
        $this->assertSame([], $wrongYearRows->pluck('id')->all());
    }

    public function test_admin_wallet_history_queries_separate_payback_and_referral_records(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $otherUserId = DB::table('users')->insertGetId(['user_id' => 'U-2', 'name' => 'Another']);
        $paybackId = $this->createWalletRow($userId, [
            'month' => 'May-26',
            'wallet_amount' => '100',
            'reward_points' => '50',
            'transaction_date' => '2026-05-01',
            'source' => 'admin_import',
        ]);
        $this->createWalletRow($otherUserId, [
            'month' => 'May-26',
            'wallet_amount' => '200',
            'reward_points' => '0',
            'transaction_date' => '2026-05-01',
            'source' => 'admin_import',
        ]);
        $referralId = $this->createWalletRow($userId, [
            'month' => '31-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2025-08-31',
        ]);
        $this->createWalletRow($otherUserId, [
            'month' => '31-Aug',
            'wallet_amount' => '0',
            'reward_points' => '50',
            'trans_type' => 'credit',
            'transaction_date' => '2025-08-31',
        ]);

        $paybackRows = UserWallet::getAdminPaybackHistory(Request::create('/', 'POST', [
            'transaction_date' => '2026-05-01',
            'history_user_id' => $userId,
        ]))->getCollection();
        $referralRows = UserWallet::getAdminReferralHistory(Request::create('/', 'POST', [
            'transaction_date' => '2025-08-31',
            'history_user_id' => $userId,
        ]))->getCollection();

        $this->assertSame([$paybackId], $paybackRows->pluck('id')->all());
        $this->assertSame('payback', $paybackRows->first()->history_type);
        $this->assertSame('May,26', $paybackRows->first()->display_month);
        $this->assertSame([$referralId], $referralRows->pluck('id')->all());
        $this->assertSame('referral', $referralRows->first()->history_type);
    }

    public function test_admin_upload_uses_uploaded_month_and_tags_imported_rows(): void
    {
        DB::table('users')->insert([
            'user_id' => 'U-1',
            'name' => 'Example',
        ]);

        $row = (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '2026-10-14',
            'month' => 'Aug-26',
            'wallet_amount' => '125.50',
            'reward_point' => '10',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);

        $this->assertSame('2026-10-14', $row->transaction_date);
        $this->assertSame('Aug-26', $row->month);
        $this->assertSame('admin_import', $row->source);
    }

    public function test_admin_upload_formats_excel_serial_month_values(): void
    {
        DB::table('users')->insert([
            'user_id' => 'U-1',
            'name' => 'Example',
        ]);

        $row = (new AdminWalletImport())->model([
            'user_id' => 'U-1',
            'transaction_date' => '2026-10-14',
            'month' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new \DateTime('2026-08-01')),
            'wallet_amount' => '125.50',
            'reward_point' => '10',
            'payment_mode' => 'credit',
            'mobile_number' => '5551234',
        ]);

        $this->assertSame('Aug-26', $row->month);
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
        $exportedRow = $result->firstWhere('id', $matchingId);
        $this->assertSame('26-Oct', $exportedRow->month);
        $this->assertSame('October,26', $exportedRow->display_month);
    }

    public function test_payback_history_filters_by_selected_month_and_year(): void
    {
        $userId = DB::table('users')->insertGetId(['user_id' => 'U-1', 'name' => 'Example']);
        $matchingId = $this->createWalletRow($userId, [
            'month' => 'Oct-26',
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
        $this->createWalletRow($userId, [
            'month' => 'Sep-26',
            'wallet_amount' => '80',
            'reward_points' => '15',
            'trans_type' => 'credit',
            'transaction_date' => '2026-09-14',
            'source' => 'admin_import',
        ]);

        $result = UserWallet::getAdminPaybackHistory(Request::create('/', 'GET', [
            'history_user_id' => $userId,
            'transaction_month' => '2026-10',
        ]));

        $this->assertSame([$matchingId], $result->getCollection()->pluck('id')->all());
    }

    public function test_transaction_history_filters_by_selected_month_and_year(): void
    {
        $userId = DB::table('users')->insertGetId([
            'user_id' => 'U-1',
            'name' => 'Example',
            'mobilenumber' => '5551234',
        ]);
        $matchingId = DB::table('wallets')->insertGetId([
            'user_id' => $userId,
            'transaction_date' => '2026-10-14',
        ]);
        DB::table('wallets')->insert([
            ['user_id' => $userId, 'transaction_date' => '2025-10-14'],
            ['user_id' => $userId, 'transaction_date' => '2026-09-14'],
        ]);

        $response = app(WalletController::class)->walletManage(Request::create('/admin/walletmanage', 'GET', [
            'user_id' => 'U-1',
            'transaction_month' => '2026-10',
        ]));

        $this->assertSame(
            [$matchingId],
            $response->getData()['walletList']->pluck('id')->all()
        );
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
