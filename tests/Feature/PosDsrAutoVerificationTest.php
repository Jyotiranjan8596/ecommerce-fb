<?php

namespace Tests\Feature;

use App\Models\Wallet;
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
        });
    }

    protected function tearDown(): void
    {
        \Carbon\Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_pos_can_verify_its_pending_transactions_until_next_day_noon_only(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-13 11:59:00');

        $ownNullStatus = $this->createTransaction(1, '2026-10-12 10:00:00', null);
        $ownZeroStatus = $this->createTransaction(1, '2026-10-12 11:00:00', 0);
        $otherPos = $this->createTransaction(2, '2026-10-12 12:00:00', null);
        $alreadyVerified = $this->createTransaction(1, '2026-10-12 13:00:00', 1);

        $this->assertSame(2, Wallet::verifyDsr(
            [$ownNullStatus, $ownZeroStatus, $otherPos, $alreadyVerified],
            1
        ));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $ownNullStatus)->value('status'));
        $this->assertSame(1, (int) DB::table('wallets')->where('id', $ownZeroStatus)->value('status'));
        $this->assertNull(DB::table('wallets')->where('id', $otherPos)->value('status'));

        \Carbon\Carbon::setTestNow('2026-10-13 12:00:00');
        $afterCutoff = $this->createTransaction(1, '2026-10-12 14:00:00', null);

        $this->assertSame(0, Wallet::verifyDsr([$afterCutoff], 1));
        $this->assertNull(DB::table('wallets')->where('id', $afterCutoff)->value('status'));
    }

    public function test_scheduled_verification_covers_all_pos_and_catches_up_after_noon(): void
    {
        \Carbon\Carbon::setTestNow('2026-10-14 12:00:00');

        $olderPending = $this->createTransaction(1, '2026-10-11 10:00:00', null);
        $yesterdayZeroStatus = $this->createTransaction(2, '2026-10-13 10:00:00', 0);
        $alreadyVerified = $this->createTransaction(2, '2026-10-13 11:00:00', 1);
        $todayPending = $this->createTransaction(1, '2026-10-14 09:00:00', null);

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

    private function createTransaction(int $posId, string $transactionDate, ?int $status): int
    {
        return DB::table('wallets')->insertGetId([
            'pos_id' => $posId,
            'transaction_date' => $transactionDate,
            'status' => $status,
        ]);
    }
}
