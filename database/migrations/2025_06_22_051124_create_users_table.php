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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('whatsapp', 20)->nullable(); // Kolom WhatsApp
            $table->enum('role', ['admin', 'user'])->default('user'); // Kolom Role
            $table->boolean('is_active')->default(true); // Kolom is_active
            $table->rememberToken();
            $table->timestamps(); // Ini akan membuat created_at dan updated_at

            // Menambahkan indeks (opsional, tapi bagus untuk performa)
            $table->index('role');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
