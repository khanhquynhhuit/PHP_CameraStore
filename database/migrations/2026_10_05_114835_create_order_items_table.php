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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('product_name', 200);
            $table->decimal('unit_price', 12, 0);
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT chk_order_items_quantity CHECK (quantity > 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('order_items')) {
            try {
                DB::statement('ALTER TABLE order_items DROP CHECK chk_order_items_quantity');
            } catch (\Throwable $e) {
                // Ignore if constraint already dropped
            }
        }

        Schema::dropIfExists('order_items');
    }
};
