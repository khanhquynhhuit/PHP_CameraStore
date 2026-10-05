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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->string('receiver_name', 100);
            $table->string('phone', 10);
            $table->string('shipping_address', 500);
            $table->enum('payment_method', ['cod', 'bank', 'vnpay']);
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->decimal('subtotal', 12, 0);
            $table->decimal('discount', 12, 0)->default(0);
            $table->decimal('shipping_fee', 12, 0)->default(0);
            $table->decimal('total', 12, 0);
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->string('cancel_reason', 255)->nullable();
            $table->string('tracking_code', 50)->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_total CHECK (total >= 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('orders')) {
            try {
                DB::statement('ALTER TABLE orders DROP CHECK chk_orders_total');
            } catch (\Throwable $e) {
                // Ignore if constraint already dropped
            }
        }

        Schema::dropIfExists('orders');
    }
};
