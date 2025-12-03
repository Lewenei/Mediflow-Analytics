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
        Schema::create('dispenses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained();
    $table->foreignId('drug_id')->constrained();
    $table->integer('quantity');
    $table->decimal('total_cost', 10, 2);
    $table->timestamp('dispensed_at')->useCurrent();
    $table->string('dispensed_by')->nullable(); // Nurse name
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispenses');
    }
};
