<?php

namespace Tests\Feature;

use App\Http\Controllers\DsrSummaryController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DsrSummaryHistoryTest extends TestCase
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
            $table->string('status')->default('pending');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_summury_id')->nullable();
            $table->string('remark')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('to')->nullable();
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

        DB::table('pos_systems')->insert([
            ['id' => 1, 'user_id' => 'POS-A', 'name' => 'Alpha Store', 'mobilenumber' => '9111111111', 'transaction_charge' => 2],
            ['id' => 2, 'user_id' => 'POS-B', 'name' => 'Beta Store', 'mobilenumber' => '9222222222', 'transaction_charge' => 2],
        ]);

        DB::table('payment_summaries')->insert([
            ['id' => 1, 'pos_id' => 1, 'date' => '2026-10-10', 'total_transaction' => 2, 'total_billing_amount' => 150],
            ['id' => 2, 'pos_id' => 2, 'date' => '2026-10-10', 'total_transaction' => 1, 'total_billing_amount' => 200],
            ['id' => 3, 'pos_id' => 1, 'date' => '2026-10-11', 'total_transaction' => 3, 'total_billing_amount' => 300],
        ]);
        DB::table('payment_summaries')->where('id', 1)->update(['admin_credit' => 2]);
        DB::table('payments')->insert([
            ['payment_summury_id' => 1, 'remark' => 'Auto Generated', 'amount' => 0, 'to' => null],
            ['payment_summury_id' => 1, 'remark' => 'Auto Submitted', 'amount' => 2, 'to' => '666666'],
        ]);
        DB::table('wallets')->insert([
            ['pos_id' => 1, 'transaction_date' => '2026-10-10 09:00:00', 'status' => 1, 'billing_amount' => 100, 'amount' => 90, 'amount_wallet' => 10, 'reward_amount' => 0, 'pay_by' => 'wallet'],
            ['pos_id' => 1, 'transaction_date' => '2026-10-10 10:00:00', 'status' => 0, 'billing_amount' => 50, 'amount' => 50, 'amount_wallet' => 0, 'reward_amount' => 0, 'pay_by' => 'cash'],
            ['pos_id' => 2, 'transaction_date' => '2026-10-10 11:00:00', 'status' => 1, 'billing_amount' => 200, 'amount' => 200, 'amount_wallet' => 0, 'reward_amount' => 0, 'pay_by' => 'cash'],
            ['pos_id' => 1, 'transaction_date' => '2026-10-11 12:00:00', 'status' => 1, 'billing_amount' => 300, 'amount' => 300, 'amount_wallet' => 0, 'reward_amount' => 0, 'pay_by' => 'cash'],
            ['pos_id' => 2, 'transaction_date' => '2026-10-12 13:00:00', 'status' => null, 'billing_amount' => 500, 'amount' => 500, 'amount_wallet' => 0, 'reward_amount' => 0, 'pay_by' => 'cash'],
        ]);
    }

    public function test_admin_date_and_pos_filters_apply_together(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
            'pos_search' => 'POS-A',
        ]));

        $this->assertSame(
            [1],
            $view->getData()['summaries']->getCollection()->pluck('pos_id')->all()
        );
        $this->assertSame('2026-10-10', $view->getData()['selectedDate']);
        $this->assertSame('POS-A', $view->getData()['selectedPosSearch']);
        $summary = $view->getData()['summaries']->first();
        $this->assertTrue($summary->has_generated_payment);
        $this->assertSame(1, (int) $summary->total_transaction);
        $this->assertEquals(100, $summary->total_billing_amount);
    }

    public function test_admin_history_status_is_confirmed_only_when_generated_ledger_entry_exists(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
        ]));

        $statuses = $view->getData()['summaries']->getCollection()
            ->keyBy('pos_id')
            ->map(fn ($summary) => (bool) $summary->has_generated_payment)
            ->all();

        $this->assertSame([2 => false, 1 => true], $statuses);
    }

    public function test_admin_can_search_summaries_by_pos_mobile_number_with_date_filter(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
            'pos_search' => '9222221111',
        ]));

        $this->assertCount(0, $view->getData()['summaries']->getCollection());

        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
            'pos_search' => '9222222222',
        ]));

        $this->assertSame(
            [2],
            $view->getData()['summaries']->getCollection()->pluck('pos_id')->all()
        );
    }

    public function test_pos_history_only_includes_the_signed_in_pos_and_selected_date(): void
    {
        $user = new User();
        $user->user_id = 'POS-A';
        $this->actingAs($user);

        $view = (new DsrSummaryController())->posIndex(new Request([
            'date' => '2026-10-10',
        ]));

        $this->assertSame(
            [1],
            $view->getData()['summaries']->getCollection()->pluck('pos_id')->all()
        );
        $this->assertFalse($view->getData()['isAdmin']);
    }

    public function test_history_includes_verified_sales_dates_without_generated_payment_summaries(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request());

        $this->assertSame(
            ['2026-10-12', '2026-10-11', '2026-10-10', '2026-10-10'],
            $view->getData()['summaries']->getCollection()->pluck('date')->all()
        );
        $this->assertSame(
            [2, 1, 2, 1],
            $view->getData()['summaries']->getCollection()->pluck('pos_id')->all()
        );
    }

    public function test_pos_history_shows_pending_verification_and_verified_sales_without_saved_summaries(): void
    {
        $user = new User();
        $user->user_id = 'POS-B';
        $this->actingAs($user);

        $view = (new DsrSummaryController())->posIndex(new Request());

        $this->assertSame(
            ['2026-10-12', '2026-10-10'],
            $view->getData()['summaries']->getCollection()->pluck('date')->all()
        );
        $this->assertSame(
            [0, 200],
            $view->getData()['summaries']->getCollection()
                ->pluck('total_billing_amount')
                ->map(fn ($amount) => (int) $amount)
                ->all()
        );
        $pendingSummary = $view->getData()['summaries']->first();
        $this->assertSame(0, (int) $pendingSummary->total_transaction);
        $this->assertSame(1, (int) $pendingSummary->pending_transaction_count);
    }

    public function test_admin_summary_totals_only_include_verified_transactions(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
            'pos_search' => 'POS-A',
        ]));

        $summary = $view->getData()['summaries']->first();

        $this->assertSame(1, (int) $summary->total_transaction);
        $this->assertSame(2, (int) $summary->all_transaction_count);
        $this->assertSame(1, (int) $summary->pending_transaction_count);
        $this->assertEquals(100, $summary->total_billing_amount);
        $this->assertEquals(90, $summary->by_cash);
    }

    public function test_history_exposes_generated_summary_and_auto_submitted_payment_status(): void
    {
        $view = (new DsrSummaryController())->adminIndex(new Request([
            'date' => '2026-10-10',
            'pos_search' => 'POS-A',
        ]));
        $summary = $view->getData()['summaries']->first();

        $this->assertTrue((bool) $summary->has_generated_payment);
        $this->assertEquals(2, (float) $summary->auto_submitted_amount);
        $this->assertEquals(2, (float) $summary->pos_payment_amount);
        $this->assertEquals(2, (float) $summary->admin_credit);
    }
}
