<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Enums\CouponType;
use App\Models\Product;
use App\Models\User;
use App\Modules\Cart\Services\CouponService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_validate_and_calculate_percent_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'TEST10',
            'type' => CouponType::PERCENT,
            'value' => 10,
            'min_order' => 1000000,
            'max_uses' => 50,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);

        $service = new CouponService;
        $result = $service->validateAndCalculate('TEST10', 2000000);

        $this->assertTrue($result['valid']);
        $this->assertSame(200000.0, $result['discount']);
    }

    public function test_validate_and_calculate_fixed_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'TEST500K',
            'type' => CouponType::FIXED,
            'value' => 500000,
            'min_order' => 5000000,
            'max_uses' => 50,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);

        $service = new CouponService;
        $result = $service->validateAndCalculate('TEST500K', 10000000);

        $this->assertTrue($result['valid']);
        $this->assertSame(500000.0, $result['discount']);
    }

    public function test_validate_coupon_fails_when_below_min_order(): void
    {
        Coupon::create([
            'code' => 'MIN10M',
            'type' => CouponType::FIXED,
            'value' => 500000,
            'min_order' => 10000000,
            'is_active' => true,
        ]);

        $service = new CouponService;
        $result = $service->validateAndCalculate('MIN10M', 5000000);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Đơn hàng tối thiểu', $result['message']);
    }

    public function test_validate_coupon_fails_when_expired(): void
    {
        Coupon::create([
            'code' => 'EXPIRED',
            'type' => CouponType::PERCENT,
            'value' => 10,
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $service = new CouponService;
        $result = $service->validateAndCalculate('EXPIRED', 2000000);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('hết hạn', $result['message']);
    }

    public function test_validate_coupon_fails_when_max_uses_reached(): void
    {
        Coupon::create([
            'code' => 'MAXED',
            'type' => CouponType::PERCENT,
            'value' => 10,
            'max_uses' => 5,
            'used_count' => 5,
            'is_active' => true,
        ]);

        $service = new CouponService;
        $result = $service->validateAndCalculate('MAXED', 2000000);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('hết lượt sử dụng', $result['message']);
    }

    public function test_cart_coupon_apply_endpoint_success(): void
    {
        $user = User::factory()->create();

        Coupon::create([
            'code' => 'WELCOME10',
            'type' => CouponType::PERCENT,
            'value' => 10,
            'min_order' => 500000,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson(route('cart.coupon.apply'), [
                'code' => 'WELCOME10',
                'subtotal' => 2000000,
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'coupon' => [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
            ],
            'discount' => 200000,
        ]);
    }

    public function test_cart_coupon_apply_endpoint_fails_for_invalid_coupon(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('cart.coupon.apply'), [
                'code' => 'NON_EXISTENT',
                'subtotal' => 2000000,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_checkout_applies_coupon_and_records_discount(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);

        $category = Category::create(['name' => 'Máy ảnh', 'slug' => 'may-anh']);
        $brand = Brand::create(['name' => 'Sony', 'slug' => 'sony']);

        $product = Product::create([
            'name' => 'Sony A7 IV',
            'slug' => 'sony-a7-iv',
            'sku' => 'CAM-001',
            'price' => 50000000,
            'stock' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $cart->cartItems()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $coupon = Coupon::create([
            'code' => 'GIAM500K',
            'type' => CouponType::FIXED,
            'value' => 500000,
            'min_order' => 10000000,
            'max_uses' => 50,
            'used_count' => 0,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/checkout', [
                'receiver_name' => 'Nguyễn Văn Khách',
                'phone' => '0903456789',
                'shipping_address' => 'Số 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'payment_method' => 'cod',
                'coupon_code' => 'GIAM500K',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'subtotal' => 50000000,
            'discount' => 500000,
            'total' => 49500000,
        ]);

        $this->assertSame(1, $coupon->fresh()->used_count);
    }
}
