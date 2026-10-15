<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_systems', function (Blueprint $table) {
            $table->boolean('delivery_orders_requested')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('pos_systems', function (Blueprint $table) {
            $table->dropColumn('delivery_orders_requested');
        });
    }
};
