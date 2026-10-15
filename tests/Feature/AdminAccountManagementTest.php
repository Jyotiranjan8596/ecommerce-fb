<?php

namespace Tests\Feature;

use App\Models\PosModel;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminAccountManagementTest extends TestCase
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
            $table->string('mobilenumber')->nullable();
            $table->string('password');
            $table->integer('role');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('sponsor_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function test_new_user_accounts_default_to_active(): void
    {
        $this->assertSame(1, (int) (new User())->status);
    }

    public function test_admin_can_suspend_customer_and_update_customer_password(): void
    {
        $admin = $this->createUser('admin-1', 1);
        $customer = $this->createUser('customer-1', 3);

        $this->actingAs($admin)
            ->post(route('admin.users.status', $customer), ['status' => 0])
            ->assertRedirect();

        $this->assertSame(0, (int) $customer->fresh()->status);

        $this->post(route('admin.users.password', $customer), [
            'password' => 'NewCustomerPassword1',
            'password_confirmation' => 'NewCustomerPassword1',
        ])->assertRedirect();

        $customer->refresh();
        $this->assertTrue(Hash::check('NewCustomerPassword1', $customer->password));
    }

    public function test_admin_can_activate_and_change_pos_account_password(): void
    {
        $admin = $this->createUser('admin-2', 1);
        $posUser = $this->createUser('pos-1', 4, 0);
        $pos = PosModel::create(['user_id' => $posUser->user_id, 'name' => 'Test POS']);

        $this->actingAs($admin)
            ->post(route('admin.pos_system.account-status', $pos), ['status' => 0])
            ->assertRedirect();
        $this->assertSame(0, (int) $posUser->fresh()->status);

        $this->actingAs($admin)
            ->post(route('admin.pos_system.account-status', $pos), ['status' => 1])
            ->assertRedirect();

        $this->assertSame(1, (int) $posUser->fresh()->status);

        $this->post(route('admin.pos_system.account-password', $pos), [
            'password' => 'NewPosPassword1',
            'password_confirmation' => 'NewPosPassword1',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('NewPosPassword1', $posUser->fresh()->password));
    }

    public function test_suspended_pos_accounts_cannot_sign_in_or_access_pos_pages(): void
    {
        $posUser = $this->createUser('pos-3', 4);
        $this->assertTrue(Hash::check('CurrentPassword1', $posUser->password));
        DB::table('users')->where('id', $posUser->id)->update(['status' => 0]);

        $this->actingAs($posUser->fresh())
            ->get(route('pos.index'))
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login'), [
            'mobile_or_user_id' => $posUser->user_id,
            'password' => 'CurrentPassword1',
        ])->assertRedirect();
        $this->assertGuest();
    }

    public function test_management_actions_reject_accounts_with_the_wrong_role(): void
    {
        $admin = $this->createUser('admin-3', 1);
        $posUser = $this->createUser('pos-2', 4);

        $this->actingAs($admin)
            ->post(route('admin.users.status', $posUser), ['status' => 0])
            ->assertNotFound();

        $this->assertSame(1, (int) $posUser->fresh()->status);
    }

    public function test_suspended_customers_cannot_sign_in(): void
    {
        $customer = $this->createUser('customer-2', 3);
        $this->assertTrue(Hash::check('CurrentPassword1', $customer->password));

        $this->post(route('login'), [
            'mobile_or_user_id' => $customer->user_id,
            'password' => 'CurrentPassword1',
        ])->assertRedirect('/user');
        $this->assertAuthenticatedAs($customer);

        DB::table('users')->where('id', $customer->id)->update(['status' => 0]);
        $this->assertSame(0, (int) $customer->fresh()->status);

        $this->actingAs($customer->fresh())
            ->get('/user')
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login'), [
            'mobile_or_user_id' => $customer->user_id,
            'password' => 'CurrentPassword1',
        ])->assertRedirect();

        $this->assertGuest();
    }

    private function createUser(string $userId, int $role, int $status = 1): User
    {
        $id = DB::table('users')->insertGetId([
            'user_id' => $userId,
            'name' => $userId,
            'email' => $userId . '@example.test',
            'mobilenumber' => null,
            'password' => Hash::make('CurrentPassword1'),
            'role' => $role,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
