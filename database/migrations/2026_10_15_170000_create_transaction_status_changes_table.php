<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_status_changes', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');
            $table->string('event_type', 24);
            $table->text('old_status')->nullable();
            $table->text('new_status')->nullable();
            $table->json('changed_fields')->nullable();
            $table->string('actor_type', 24);
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('actor_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_status_changes');
    }
};
