<?php

namespace Tests\Feature;

use App\Http\Controllers\DsrSummaryController;
use App\Http\Controllers\PaymentSummaryController;
use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Services\PaymentSummaryGenerator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PaymentSummaryGenerationTest extends TestCase
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

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('mobilenumber')->nullable();
            $table->decimal('transaction_charge', 8, 2)->default(0);
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->dateTime('transaction_date');
            $table->unsignedTinyInteger('status')->nullable();
            $table->decimal('billing_amount', 12, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('amount_wallet', 12, 2)->default(0);
            $table->decimal('reward_amount', 12, 2)->default(0);
            $table->string('pay_by')->nullable();
        });

        Schema::create('payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->date('date');
            $table->integer('total_transaction')->default(0);
            $table->decimal('total_billing_amount', 12, 2)->default(0);
            $table->decimal('by_cash', 12, 2)->default(0);
            $table->decimal('by_wallet', 12, 2)->default(0);
            $table->decimal('by_reward', 12, 2)->default(0);
            $table->decimal('pos_credit', 12, 2)->default(0);
            $table->decimal('pos_debit', 12, 2)->default(0);
            $table->decimal('admin_credit', 12, 2)->default(0);
            $table->decimal('admin_debit', 12, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_summury_id');
            $table->date('transaction_date');
            $table->string('voucher_number');
            $table->string('reference_number');
            $table->text('account_details')->nullable();
            $table->text('gst_no')->nullable();
            $table->string('pay_by')->nullable();
            $table->decimal('due', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('to');
            $table->string('from');
            $table->string('created_by');
            $table->string('updated_by');
            $table->text('remark')->nullable();
            $table->timestamps();
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
    }

    protected function tearDown(): void
    {
        \Carbon\Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_generates_payment_summaries_only_for_pos_with_all_transactions_verified(): void
    {
        $date = '2026-10-10';
        $posIds = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-A',
            'name' => 'Alpha POS',
            'transaction_charge' => 2,
        ]);
        $secondPosId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-B',
            'name' => 'Beta POS',
            'transaction_charge' => 2,
        ]);

        DB::table('wallets')->insert([
            [
                'pos_id' => $posIds,
                'transaction_date' => $date . ' 09:00:00',
                'status' => 1,
                'billing_amount' => 100,
                'amount' => 90,
                'amount_wallet' => 10,
                'reward_amount' => 0,
                'pay_by' => 'wallet',
            ],
            [
                'pos_id' => $posIds,
                'transaction_date' => $date . ' 10:00:00',
                'status' => 1,
                'billing_amount' => 50,
                'amount' => 50,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $posIds,
                'transaction_date' => $date . ' 11:00:00',
                'status' => null,
                'billing_amount' => 1000,
                'amount' => 1000,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $secondPosId,
                'transaction_date' => $date . ' 12:00:00',
                'status' => 1,
                'billing_amount' => 200,
                'amount' => 200,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
        ]);

        $response = (new PaymentSummaryController())->generatePosPaymentSummaries(new Request([
            'transaction_date' => $date,
        ]));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertSame(1, $payload['created_count']);
        $this->assertCount(1, $payload['summaries']);
        $this->assertSame($secondPosId, $payload['summaries'][0]['pos_id']);
        $this->assertSame(1, $payload['summaries'][0]['total_transactions']);
        $this->assertEquals(200, $payload['summaries'][0]['total_billing_amount']);
        $this->assertEquals(0, $payload['summaries'][0]['pos_credit']);
        $this->assertEquals(4, $payload['summaries'][0]['pos_debit']);
        $this->assertSame(1, Payment::count());
        $this->assertSame(1, Payment::where('remark', 'Auto Generated')->count());

        $history = (new DsrSummaryController())->adminIndex(new Request([
            'date' => $date,
        ]));
        $this->assertSame(
            [true, false],
            $history->getData()['summaries']->getCollection()
                ->map(fn ($summary) => (bool) $summary->has_generated_payment)
                ->all()
        );

        $retry = (new PaymentSummaryController())->generatePosPaymentSummaries(new Request([
            'transaction_date' => $date,
        ]))->getData(true);
        $this->assertSame(0, $retry['created_count']);
        $this->assertSame(1, $retry['existing_count']);
        $this->assertSame(1, Payment::count());
    }

    public function test_automatically_generates_when_the_pos_date_has_no_pending_transactions(): void
    {
        $date = '2026-10-10';
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-A',
            'name' => 'Alpha POS',
            'transaction_charge' => 2,
        ]);
        DB::table('wallets')->insert([
            [
                'pos_id' => $posId,
                'transaction_date' => '2026-10-11 10:00:00',
                'status' => 1,
                'billing_amount' => 75,
                'amount' => 75,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $posId,
                'transaction_date' => $date . ' 09:00:00',
                'status' => 1,
                'billing_amount' => 100,
                'amount' => 100,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $posId,
                'transaction_date' => $date . ' 10:00:00',
                'status' => null,
                'billing_amount' => 50,
                'amount' => 50,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
        ]);

        $generator = new PaymentSummaryGenerator();
        $this->assertSame('pending', $generator->generateWhenComplete($posId, $date));
        $this->assertSame(0, PaymentSummary::count());

        DB::table('wallets')
            ->where('pos_id', $posId)
            ->whereNull('status')
            ->update(['status' => 1]);

        $this->assertSame('created', $generator->generateWhenComplete($posId, $date));
        $this->assertSame('existing', $generator->generateWhenComplete($posId, $date));
        $this->assertSame(1, PaymentSummary::count());
        $this->assertSame(2, PaymentSummary::first()->total_transaction);
        $this->assertEquals(150, PaymentSummary::first()->total_billing_amount);
        $this->assertSame(1, Payment::where('remark', 'Auto Generated')->count());
    }

    public function test_scheduled_verification_generates_summary_after_verifying_the_last_pending_transaction(): void
    {
        $date = '2026-10-13';
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-A',
            'name' => 'Alpha POS',
            'transaction_charge' => 2,
        ]);
        DB::table('wallets')->insert([
            [
                'pos_id' => $posId,
                'transaction_date' => $date . ' 09:00:00',
                'status' => 1,
                'billing_amount' => 100,
                'amount' => 100,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $posId,
                'transaction_date' => $date . ' 10:00:00',
                'status' => null,
                'billing_amount' => 50,
                'amount' => 50,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
        ]);
        \Carbon\Carbon::setTestNow('2026-10-14 12:00:00');

        $this->artisan('app:verify-pos-dsr-transactions')->assertSuccessful();

        $summary = PaymentSummary::firstOrFail();
        $this->assertSame(2, $summary->total_transaction);
        $this->assertEquals(150, $summary->total_billing_amount);
        $this->assertSame(1, PaymentSummary::count());
        $this->assertSame(1, Payment::where('remark', 'Auto Generated')->count());
        $this->assertSame(1, Payment::where('remark', 'Auto Submitted')->count());
        $submittedPayment = Payment::where('remark', 'Auto Submitted')->firstOrFail();
        $this->assertEquals(3, (float) $submittedPayment->amount);
        $this->assertSame('POS-A', $submittedPayment->from);
        $this->assertSame('666666', $submittedPayment->to);
        $this->assertSame('pending', $summary->fresh()->status);

        $this->artisan('app:verify-pos-dsr-transactions')->assertSuccessful();

        $this->assertSame(1, Payment::where('remark', 'Auto Submitted')->count());
    }

    public function test_scheduled_verification_catches_up_summary_and_submission_for_all_overdue_dates(): void
    {
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-A',
            'name' => 'Alpha POS',
            'transaction_charge' => 2,
        ]);
        DB::table('wallets')->insert([
            [
                'pos_id' => $posId,
                'transaction_date' => '2026-10-13 09:00:00',
                'status' => 1,
                'billing_amount' => 100,
                'amount' => 100,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
            [
                'pos_id' => $posId,
                'transaction_date' => '2026-10-14 09:00:00',
                'status' => 1,
                'billing_amount' => 50,
                'amount' => 50,
                'amount_wallet' => 0,
                'reward_amount' => 0,
                'pay_by' => 'cash',
            ],
        ]);
        \Carbon\Carbon::setTestNow('2026-10-15 12:00:00');

        $this->artisan('app:verify-pos-dsr-transactions')->assertSuccessful();

        $this->assertSame(
            ['2026-10-13', '2026-10-14'],
            PaymentSummary::query()->orderBy('date')->pluck('date')->map(
                fn ($date) => \Carbon\Carbon::parse($date)->toDateString()
            )->all()
        );
        $this->assertSame(2, Payment::where('remark', 'Auto Generated')->count());
        $this->assertSame(2, Payment::where('remark', 'Auto Submitted')->count());
    }

    public function test_admin_payment_summary_page_lists_saved_summaries_and_filters_by_date_and_pos(): void
    {
        $firstPosId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-A',
            'name' => 'Alpha POS',
            'transaction_charge' => 2,
        ]);
        $secondPosId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-B',
            'name' => 'Beta POS',
            'transaction_charge' => 2,
        ]);

        DB::table('payment_summaries')->insert([
            [
                'pos_id' => $firstPosId,
                'date' => '2026-10-10',
                'total_transaction' => 3,
                'total_billing_amount' => 250,
                'by_cash' => 200,
                'by_wallet' => 30,
                'by_reward' => 20,
                'pos_credit' => 0,
                'pos_debit' => 5,
                'admin_credit' => 5,
                'admin_debit' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => $secondPosId,
                'date' => '2026-10-11',
                'total_transaction' => 1,
                'total_billing_amount' => 100,
                'by_cash' => 100,
                'by_wallet' => 0,
                'by_reward' => 0,
                'pos_credit' => 0,
                'pos_debit' => 0,
                'admin_credit' => 0,
                'admin_debit' => 0,
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $view = (new PaymentSummaryController())->summaryIndex(new Request([
            'date' => '2026-10-10',
            'pos_id' => $firstPosId,
        ]));
        $summaries = $view->getData()['summaries'];

        $this->assertCount(1, $summaries->getCollection());
        $this->assertSame('Alpha POS', $summaries->first()->pos_system->name);
        $this->assertSame('2026-10-10', $view->getData()['selectedDate']);
        $this->assertSame($firstPosId, (int) $view->getData()['selectedPosId']);
    }

    public function test_payment_summary_status_matches_dsr_generated_entry_status_not_settlement_status(): void
    {
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-STATUS',
            'name' => 'Status POS',
            'transaction_charge' => 2,
        ]);
        $confirmedSummaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => '2026-10-10',
            'admin_credit' => 3,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pendingSummaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => '2026-10-09',
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $confirmedSummaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'STATUS-AUTO',
            'reference_number' => 'STATUS-REF',
            'amount' => 1,
            'to' => 'POS-STATUS',
            'from' => '666666',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'Auto Generated',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $confirmedSummaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'STATUS-SUBMITTED',
            'reference_number' => 'STATUS-REF',
            'amount' => 3,
            'to' => '666666',
            'from' => 'POS-STATUS',
            'created_by' => 'POS-STATUS',
            'updated_by' => 'POS-STATUS',
            'remark' => 'Auto Submitted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $view = (new PaymentSummaryController())->summaryIndex(new Request());
        $summaries = $view->getData()['summaries']->getCollection()->keyBy('id');

        $this->assertTrue((bool) $summaries->get($confirmedSummaryId)->has_generated_payment);
        $this->assertTrue((bool) $summaries->get($confirmedSummaryId)->has_auto_submitted_payment);
        $this->assertEquals(3, (float) $summaries->get($confirmedSummaryId)->auto_submitted_amount);
        $this->assertEquals(3, (float) $summaries->get($confirmedSummaryId)->pos_payment_amount);
        $this->assertFalse((bool) $summaries->get($pendingSummaryId)->has_generated_payment);
        $this->assertFalse((bool) $summaries->get($pendingSummaryId)->has_auto_submitted_payment);
        $this->assertEquals(0, (float) $summaries->get($pendingSummaryId)->auto_submitted_amount);
        $this->assertSame('pending', $summaries->get($confirmedSummaryId)->status);
        $this->assertSame('approved', $summaries->get($pendingSummaryId)->status);
    }

    public function test_settling_payable_summary_requires_recorded_admin_payment(): void
    {
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-SETTLE',
            'name' => 'Settlement POS',
            'transaction_charge' => 2,
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => '2026-10-10',
            'total_transaction' => 1,
            'total_billing_amount' => 100,
            'by_cash' => 80,
            'by_wallet' => 10,
            'by_reward' => 10,
            'pos_credit' => 15,
            'pos_debit' => 0,
            'admin_credit' => 0,
            'admin_debit' => 15,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'AUTO-SETTLE',
            'reference_number' => 'SUMMARY-SETTLE-REF',
            'amount' => 15,
            'to' => 'POS-SETTLE',
            'from' => '666666',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'Auto Generated',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('admin_payment_missing', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('admin_payment_missing', PaymentSummary::update_settlement(new Request([
            'id' => $summaryId,
            'reference_number' => 'ADMIN-PAYOUT-REF',
        ])));
        $this->assertSame('pending', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));
        $this->assertSame(0, DB::table('payments')->where('remark', 'DSR Summary Settled')->count());

        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'LEGACY-SYNTHETIC',
            'reference_number' => 'SUMMARY-SETTLE-REF',
            'amount' => 15,
            'to' => 'POS-SETTLE',
            'from' => '666666',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'DSR Summary Settled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payment_summaries')->where('id', $summaryId)->update(['status' => 'approved']);
        $this->assertSame('admin_payment_missing', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('admin_payment_missing', PaymentSummary::update_settlement(new Request([
            'id' => $summaryId,
            'reference_number' => 'ADMIN-PAYOUT-REF',
        ])));
        DB::table('payment_summaries')->where('id', $summaryId)->update(['status' => 'pending']);

        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'ADMIN-PAYOUT',
            'reference_number' => 'ADMIN-PAYOUT-REF',
            'amount' => 10,
            'to' => 'POS-SETTLE',
            'from' => '666666',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'Admin payout',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('admin_payment_incomplete', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('admin_payment_incomplete', PaymentSummary::update_settlement(new Request([
            'id' => $summaryId,
            'reference_number' => 'ADMIN-PAYOUT-REF',
        ])));
        $this->assertSame('pending', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));

        DB::table('payments')->where('voucher_number', 'ADMIN-PAYOUT')->update(['amount' => 15]);
        $this->assertSame('settled', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('approved', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));

        $ledgerPayment = DB::table('payments')
            ->where('payment_summury_id', $summaryId)
            ->where('voucher_number', 'ADMIN-PAYOUT')
            ->first();
        $this->assertNotNull($ledgerPayment);
        $this->assertSame('POS-SETTLE', $ledgerPayment->to);
        $this->assertSame('666666', $ledgerPayment->from);
        $this->assertEquals(15, (float) $ledgerPayment->amount);
        $this->assertSame('ADMIN-PAYOUT-REF', DB::table('payment_summaries')->where('id', $summaryId)->value('reference_number'));
        $this->assertSame('already_settled', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame(1, DB::table('payments')->where('remark', 'DSR Summary Settled')->count());
    }

    public function test_receivable_summary_cannot_be_settled_until_pos_payment_is_recorded(): void
    {
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-RECEIVE',
            'name' => 'Receipt POS',
            'transaction_charge' => 2,
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => '2026-10-10',
            'total_transaction' => 1,
            'total_billing_amount' => 100,
            'by_cash' => 80,
            'by_wallet' => 10,
            'by_reward' => 10,
            'pos_credit' => 0,
            'pos_debit' => 15,
            'admin_credit' => 15,
            'admin_debit' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => '2026-10-10',
            'voucher_number' => 'AUTO-RECEIVE',
            'reference_number' => 'SUMMARY-RECEIVE-REF',
            'amount' => 15,
            'to' => '666666',
            'from' => 'POS-RECEIVE',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'Auto Generated',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('payment_incomplete', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('pending', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));
        $this->assertSame(1, DB::table('payments')->where('remark', 'Auto Generated')->count());
    }

    public function test_settlement_recreates_missing_generated_entry_when_pos_is_linked(): void
    {
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-REPAIR',
            'name' => 'Repair POS',
            'transaction_charge' => 2,
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => '2026-10-10',
            'total_transaction' => 1,
            'total_billing_amount' => 100,
            'by_cash' => 80,
            'by_wallet' => 10,
            'by_reward' => 10,
            'pos_credit' => 0,
            'pos_debit' => 15,
            'admin_credit' => 15,
            'admin_debit' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => now()->toDateString(),
            'voucher_number' => 'POS-REPAIR-RECEIPT',
            'reference_number' => 'POS-REPAIR-REF',
            'amount' => 15,
            'to' => '666666',
            'from' => 'POS-REPAIR',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'POS repayment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('settled', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame(1, DB::table('payments')
            ->where('payment_summury_id', $summaryId)
            ->where('remark', 'Auto Generated')
            ->count());
        $this->assertSame('approved', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));
    }

    public function test_summary_without_pos_link_cannot_be_settled(): void
    {
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => 9999,
            'date' => '2026-10-10',
            'total_transaction' => 1,
            'total_billing_amount' => 100,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('missing_pos', PaymentSummary::settleGeneratedSummary($summaryId, '666666'));
        $this->assertSame('pending', DB::table('payment_summaries')->where('id', $summaryId)->value('status'));
        $this->assertSame(0, DB::table('payments')->where('payment_summury_id', $summaryId)->count());
    }

    public function test_generation_repairs_existing_summary_missing_auto_generated_ledger_entry(): void
    {
        $date = '2026-06-03';
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'POS-REPAIR',
            'name' => 'Repair POS',
            'transaction_charge' => 25,
        ]);
        DB::table('wallets')->insert([
            'pos_id' => $posId,
            'transaction_date' => $date . ' 12:00:00',
            'status' => 1,
            'billing_amount' => 24,
            'amount' => 20,
            'amount_wallet' => 0,
            'reward_amount' => 0,
            'pay_by' => 'cash',
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => $date,
            'total_transaction' => 1,
            'total_billing_amount' => 24,
            'by_cash' => 20,
            'by_wallet' => 0,
            'by_reward' => 0,
            'pos_credit' => 0,
            'pos_debit' => 4,
            'admin_credit' => 4,
            'admin_debit' => 0,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = (new PaymentSummaryController())->generatePosPaymentSummaries(new Request([
            'transaction_date' => $date,
        ]));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertSame(0, $payload['created_count']);
        $this->assertSame(1, $payload['repaired_count']);
        $this->assertSame(0, $payload['existing_count']);
        $this->assertSame(1, DB::table('payment_summaries')->where('id', $summaryId)->count());
        $this->assertSame(1, Payment::where('payment_summury_id', $summaryId)
            ->where('remark', 'Auto Generated')->count());
        $this->assertSame('POS-REPAIR', Payment::where('payment_summury_id', $summaryId)->value('from'));

        $retry = (new PaymentSummaryController())->generatePosPaymentSummaries(new Request([
            'transaction_date' => $date,
        ]))->getData(true);
        $this->assertSame(0, $retry['created_count']);
        $this->assertSame(0, $retry['repaired_count']);
        $this->assertSame(1, $retry['existing_count']);
        $this->assertSame(1, Payment::where('payment_summury_id', $summaryId)
            ->where('remark', 'Auto Generated')->count());
    }
}
