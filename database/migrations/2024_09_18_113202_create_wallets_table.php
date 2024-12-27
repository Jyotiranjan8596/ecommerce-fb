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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('invoice')->nullable();
            $table->string('amount')->nullable();
            $table->string('pay_by')->nullable();
            $table->decimal('transaction_amount', 12, 2)->nullable();
            $table->string('pay_by_wallet')->default('wallet');
            $table->integer('amount_wallet');
            $table->integer('user_id')->nullable();
            $table->string('pos_id')->nullable();
            $table->integer('insert_by')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('insert_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
