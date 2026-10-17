<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserAllTransactionController;
use App\Http\Controllers\Admin\DsrController;
use App\Models\User;
use App\Models\Wallet;
use App\Models\PosModel;
use App\Exports\MsrTransactionExport;
use App\Exports\DsrTransactionExport;
use App\Exports\MsrExport;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TodaysSalesTest extends TestCase
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
            $table->string('name');
            $table->string('mobilenumber')->nullable();
            $table->unsignedBigInteger('sponsor_id')->nullable();
        });

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('pos_code')->nullable();
            $table->string('user_id')->nullable();
            $table->string('name');
            $table->string('mobilenumber')->nullable();
            $table->decimal('transaction_charge', 8, 2)->default(0);
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('tran_type')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->string('invoice')->nullable();
            $table->decimal('billing_amount', 12, 2)->default(0);
            $table->decimal('amount_wallet', 12, 2)->default(0);
            $table->decimal('reward_amount', 12, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
        });

        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->decimal('remaining_amount', 12, 2)->nullable();
            $table->decimal('remaining_points', 12, 2)->nullable();
        });

        Schema::create('transaction_status_changes', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');
            $table->string('event_type', 24);
            $table->text('old_status')->nullable();
            $table->text('new_status')->nullable();
            $table->json('changed_fields')->nullable();
            $table->string('actor_type', 24);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_name')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'id' => 1,
            'user_id' => 'CUSTOMER-001',
            'name' => 'Customer',
            'mobilenumber' => '9000000000',
        ]);
        DB::table('users')->insert([
            ['id' => 2, 'user_id' => 'POS-USER-A', 'name' => 'Alpha POS User', 'mobilenumber' => '9111111111'],
            ['id' => 3, 'user_id' => 'POS-USER-B', 'name' => 'Beta POS User', 'mobilenumber' => '9222222222'],
        ]);
        DB::table('pos_systems')->insert([
            ['id' => 1, 'pos_code' => 'POS-A1', 'user_id' => 'POS-USER-A', 'name' => 'Alpha Store', 'mobilenumber' => '9111111111', 'transaction_charge' => 2],
            ['id' => 2, 'pos_code' => 'POS-B2', 'user_id' => 'POS-USER-B', 'name' => 'Beta Store', 'mobilenumber' => '9222222222', 'transaction_charge' => 2],
        ]);
        DB::table('wallets')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'pos_id' => 1,
                'transaction_date' => '2026-10-11 10:00:00',
                'status' => 1,
                'invoice' => 'ALPHA-VERIFIED',
                'billing_amount' => 100,
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'pos_id' => 1,
                'transaction_date' => '2026-10-11 11:00:00',
                'status' => null,
                'invoice' => 'ALPHA-PENDING',
                'billing_amount' => 50,
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'pos_id' => 2,
                'transaction_date' => '2026-10-11 12:00:00',
                'status' => 1,
                'invoice' => 'BETA',
                'billing_amount' => 75,
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'pos_id' => 1,
                'transaction_date' => '2026-10-10 12:00:00',
                'status' => 1,
                'invoice' => 'PREVIOUS-DAY',
                'billing_amount' => 200,
            ],
        ]);
    }

    public function test_sales_filter_by_customer_user_id_and_return_verification_status(): void
    {
        $request = new Request([
            'transaction_date' => '2026-10-11',
            'user_search' => 'CUSTOMER-001',
        ]);

        $sales = Wallet::getAllTransactions($request);

        $this->assertSame(
            ['BETA', 'ALPHA-PENDING', 'ALPHA-VERIFIED'],
            $sales->getCollection()->pluck('invoice')->all()
        );
        $this->assertSame(
            ['Verified', 'Pending', 'Verified'],
            $sales->getCollection()->pluck('status_label')->all()
        );
    }

    public function test_export_can_filter_by_pos_user_mobile_number(): void
    {
        $request = new Request([
            'transaction_date' => '2026-10-11',
            'user_search' => '9111111111',
        ]);

        $sales = Wallet::export_user_transaction($request);

        $this->assertSame(['ALPHA-PENDING', 'ALPHA-VERIFIED'], $sales->pluck('invoice')->all());
    }

    public function test_todays_sales_export_filename_uses_matched_user_id_and_selected_date(): void
    {
        Excel::fake();

        (new UserAllTransactionController())->userAllTransactionsExport(new Request([
            'transaction_date' => '2026-10-11',
            'user_search' => '9111111111',
        ]));

        Excel::assertDownloaded("Today's_Sales_pos_user_a_11,10,2026.xlsx");
    }

    public function test_todays_sales_export_filename_uses_all_users_when_no_user_is_searched(): void
    {
        Excel::fake();

        (new UserAllTransactionController())->userAllTransactionsExport(new Request([
            'transaction_date' => '2026-10-11',
        ]));

        Excel::assertDownloaded("Today's_Sales_All_Users_11,10,2026.xlsx");
    }

    public function test_sales_search_matches_pos_user_id_and_customer_mobile(): void
    {
        $posUserSales = Wallet::getAllTransactions(new Request([
            'transaction_date' => '2026-10-11',
            'user_search' => 'POS-USER-B',
        ]));
        $customerMobileSales = Wallet::getAllTransactions(new Request([
            'transaction_date' => '2026-10-11',
            'user_search' => '9000000000',
        ]));

        $this->assertSame(['BETA'], $posUserSales->getCollection()->pluck('invoice')->all());
        $this->assertSame(
            ['BETA', 'ALPHA-PENDING', 'ALPHA-VERIFIED'],
            $customerMobileSales->getCollection()->pluck('invoice')->all()
        );
    }

    public function test_dsr_applies_pos_search_and_date_range_together(): void
    {
        Auth::login(User::findOrFail(1));

        $view = (new DsrController())->dsr(new Request([
            'start_date' => '2026-10-11',
            'end_date' => '2026-10-11',
            'search' => '9111111111',
        ]));
        $reportRows = $view->getData()['wallets']->getCollection();

        $this->assertCount(1, $reportRows);
        $this->assertSame(1, (int) $reportRows->first()->pos_id);
        $this->assertSame(2, (int) $reportRows->first()->total_transactions);
        $this->assertEquals(150, (float) $view->getData()['totalBillingAmount']);
    }

    public function test_dsr_details_export_contains_only_the_selected_pos_and_date(): void
    {
        Excel::fake();
        $encryptedPosId = encrypt(1);

        (new DsrController())->exportTransactionDetails(
            new Request(['transaction_date' => '2026-10-11']),
            $encryptedPosId
        );

        Excel::assertDownloaded('DSR_pos_user_a_11,10,2026.xlsx', function (DsrTransactionExport $export) {
            $rows = $export->collection();

            $this->assertCount(2, $rows);
            $this->assertSame('ALPHA-PENDING', $rows[0][1]);
            $this->assertSame('ALPHA-VERIFIED', $rows[1][1]);

            return true;
        });
    }

    public function test_main_dsr_export_contains_all_transactions_in_the_filtered_date_range(): void
    {
        Excel::fake();

        (new DsrController())->export(new Request([
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-11',
            'search' => '9111111111',
        ]));

        Excel::assertDownloaded('DSR_pos_user_a_10,10,2026_to_11,10,2026.xlsx', function (DsrTransactionExport $export) {
            $rows = $export->collection();

            $this->assertCount(3, $rows);
            $this->assertSame('ALPHA-PENDING', $rows[0][1]);
            $this->assertSame('ALPHA-VERIFIED', $rows[1][1]);
            $this->assertSame('PREVIOUS-DAY', $rows[2][1]);

            return true;
        });
    }

    public function test_msr_details_export_contains_all_transactions_for_the_selected_pos_and_month(): void
    {
        Excel::fake();
        $pos = PosModel::findOrFail(1);

        (new DsrController())->exportNewMsrDetails(
            new Request(['month' => '2026-10']),
            $pos
        );

        Excel::assertDownloaded('MSR_pos_user_a_10_2026.xlsx', function (MsrTransactionExport $export) {
            $rows = $export->collection();

            $this->assertCount(3, $rows);
            $this->assertSame('ALPHA-PENDING', $rows[0][1]);
            $this->assertSame('ALPHA-VERIFIED', $rows[1][1]);
            $this->assertSame('PREVIOUS-DAY', $rows[2][1]);

            return true;
        });
    }

    public function test_payback_run_export_uses_month_only_and_matches_the_csv_format(): void
    {
        Excel::fake();
        DB::table('wallets')->whereIn('id', [1, 2])->update([
            'mobilenumber' => '9000000000',
            'tran_type' => 'credit',
        ]);
        DB::table('wallets')->where('id', 1)->update([
            'reward_amount' => 5,
            'amount_wallet' => 10,
        ]);
        DB::table('wallets')->where('id', 2)->update([
            'reward_amount' => 2,
            'amount_wallet' => 10,
        ]);

        (new DsrController())->exportMsr(new Request([
            'month' => '2026-10',
            'search' => 1,
            'filter' => '9000000000',
        ]));

        Excel::assertDownloaded('Payback_Run_Data_October2026.csv', function (MsrExport $export) {
            $this->assertSame([
                'MONTH',
                'MOBILE NUMBER',
                'USER ID',
                'SPONSOR ID',
                'TOTAL BILLING AMOUNT',
                'Reward Point',
                'Wallet Amount',
                'Transaction Date',
                'Payment Mode',
                'SPONSOR EXPENDITURE',
            ], $export->headings());

            $rows = $export->collection();
            $this->assertCount(2, $rows);
            $customerRow = $rows->first(fn (array $row) => $row[1] === '9000000000');
            $this->assertSame([
                '26-Oct',
                '9000000000',
                'CUSTOMER-001',
                'N/A',
                150,
                7,
                20,
                '10-11-26',
                'credit',
                0,
            ], $customerRow);
            $this->assertSame(425, array_sum(array_column($rows->all(), 4)));

            return true;
        });
    }

    public function test_transaction_edit_updates_only_the_three_allowed_amount_fields(): void
    {
        $wallet = Wallet::findOrFail(1);
        $wallet->amount = 25;
        $wallet->save();

        $request = Request::create('/', 'PATCH', [
            'billing_amount' => 125.50,
            'amount_wallet' => 40,
            'reward_amount' => 10.25,
        ]);

        $response = (new UserAllTransactionController())->updateTransaction($request, $wallet);
        $wallet->refresh();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertEquals(125.50, $wallet->billing_amount);
        $this->assertEquals(40, $wallet->amount_wallet);
        $this->assertEquals(10.25, $wallet->reward_amount);
        $this->assertEquals(25, $wallet->amount);
    }

    public function test_transaction_delete_removes_the_transaction_and_linked_user_wallet_record(): void
    {
        $wallet = Wallet::findOrFail(1);
        DB::table('user_wallets')->insert([
            'wallet_id' => $wallet->id,
            'remaining_amount' => 50,
            'remaining_points' => 10,
        ]);

        $response = (new UserAllTransactionController())->deleteTransaction($wallet);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertDatabaseMissing('wallets', ['id' => 1]);
        $this->assertDatabaseMissing('user_wallets', ['wallet_id' => 1]);
    }
}
