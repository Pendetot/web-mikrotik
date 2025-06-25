<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_packages_table.php

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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('duration');
            $table->enum('duration_type', ['hari', 'minggu', 'bulan', 'tahun'])->default('hari');
            $table->foreignId('category_id')->nullable()->constrained('package_categories')->onDelete('SET NULL'); // Foreign Key
            $table->json('features')->nullable(); // JSON type
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('price');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
