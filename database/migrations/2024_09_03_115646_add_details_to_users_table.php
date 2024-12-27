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
            $table->string('gender')->nullable()->after('email'); 
            $table->text('address')->nullable()->after('password'); 
            $table->string('city')->nullable()->after('role'); 
            $table->string('state')->nullable()->after('city'); 
            $table->string('zip')->nullable()->after('state');
            $table->string('sponsor_id')->nullable()->after('zip'); 
            $table->string('parent_level')->nullable()->after('sponsor_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
