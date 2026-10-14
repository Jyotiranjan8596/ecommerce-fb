<?php

namespace Tests\Feature;

use App\Exports\AccountLedgerExport;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccountLedgerTest extends TestCase
{
    protected string $adminUserId;
    protected string $posUserId;

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

        $this->adminUserId = 'admin-100';
        $this->posUserId = 'pos-200';
        foreach ([
            [$this->adminUserId, 'Admin'],
            [$this->posUserId, 'Sample POS'],
        ] as [$userId, $name]) {
            DB::table('users')->insert([
                'user_id' => $userId,
                'name' => $name,
                'password' => 'password',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Auth::login(User::where('user_id', $this->adminUserId)->firstOrFail());
    }

    public function test_pos_ledger_includes_opening_running_and_period_balances_in_the_export(): void
    {
        $this->createPayment([
            'transaction_date' => now()->subDays(2)->toDateString(),
            'voucher_number' => 'VCH-OPEN',
            'amount' => 100,
            'from' => $this->adminUserId,
            'to' => $this->posUserId,
        ]);
        $this->createPayment([
            'transaction_date' => now()->subDay()->toDateString(),
            'voucher_number' => 'VCH-001',
            'reference_number' => 'REF-001',
            'account_details' => 'Settlement payment',
            'gst_no' => 'GST-123',
            'pay_by' => '0',
            'due' => 15,
            'amount' => 20,
            'from' => $this->posUserId,
            'to' => $this->adminUserId,
            'remark' => 'Daily settlement',
        ]);

        $request = new Request([
            'from_date' => now()->subDay()->toDateString(),
            'to_date' => now()->toDateString(),
        ]);
        $ledger = Payment::getLedgerData($request, $this->posUserId);
        $transaction = $ledger->first();

        $this->assertEquals(100, $ledger['opening_balance']);
        $this->assertSame('Admin', $transaction->account_name);
        $this->assertSame('Cash', $transaction->payment_method);
        $this->assertEquals(20, $transaction->credit);
        $this->assertSame('80.00', $transaction->balance);
        $this->assertSame('80.00', $ledger['total_balance']);

        $exportData = Payment::getLedgerDataExport($request, $this->posUserId, 'Sample POS');
        $exportRows = (new AccountLedgerExport($exportData))->collection();

        $this->assertCount(3, $exportRows);
        $this->assertSame('Opening Balance — Sample POS', $exportRows[0][2]);
        $this->assertSame('Admin', $exportRows[1][2]);
        $this->assertSame('Settlement payment | Daily settlement', $exportRows[1][3]);
        $this->assertSame('REF-001', $exportRows[1][4]);
        $this->assertEquals(15, $exportRows[1][8]);
        $this->assertSame('GST-123', $exportRows[1][9]);
        $this->assertSame('80.00 Dr', $exportRows[2][10]);
        $this->assertCount(11, (new AccountLedgerExport($exportData))->headings());
    }

    public function test_generated_summary_is_a_pos_debit_and_admin_settlement_credits_it(): void
    {
        $this->createPayment([
            'payment_summury_id' => 10,
            'voucher_number' => 'SUMMARY-AUTO',
            'reference_number' => 'SUMMARY-REF',
            'amount' => 75,
            'from' => $this->posUserId,
            'to' => $this->adminUserId,
            'remark' => 'Auto Generated',
        ]);
        $this->createPayment([
            'payment_summury_id' => 10,
            'voucher_number' => 'SUMMARY-SETTLEMENT',
            'reference_number' => 'SUMMARY-REF',
            'amount' => 75,
            'to' => $this->posUserId,
            'from' => $this->adminUserId,
            'remark' => 'DSR Summary Settled',
        ]);

        $this->actingAs(User::where('user_id', $this->posUserId)->firstOrFail());
        $posLedger = Payment::getLedgerData(new Request());
        $posEntries = $posLedger->filter(fn ($item, $key) => is_int($key))->values();
        $this->assertCount(2, $posEntries);
        $this->assertEquals(75, $posEntries[0]->debit);
        $this->assertSame('75.00', $posEntries[0]->balance);
        $this->assertSame('Dr', $posEntries[0]->balance_type);
        $this->assertEquals(75, $posEntries[1]->credit);
        $this->assertSame('0.00', $posEntries[1]->balance);
        $this->assertSame('Dr', $posEntries[1]->balance_type);
        $this->assertSame('75.00', $posLedger['total_debit']);
        $this->assertSame('75.00', $posLedger['total_credit']);
        $this->assertSame('0.00', $posLedger['total_balance']);

        $posExport = Payment::getLedgerDataExport(new Request());
        $this->assertCount(2, $posExport['rows']);
        $this->assertSame('0.00', $posExport['total_balance']);

        $adminLedger = Payment::getLedgerData(new Request(), $this->posUserId);
        $adminEntries = $adminLedger->filter(fn ($item, $key) => is_int($key))->values();
        $this->assertSame('SUMMARY-AUTO', $adminEntries->first()->voucher_number);
        $this->assertEquals(75, $adminEntries->first()->debit);
        $this->assertSame('SUMMARY-REF', $adminEntries->first()->reference_number);
        $this->assertSame('0.00', $adminEntries->last()->balance);
    }

    private function createPayment(array $overrides): void
    {
        DB::table('payments')->insert(array_merge([
            'payment_summury_id' => null,
            'transaction_date' => now()->toDateString(),
            'voucher_number' => 'VCH-DEFAULT',
            'reference_number' => null,
            'account_details' => null,
            'gst_no' => null,
            'pay_by' => null,
            'due' => 0,
            'amount' => 0,
            'to' => $this->posUserId,
            'from' => $this->adminUserId,
            'created_by' => $this->adminUserId,
            'updated_by' => $this->adminUserId,
            'remark' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }
}
