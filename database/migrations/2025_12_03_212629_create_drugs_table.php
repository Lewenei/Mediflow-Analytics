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
        Schema::create('drugs', function (Blueprint $table) {
    $table->id();
    $table->string('generic_name');
    $table->string('brand_name')->nullable();
    $table->string('dosage_form'); // Tablet, Injection, Syrup
    $table->string('strength');    // 500mg, 100ml
    $table->integer('pack_size');
    $table->decimal('unit_price', 10, 2);
    $table->integer('current_stock');
    $table->integer('reorder_level')->default(50);
    $table->boolean('is_narcotic')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
