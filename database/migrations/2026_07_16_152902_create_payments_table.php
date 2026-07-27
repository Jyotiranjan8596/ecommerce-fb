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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->date('transaction_date');
            $table->string('voucher_number')->unique();
            $table->string('reference_number')->unique();
            $table->text('account_details')->nullable();
            $table->decimal('due', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->string('credited_to');
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('credited_to')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    // date,vouchernumber,account_details,due,credit,debit,credited_to,created_by,updated_by,remark.

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
