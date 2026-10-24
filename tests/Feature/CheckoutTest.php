<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_checkout(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/login');
    }

    public function test_user_with_empty_cart_is_redirected_to_cart_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/checkout');

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_access_checkout_page_when_cart_has_items(): void
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

        $response = $this
            ->actingAs($user)
            ->get('/checkout');

        $response->assertOk();
    }

    public function test_user_can_submit_checkout_without_403_forbidden(): void
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

        $response = $this
            ->actingAs($user)
            ->post('/checkout', [
                'receiver_name' => 'Nguyễn Văn Khách',
                'phone' => '0903456789',
                'shipping_address' => 'Số 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'payment_method' => 'cod',
                'note' => 'Giao vào buổi sáng',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'receiver_name' => 'Nguyễn Văn Khách',
            'phone' => '0903456789',
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);
    }

    public function test_user_can_checkout_directly_using_saved_address_id(): void
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create([
            'receiver_name' => 'Trần Thị Nhận',
            'phone' => '0912345678',
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'detail' => 'Số 1 Lê Duẩn',
            'is_default' => true,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        $category = Category::create(['name' => 'Ống kính', 'slug' => 'ong-kinh']);
        $brand = Brand::create(['name' => 'Canon', 'slug' => 'canon']);
        $product = Product::create([
            'name' => 'Canon RF 50mm f/1.2L',
            'slug' => 'canon-rf-50mm',
            'sku' => 'LENS-001',
            'price' => 45000000,
            'stock' => 5,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
        $cart->cartItems()->create([
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cod',
                'note' => 'Giao giờ hành chính',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'receiver_name' => 'Trần Thị Nhận',
            'phone' => '0912345678',
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_checkout_only_selected_items_and_keep_others_in_cart(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $category = Category::create(['name' => 'Phụ kiện', 'slug' => 'phu-kien']);
        $brand = Brand::create(['name' => 'Sony', 'slug' => 'sony-pk']);

        $product1 = Product::create([
            'name' => 'Pin Sony NP-FZ100',
            'slug' => 'pin-sony-fz100',
            'sku' => 'ACC-001',
            'price' => 1800000,
            'stock' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
        $product2 = Product::create([
            'name' => 'Thẻ nhớ Sony 128GB',
            'slug' => 'the-nho-sony-128gb',
            'sku' => 'ACC-002',
            'price' => 2500000,
            'stock' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $item1 = $cart->cartItems()->create([
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);
        $item2 = $cart->cartItems()->create([
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/checkout', [
                'receiver_name' => 'Lê Văn Mua',
                'phone' => '0988776655',
                'shipping_address' => '123 Cầu Diễn, Bắc Từ Liêm, Hà Nội',
                'payment_method' => 'cod',
                'selected_ids' => [$item1->id],
            ]);

        $response->assertSessionHasNoErrors();
        // Item 1 đã mua nên bị xóa khỏi giỏ
        $this->assertDatabaseMissing('cart_items', [
            'id' => $item1->id,
        ]);
        // Item 2 chưa chọn nên vẫn còn trong giỏ
        $this->assertDatabaseHas('cart_items', [
            'id' => $item2->id,
        ]);
    }
}
