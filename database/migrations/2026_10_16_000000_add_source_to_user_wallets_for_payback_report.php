<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->string('source')->nullable()->after('trans_type');
        });

        DB::table('user_wallets')
            ->whereNull('wallet_id')
            ->whereNull('used_amount')
            ->whereNotNull('transaction_date')
            ->where('wallet_amount', '>', 0)
            ->update(['source' => 'legacy_admin_import']);
    }

    public function down(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
