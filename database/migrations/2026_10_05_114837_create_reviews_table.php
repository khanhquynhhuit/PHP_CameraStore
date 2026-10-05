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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('comment', 1000)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'product_id', 'order_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating >= 1 AND rating <= 5)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('reviews')) {
            try {
                DB::statement('ALTER TABLE reviews DROP CHECK chk_reviews_rating');
            } catch (\Throwable $e) {
                // Ignore if constraint already dropped
            }
        }

        Schema::dropIfExists('reviews');
    }
};
