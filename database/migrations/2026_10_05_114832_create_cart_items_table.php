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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['cart_id', 'product_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE cart_items ADD CONSTRAINT chk_cart_items_quantity CHECK (quantity > 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('cart_items')) {
            try {
                DB::statement('ALTER TABLE cart_items DROP CHECK chk_cart_items_quantity');
            } catch (\Throwable $e) {
                // Ignore if constraint already dropped
            }
        }

        Schema::dropIfExists('cart_items');
    }
};
