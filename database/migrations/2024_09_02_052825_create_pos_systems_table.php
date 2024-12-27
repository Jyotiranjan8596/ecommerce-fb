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
        Schema::create('pos_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();;
            $table->string('email')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('transaction_charge')->nullable();
            $table->string('min_charge')->nullable();
            $table->string('max_charge')->nullable();
            $table->string('initial_letter_of_invoice')->nullable();
            $table->string('pos_code')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('entity_address')->nullable();
            $table->string('entity_contact')->nullable();
            $table->text('comment')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_systems');
    }
};
