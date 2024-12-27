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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sector_id')->nullable();
            $table->string('subsector_id')->nullable();
            $table->string('title')->nullable();
            $table->string('meta_tag')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('product_file')->nullable();
            $table->string('price')->nullable();
            $table->string('source')->nullable();
            $table->string('header')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
