<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();
            $table->string('sku', 30)->unique();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->decimal('price', 12, 0);
            $table->decimal('original_price', 12, 0)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->text('description')->nullable();
            $table->json('specs')->nullable();
            $table->unsignedTinyInteger('warranty_months')->default(12);
            $table->enum('status', ['active', 'hidden'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'category_id', 'brand_id', 'price'], 'idx_products_filter');
            $table->fullText('name', 'idx_products_name_fulltext');
        });

        // Ràng buộc CHECK trên MySQL 8
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products ADD CONSTRAINT chk_products_price CHECK (price > 0)');
            DB::statement('ALTER TABLE products ADD CONSTRAINT chk_products_original_price CHECK (original_price IS NULL OR original_price >= price)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('products')) {
            try {
                DB::statement('ALTER TABLE products DROP CHECK chk_products_price');
                DB::statement('ALTER TABLE products DROP CHECK chk_products_original_price');
            } catch (\Throwable $e) {
                // Ignore if constraint already dropped
            }
        }

        Schema::dropIfExists('products');
    }
};
