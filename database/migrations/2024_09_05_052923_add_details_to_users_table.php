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
        Schema::table('users', function (Blueprint $table) {
            $table->string('pan_no')->nullable()->after('state');
            $table->string('ifsc')->nullable()->after('pan_no');
            $table->string('account_no')->nullable()->after('ifsc');
            $table->string('nominee_name')->nullable()->after('account_no');
            $table->string('bank')->nullable()->after('nominee_name');
            $table->string('relation_user')->nullable()->after('bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pan_no')->nullable()->after('state');
            $table->string('ifsc')->nullable()->after('pan_no');
            $table->string('account_no')->nullable()->after('ifsc');
            $table->string('nominee_name')->nullable()->after('account_no');
            $table->string('bank')->nullable()->after('nominee_name');
            $table->string('relation_user')->nullable()->after('bank');
        });
    }
};
