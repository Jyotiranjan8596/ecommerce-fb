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
            $table->unsignedBigInteger('payment_summury_id');
            $table->date('transaction_date');
            $table->string('voucher_number');
            $table->string('reference_number');
            $table->text('account_details')->nullable();
            $table->text('gst_no')->nullable();
            $table->enum('pay_by', ['0', '1'])->nullable()->default(null)->comment('1=>upi,0=>cash');
            $table->decimal('due', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit', 15, 2)->default(0);
            $table->string('credited_to');
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('payment_summury_id')
                ->references('id')
                ->on('payment_summaries');

            $table->foreign('credited_to')
                ->references('user_id')
                ->on('users');

            $table->foreign('created_by')
                ->references('user_id')
                ->on('users');

            $table->foreign('updated_by')
                ->references('user_id')
                ->on('users');

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
