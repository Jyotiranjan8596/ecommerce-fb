<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminCustomerTransactionsTest extends TestCase
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
            $table->string('email')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('password');
            $table->integer('role');
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->string('invoice')->nullable();
            $table->string('description')->nullable();
            $table->string('billing_amount')->nullable();
            $table->string('amount_wallet')->nullable();
            $table->string('reward_amount')->nullable();
            $table->string('amount')->nullable();
            $table->string('transaction_amount')->nullable();
            $table->string('pay_by_wallet')->nullable();
            $table->string('pay_by')->nullable();
            $table->string('tran_type')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->timestamp('insert_date')->nullable();
            $table->integer('status')->nullable();
        });

        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pos_id')->nullable();
            $table->string('invoice')->nullable();
            $table->string('source')->nullable();
            $table->string('trans_type')->nullable();
            $table->string('used_amount')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('remaining_amount')->nullable();
            $table->string('reward_points')->nullable();
            $table->string('used_points')->nullable();
            $table->string('remaining_points')->nullable();
            $table->string('pay_by')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
        });
    }

    public function test_admin_can_view_only_the_selected_customers_transaction_history(): void
    {
        $admin = $this->createUser('admin-1', 1);
        $customer = $this->createUser('customer-1', 3);
        $otherCustomer = $this->createUser('customer-2', 3);

        DB::table('wallets')->insert([
            ['user_id' => $customer->id, 'invoice' => 'CUSTOMER-SALE-1'],
            ['user_id' => $otherCustomer->id, 'invoice' => 'OTHER-SALE-1'],
        ]);
        DB::table('user_wallets')->insert([
            ['user_id' => $customer->id, 'invoice' => 'CUSTOMER-LEDGER-1'],
            ['user_id' => $otherCustomer->id, 'invoice' => 'OTHER-LEDGER-1'],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.transactions', $customer))
            ->assertOk()
            ->assertSee('CUSTOMER-SALE-1')
            ->assertSee('CUSTOMER-LEDGER-1')
            ->assertDontSee('OTHER-SALE-1')
            ->assertDontSee('OTHER-LEDGER-1');
    }

    private function createUser(string $userId, int $role): User
    {
        $id = DB::table('users')->insertGetId([
            'user_id' => $userId,
            'name' => $userId,
            'email' => $userId . '@example.test',
            'password' => bcrypt('password'),
            'role' => $role,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
