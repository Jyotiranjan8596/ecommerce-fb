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
        Schema::create('payment_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_transaction')->default(0);
            $table->decimal('total_billing_amount', 12, 2)->default(0);
            $table->decimal('by_cash', 12, 2)->default(0);
            $table->decimal('by_wallet', 12, 2)->default(0);
            $table->decimal('by_reward', 12, 2)->default(0);
            $table->decimal('pos_credit', 12, 2)->default(0);
            $table->decimal('pos_debit', 12, 2)->default(0);
            $table->decimal('admin_credit', 12, 2)->default(0);
            $table->decimal('admin_debit', 12, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_summaries');
    }
};
