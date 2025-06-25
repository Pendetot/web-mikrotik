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
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('CASCADE');
            $table->string('payment_method', 50);
            $table->string('payment_gateway', 50)->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('invoice_id');
            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
