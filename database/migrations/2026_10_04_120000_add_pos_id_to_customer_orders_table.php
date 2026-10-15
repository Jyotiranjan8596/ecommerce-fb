<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->foreignId('pos_id')
                ->nullable()
                ->after('user_id')
                ->constrained('pos_systems')
                ->nullOnDelete();
        });

        $defaultPosId = DB::table('pos_systems')
            ->whereRaw("LOWER(REPLACE(name, ' ', '')) = ?", ['freebazar1'])
            ->value('id');

        if ($defaultPosId) {
            DB::table('customer_orders')->whereNull('pos_id')->update(['pos_id' => $defaultPosId]);
        }
    }

    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropForeign(['pos_id']);
            $table->dropColumn('pos_id');
        });
    }
};
