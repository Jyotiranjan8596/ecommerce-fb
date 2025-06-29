<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->string('reward_points')->after('remaining_amount')->default(0);
            $table->string('used_points')->after('reward_points')->default(0);
            $table->string('remaining_points')->after('used_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->dropColumn(['reward_points', 'used_points', 'remaining_points']);
        });
    }
};
