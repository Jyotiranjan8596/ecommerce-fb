<?php

namespace Tests\Feature;

use App\Models\CustomerOrder;
use App\Models\TransactionStatusChange;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TransactionStatusAuditTest extends TestCase
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

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->nullable();
            $table->decimal('billing_amount', 12, 2)->nullable();
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
        });

        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->json('items')->nullable();
            $table->string('status')->default(CustomerOrder::STATUS_PENDING);
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

    public function test_status_transitions_and_edits_record_the_authenticated_actor(): void
    {
        $pos = new User();
        $pos->forceFill(['id' => 4, 'role' => 4, 'name' => 'Freebazar POS']);
        $this->actingAs($pos);

        $walletId = DB::table('wallets')->insertGetId([
            'invoice' => 'AUDIT-1',
            'pos_id' => 10,
            'status' => 0,
        ]);
        $wallet = Wallet::findOrFail($walletId);

        $wallet->status = 1;
        $wallet->billing_amount = 125;
        $wallet->save();

        $wallet->billing_amount = 150;
        $wallet->save();

        $changes = $wallet->statusChanges()->get();
        $this->assertCount(2, $changes);
        $this->assertSame('status_changed', $changes[1]->event_type);
        $this->assertSame('0', $changes[1]->old_status);
        $this->assertSame('1', $changes[1]->new_status);
        $this->assertSame('pos', $changes[1]->actor_type);
        $this->assertSame('Freebazar POS', $changes[1]->actor_name);
        $this->assertEqualsCanonicalizing(['status', 'billing_amount'], $changes[1]->changed_fields);
        $this->assertSame('edited', $changes[0]->event_type);
        $this->assertSame(['billing_amount'], $changes[0]->changed_fields);
    }

    public function test_automatic_updates_are_attributed_to_the_system(): void
    {
        Auth::logout();

        $order = new CustomerOrder();
        $order->user_id = 25;
        $order->pos_id = 10;
        $order->save();

        $change = TransactionStatusChange::query()
            ->where('subject_type', CustomerOrder::class)
            ->where('subject_id', $order->getKey())
            ->firstOrFail();

        $this->assertSame('system', $change->actor_type);
        $this->assertSame('pending', $change->new_status);
        $this->assertSame('Record created with status Pending', $change->description);
        $this->assertSame('Automatic system', $change->actor_label);
    }
}
