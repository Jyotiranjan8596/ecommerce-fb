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
        Schema::table('pos_systems', function (Blueprint $table) {
            $table->string('upi_id')->nullable()->after('mobilenumber'); // Replace 'column_name' with an existing column name

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_systems', function (Blueprint $table) {
            $table->dropColumn('upi_id');
        });
    }
};
