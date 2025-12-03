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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();           // INV-2025-0001
            $table->decimal('total_amount', 12, 2);
            $table->decimal('nhif_covered', 12, 2)->default(0);
            $table->decimal('patient_copay', 12, 2);
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamp('nhif_submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
