<?php

namespace Tests\Feature;

use App\Models\Wallet;
use App\Models\User;
use App\Services\PaymentSummaryGenerator;
use App\Services\PosPaymentAutoSubmitter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PosDsrAutoVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.timezone' => 'Asia/Kolkata',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->dateTime('transaction_date')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->string('mobilenumber')->nullable();
        });

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
        });

        Schema::create('payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->date('date');
            $table->string('status')->nullable();
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

    public function test_pos_can_verify_its_pending_transactions_at_any_time(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-14 13:00:00');

        $ownNullStatus = $this->createTransaction(1, '2026-10-12 10:00:00', null);
        $ownZeroStatus = $this->createTransaction(1, '2026-10-12 11:00:00', 0);
        $otherPos = $this->createTransaction(2, '2026-10-12 12:00:00', null);
        $alreadyVerified = $this->createTransaction(1, '2026-10-12 13:00:00', 1);

        $this->assertSame(2, Wallet::verifyDsr([
            $ownNullStatus,
            $ownZeroStatus,
            $otherPos,
            $alreadyVerified,
        ], 1));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $ownNullStatus)->value('status'));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $ownZeroStatus)->value('status'));
        $this->assertNull(DB::table('wallets')->where('id', $otherPos)->value('status'));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $alreadyVerified)->value('status'));
    }

    public function test_verify_all_covers_every_pending_transaction_in_the_report_filters(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-15 13:00:00');
        DB::table('pos_systems')->insert(['id' => 1, 'user_id' => 'pos-user']);

        $user = new User();
        $user->user_id = 'pos-user';
        $user->role = 4;
        $user->status = 1;
        $this->actingAs($user);

        $matchingIds = [];
        for ($i = 0; $i < 20; $i++) {
            $matchingIds[] = $this->createTransaction(1, '2026-10-14 10:00:00', null, '1234567890');
        }
        $otherMobile = $this->createTransaction(1, '2026-10-14 10:00:00', null, '9999999999');
        $otherDate = $this->createTransaction(1, '2026-10-13 10:00:00', null, '1234567890');
        $otherPos = $this->createTransaction(2, '2026-10-14 10:00:00', null, '1234567890');

        $this->mock(PaymentSummaryGenerator::class)
            ->shouldReceive('generateWhenComplete')
            ->once()
            ->with(1, '2026-10-14')
            ->andReturn('existing');

        $this->postJson(route('pos.dsr.verify.transaction'), [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-14',
            'search' => '1234567890',
        ])->assertOk()->assertJsonPath('count', 20);

        $this->assertSame(20, DB::table('wallets')->whereIn('id', $matchingIds)->where('status', 1)->count());
        $this->assertNull(DB::table('wallets')->where('id', $otherMobile)->value('status'));
        $this->assertNull(DB::table('wallets')->where('id', $otherDate)->value('status'));
        $this->assertNull(DB::table('wallets')->where('id', $otherPos)->value('status'));
    }

    public function test_scheduled_verification_covers_all_pos_and_catches_up_after_noon(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-14 12:00:00');

        $olderPending = $this->createTransaction(1, '2026-10-11 10:00:00', null);
        $yesterdayZeroStatus = $this->createTransaction(2, '2026-10-13 10:00:00', 0);
        $alreadyVerified = $this->createTransaction(2, '2026-10-13 11:00:00', 1);
        $todayPending = $this->createTransaction(1, '2026-10-14 09:00:00', null);

        DB::table('payment_summaries')->insert([
            'pos_id' => 2,
            'date' => '2026-10-13',
            'status' => 'approved',
        ]);
        $this->mock(PaymentSummaryGenerator::class)
            ->shouldReceive('generateWhenComplete')
            ->once()
            ->with(1, '2026-10-11')
            ->andReturn('existing');
        $this->mock(PosPaymentAutoSubmitter::class)
            ->shouldReceive('submitWhenReady')
            ->once()
            ->with(1, '2026-10-11')
            ->andReturn('already_settled');

        $this->artisan('app:verify-pos-dsr-transactions')->assertSuccessful();

        $this->assertSame(1, (int) DB::table('wallets')->where('id', $olderPending)->value('status'));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $yesterdayZeroStatus)->value('status'));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $alreadyVerified)->value('status'));
        $this->assertNull(DB::table('wallets')->where('id', $todayPending)->value('status'));
    }

    public function test_scheduled_verification_does_not_run_before_noon(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-13 11:59:00');
        $yesterdayPending = $this->createTransaction(1, '2026-10-12 10:00:00', null);

        $this->artisan('app:verify-pos-dsr-transactions')->assertSuccessful();

        $this->assertNull(DB::table('wallets')->where('id', $yesterdayPending)->value('status'));
    }

    private function createTransaction(
        int $posId,
        string $transactionDate,
        ?int $status,
        ?string $mobileNumber = null
    ): int
    {
        return DB::table('wallets')->insertGetId([
            'pos_id' => $posId,
            'transaction_date' => $transactionDate,
            'status' => $status,
            'mobilenumber' => $mobileNumber,
        ]);
    }
}
