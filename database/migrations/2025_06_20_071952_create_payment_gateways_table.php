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
            
            // Payment basic info
            $table->string('payment_method', 50); // credit_card, bank_transfer, e_wallet, etc
            $table->string('payment_gateway', 50)->default('midtrans'); // midtrans, manual, etc
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'expired', 'refunded'])->default('pending');
            
            // Midtrans specific fields
            $table->string('midtrans_order_id')->nullable()->unique(); // Order ID sent to Midtrans
            $table->string('midtrans_transaction_id')->nullable(); // Transaction ID from Midtrans
            $table->string('midtrans_transaction_status')->nullable(); // Transaction status from Midtrans
            $table->string('midtrans_payment_type')->nullable(); // Payment type from Midtrans
            $table->string('midtrans_gross_amount')->nullable(); // Gross amount from Midtrans
            $table->string('midtrans_fraud_status')->nullable(); // Fraud status from Midtrans
            $table->string('midtrans_status_code')->nullable(); // Status code from Midtrans
            $table->string('midtrans_signature_key')->nullable(); // Signature key for validation
            
            // Generic gateway fields (for other payment gateways)
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable(); // Store full response from payment gateway
            $table->json('gateway_request')->nullable(); // Store request sent to payment gateway
            
            // Additional fields
            $table->text('notes')->nullable(); // Admin notes
            $table->string('reference_number')->nullable(); // Bank reference number or receipt number
            $table->timestamp('paid_at')->nullable(); // When payment was confirmed
            $table->timestamp('expired_at')->nullable(); // When payment expires
            $table->timestamp('processed_at')->nullable(); // When payment was processed by gateway
            $table->timestamps();

            // Indexes
            $table->index('invoice_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index('payment_gateway');
            $table->index('midtrans_order_id');
            $table->index('midtrans_transaction_id');
            $table->index('midtrans_transaction_status');
            $table->index('paid_at');
            $table->index('expired_at');
            $table->index('created_at');
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