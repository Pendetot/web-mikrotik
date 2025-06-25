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
        // Create midtrans_webhook_logs table
        Schema::create('midtrans_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('transaction_status');
            $table->string('transaction_id')->nullable();
            $table->string('status_code')->nullable();
            $table->string('gross_amount')->nullable();
            $table->string('payment_type')->nullable();
            $table->json('raw_notification');
            $table->string('signature_key')->nullable();
            $table->enum('status', ['processed', 'failed'])->default('processed');
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('transaction_status');
            $table->index('status');
            $table->index('created_at');
        });

        // Create settings table if not exists
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, boolean, integer, json
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index('key');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans_webhook_logs');
        // Don't drop settings table as it might be used by other features
    }
};