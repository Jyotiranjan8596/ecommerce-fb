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
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('pos_id');
            $table->string('invoice')->nullable();
            $table->string('used_amount')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('pay_by')->nullable();
            $table->string('trans_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
