<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\PaymentSummary;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReceiptJournalTest extends TestCase
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
            $table->string('user_id')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('role')->default('3');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_summury_id')->nullable();
            $table->date('transaction_date');
            $table->string('voucher_number');
            $table->string('reference_number')->nullable();
            $table->text('account_details')->nullable();
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

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('mobilenumber')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->date('date');
            $table->unsignedInteger('total_transaction')->default(0);
            $table->decimal('total_billing_amount', 15, 2)->default(0);
            $table->decimal('by_cash', 15, 2)->default(0);
            $table->decimal('by_wallet', 15, 2)->default(0);
            $table->decimal('by_reward', 15, 2)->default(0);
            $table->decimal('pos_credit', 15, 2)->default(0);
            $table->decimal('pos_debit', 15, 2)->default(0);
            $table->decimal('admin_credit', 15, 2)->default(0);
            $table->decimal('admin_debit', 15, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->string('status')->default('pending');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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

    public function test_receipt_is_saved_as_funds_received_from_selected_pos(): void
    {
        $adminId = DB::table('users')->insertGetId([
            'user_id' => 'admin-100',
            'name' => 'Admin',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posId = DB::table('users')->insertGetId([
            'user_id' => 'pos-200',
            'name' => 'Sample POS',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $admin = User::findOrFail($adminId);
        $pos = User::findOrFail($posId);
        Auth::login($admin);

        DB::table('payments')->insert([
            'payment_summury_id' => null,
            'transaction_date' => now()->subDay()->toDateString(),
            'voucher_number' => 'PREVIOUS-PAYOUT',
            'reference_number' => 'PREVIOUS-REF',
            'account_details' => null,
            'pay_by' => '1',
            'due' => 0,
            'amount' => 40,
            'to' => $pos->user_id,
            'from' => $admin->user_id,
            'created_by' => $admin->user_id,
            'updated_by' => $admin->user_id,
            'remark' => 'Previous POS payout',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $this->assertTrue(Payment::store_receipt(new Request([
            'amount' => 125.50,
            'pay_by' => '1',
            'pay_to' => $pos->user_id,
            'reference_number' => 'UPI-REF-123',
            'remark' => 'Settlement receipt',
        ])));

        $receipt = Payment::where('reference_number', 'UPI-REF-123')->firstOrFail();
        $this->assertNull($receipt->payment_summury_id);
        $this->assertSame($admin->user_id, $receipt->to);
        $this->assertSame($pos->user_id, $receipt->from);

        $journal = Payment::getReceipt(new Request([
            'from_date' => now()->toDateString(),
            'to_date' => now()->toDateString(),
        ]));
        $this->assertCount(1, $journal['rows']);
        $journalRow = $journal['rows']->first();
        $this->assertSame('Sample POS', $journalRow['receive_from']);
        $this->assertSame('UPI', $journalRow['receive_by']);
        $this->assertSame('UPI-REF-123', $journalRow['ref_number']);
        $this->assertSame('Bank (UPI)', $journalRow['cash_bank_account']);
        $this->assertSame('125.50', $journalRow['amount']);
        $this->assertSame('85.50', $journalRow['debit_balance']);
        $this->assertSame('Dr', $journalRow['debit_balance_type']);
        $this->assertSame('85.50', $journalRow['credit_balance']);
        $this->assertSame('Cr', $journalRow['credit_balance_type']);
        $this->assertSame('125.50', $journal['total_debit']);
        $this->assertSame($journal['total_debit'], $journal['total_credit']);

        $posLedger = Payment::getLedgerData(new Request(), $pos->user_id);
        $posReceiptEntry = $posLedger->firstWhere('voucher_number', $receipt->voucher_number);
        $this->assertEquals(125.50, (float) $posReceiptEntry->credit);
        $this->assertSame('85.50', $posReceiptEntry->balance);
        $this->assertSame('Cr', $posReceiptEntry->balance_type);
    }

    public function test_admin_can_pay_a_settlement_in_parts_and_overpayment_is_rejected(): void
    {
        Http::fake(['graph.facebook.com/*' => Http::response([], 200)]);
        $adminId = DB::table('users')->insertGetId([
            'user_id' => '666666',
            'name' => 'Admin',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posUserId = DB::table('users')->insertGetId([
            'user_id' => 'pos-300',
            'name' => 'Payment POS',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'pos-300',
            'name' => 'Payment POS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => now()->toDateString(),
            'admin_debit' => 100,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Auth::login(User::findOrFail($adminId));
        $posUser = User::findOrFail($posUserId);

        $makePayment = static fn (float $amount) => Payment::create_payment(new Request([
            'pos_id' => $posId,
            'summary_date' => now()->toDateString(),
            'paying_amount' => $amount,
            'pay_by' => '1',
            'reference_number' => 'REF-' . $amount,
            'is_pos' => '0',
        ]));

        $this->assertTrue($makePayment(40));
        $this->assertSame('pending', PaymentSummary::findOrFail($summaryId)->status);
        $this->assertEquals(60, Payment::latest('id')->value('due'));

        $this->assertTrue($makePayment(60));
        $this->assertSame('approved', PaymentSummary::findOrFail($summaryId)->status);
        $this->assertFalse($makePayment(1));
        $this->assertSame(2, Payment::where('payment_summury_id', $summaryId)
            ->where('from', '666666')
            ->where('to', $posUser->user_id)
            ->count());
    }

    public function test_pos_partial_payments_remain_pending_until_the_admin_verifies_the_full_amount(): void
    {
        Http::fake(['graph.facebook.com/*' => Http::response([], 200)]);
        $adminId = DB::table('users')->insertGetId([
            'user_id' => '666666',
            'name' => 'Admin',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insertGetId([
            'user_id' => 'pos-400',
            'name' => 'Receipt POS',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'pos-400',
            'name' => 'Receipt POS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => now()->toDateString(),
            'admin_credit' => 100,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('payments')->insert([
            'payment_summury_id' => $summaryId,
            'transaction_date' => now()->toDateString(),
            'voucher_number' => 'ADMIN-LEDGER-VOUCHER',
            'reference_number' => 'ADMIN-LEDGER-REF',
            'pay_by' => null,
            'amount' => 100,
            'to' => '666666',
            'from' => 'pos-400',
            'created_by' => '666666',
            'updated_by' => '666666',
            'remark' => 'Auto Generated',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Auth::login(User::findOrFail($adminId));

        $makePayment = static fn (float $amount) => Payment::create_payment(new Request([
            'pos_id' => $posId,
            'summary_date' => now()->toDateString(),
            'paying_amount' => $amount,
            'pay_by' => '1',
            'reference_number' => 'POS-REF-' . $amount,
            'is_pos' => '1',
        ]));

        $this->assertTrue($makePayment(35));
        $this->assertSame('pending', PaymentSummary::findOrFail($summaryId)->status);
        $this->assertEquals(65, Payment::latest('id')->value('due'));
        $this->assertFalse(PaymentSummary::update_settlement(new Request([
            'id' => $summaryId,
            'reference_number' => 'verified-too-early',
        ])));
        $this->assertTrue($makePayment(65));
        $this->assertSame('pending', PaymentSummary::findOrFail($summaryId)->status);
        $this->assertSame(
            ['ADMIN-LEDGER-REF'],
            Payment::where('payment_summury_id', $summaryId)
                ->where(function ($query) {
                    $query->whereNull('remark')
                        ->orWhere('remark', '!=', 'Auto Generated');
                })
                ->pluck('reference_number')
                ->unique()
                ->values()
                ->all()
        );
        Payment::where('payment_summury_id', $summaryId)
            ->where('remark', 'Auto Generated')
            ->delete();
        $this->assertTrue(PaymentSummary::update_settlement(new Request([
            'id' => $summaryId,
            'reference_number' => 'verified',
        ])));
        $this->assertSame('approved', PaymentSummary::findOrFail($summaryId)->status);

        $journal = Payment::getReceipt(new Request());
        $this->assertCount(2, $journal['rows']);
        $this->assertSame('100.00', $journal['total_debit']);
        $this->assertSame($journal['total_debit'], $journal['total_credit']);
        $latestPayment = Payment::where('payment_summury_id', $summaryId)
            ->where('reference_number', 'ADMIN-LEDGER-REF')
            ->latest('id')
            ->firstOrFail();
        $latestReceipt = $journal['rows']->firstWhere('voucher', $latestPayment->voucher_number);
        $this->assertSame('100.00', $latestReceipt['debit_balance']);
        $this->assertSame('100.00', $latestReceipt['credit_balance']);

        $posLedger = Payment::getLedgerData(new Request(), 'pos-400');
        $this->assertEquals(100, (float) $posLedger->sum('credit'));
        $posLatestEntry = $posLedger->firstWhere('voucher_number', $latestPayment->voucher_number);
        $this->assertSame('100.00', $posLatestEntry->balance);
        $this->assertSame('Cr', $posLatestEntry->balance_type);
    }

    public function test_pos_payment_recreates_missing_generated_entry_and_uses_its_reference(): void
    {
        Http::fake(['graph.facebook.com/*' => Http::response([], 200)]);
        $adminId = DB::table('users')->insertGetId([
            'user_id' => '666666',
            'name' => 'Admin',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posUserId = DB::table('users')->insertGetId([
            'user_id' => 'pos-repair',
            'name' => 'Repair POS',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'pos-repair',
            'name' => 'Repair POS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $summaryId = DB::table('payment_summaries')->insertGetId([
            'pos_id' => $posId,
            'date' => now()->toDateString(),
            'admin_credit' => 50,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Auth::login(User::findOrFail($posUserId));

        $this->assertTrue(Payment::create_payment(new Request([
            'pos_id' => $posId,
            'summary_date' => now()->toDateString(),
            'paying_amount' => 50,
            'pay_by' => '1',
            'is_pos' => '1',
        ])));

        $generatedEntry = Payment::where('payment_summury_id', $summaryId)
            ->where('remark', 'Auto Generated')
            ->firstOrFail();
        $submittedPayment = Payment::where('payment_summury_id', $summaryId)
            ->where(function ($query) {
                $query->whereNull('remark')
                    ->orWhere('remark', '!=', 'Auto Generated');
            })
            ->firstOrFail();
        $this->assertNotEmpty($generatedEntry->reference_number);
        $this->assertSame($generatedEntry->reference_number, $submittedPayment->reference_number);
    }

    public function test_payment_summary_creates_a_balanced_auto_entry_and_reports_duplicates(): void
    {
        DB::table('users')->insert([
            'user_id' => '666666',
            'name' => 'Admin',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Auth::login(User::where('user_id', '666666')->firstOrFail());
        DB::table('users')->insert([
            'user_id' => 'pos-500',
            'name' => 'Summary POS',
            'password' => 'password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $posId = DB::table('pos_systems')->insertGetId([
            'user_id' => 'pos-500',
            'name' => 'Summary POS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $summaryData = [
            'date' => now()->toDateString(),
            'pos_id' => $posId,
            'total_transactions' => 2,
            'billing_amount' => 500,
            'cash_upi' => 500,
            'wallet' => 0,
            'reward' => 0,
            'credit_amount' => 25,
            'debit_amount' => 0,
        ];

        $this->assertSame(2, PaymentSummary::store_summary($summaryData));
        $this->assertSame(1, PaymentSummary::store_summary($summaryData));

        $summary = PaymentSummary::firstOrFail();
        $autoEntry = Payment::where('payment_summury_id', $summary->id)->firstOrFail();
        $this->assertSame('pos-500', $autoEntry->to);
        $this->assertSame('666666', $autoEntry->from);
        $this->assertSame('Auto Generated', $autoEntry->remark);
        $this->assertSame(1, Payment::where('payment_summury_id', $summary->id)->count());
        $this->assertCount(0, Payment::getReceipt(new Request())['rows']);
    }
}
