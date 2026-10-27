<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $staff;

    private User $customer;

    private Category $category;

    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->staff = User::factory()->staff()->create();
        $this->customer = User::factory()->customer()->create();

        $this->category = Category::factory()->create(['name' => 'Máy ảnh Mirrorless']);
        $this->brand = Brand::factory()->create(['name' => 'Sony']);
    }

    /* =========================================================================
     * 1. PHÂN QUYỀN TRUY CẬP (ROLE-BASED PERMISSIONS)
     * ========================================================================= */

    public function test_guest_cannot_access_admin_product_pages(): void
    {
        $this->get(route('admin.products.index'))->assertRedirect(route('login'));
        $this->get(route('admin.inventory.index'))->assertRedirect(route('login'));
        $this->get(route('admin.products.create'))->assertRedirect(route('login'));
    }

    public function test_customer_is_forbidden_from_admin_product_pages(): void
    {
        $this->actingAs($this->customer)->get(route('admin.products.index'))->assertStatus(403);
        $this->actingAs($this->customer)->get(route('admin.inventory.index'))->assertStatus(403);
        $this->actingAs($this->customer)->get(route('admin.products.create'))->assertStatus(403);
    }

    public function test_admin_is_forbidden_from_admin_product_pages(): void
    {
        $this->actingAs($this->admin)->get(route('admin.products.index'))->assertStatus(403);
        $this->actingAs($this->admin)->get(route('admin.inventory.index'))->assertStatus(403);
        $this->actingAs($this->admin)->get(route('admin.products.create'))->assertStatus(403);
    }

    public function test_staff_can_access_admin_product_pages(): void
    {
        $this->actingAs($this->staff)->get(route('admin.products.index'))->assertStatus(200);
        $this->actingAs($this->staff)->get(route('admin.inventory.index'))->assertStatus(200);
        $this->actingAs($this->staff)->get(route('admin.products.create'))->assertStatus(200);
    }

    /* =========================================================================
     * 2. STOREFRONT CÔNG KHAI DÀNH CHO KHÁCH (TÌM KIẾM, LỌC & PHÂN TRANG)
     * ========================================================================= */

    public function test_public_user_can_view_product_list_with_pagination(): void
    {
        Product::factory()->count(12)->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'status' => ProductStatus::ACTIVE,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewHas('danhSachSanPham');

        $products = $response->viewData('danhSachSanPham');
        // Phân trang 9 sản phẩm/trang theo chuẩn rubric
        $this->assertEquals(9, $products->perPage());
        $this->assertEquals(12, $products->total());
    }

    public function test_public_user_can_filter_products_by_keyword_and_category(): void
    {
        Product::factory()->create([
            'name' => 'Sony Alpha A7 IV',
            'sku' => 'SONY-A7M4-TEST',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 50000000,
            'status' => ProductStatus::ACTIVE,
        ]);

        Product::factory()->create([
            'name' => 'Canon EOS R6 Mark II',
            'sku' => 'CANON-R6M2-TEST',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 55000000,
            'status' => ProductStatus::ACTIVE,
        ]);

        // Tìm theo từ khóa
        $response = $this->get(route('products.index', ['keyword' => 'Sony Alpha']));
        $response->assertStatus(200);
        $products = $response->viewData('danhSachSanPham');
        $this->assertEquals(1, $products->total());

        // Tìm theo SKU
        $responseSku = $this->get(route('products.index', ['keyword' => 'CANON-R6M2']));
        $responseSku->assertStatus(200);
        $productsSku = $responseSku->viewData('danhSachSanPham');
        $this->assertEquals(1, $productsSku->total());
    }

    public function test_public_user_can_filter_products_by_price_range(): void
    {
        Product::factory()->create([
            'name' => 'Máy ảnh giá rẻ',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 10000000,
            'status' => ProductStatus::ACTIVE,
        ]);

        Product::factory()->create([
            'name' => 'Máy ảnh cao cấp',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 80000000,
            'status' => ProductStatus::ACTIVE,
        ]);

        $response = $this->get(route('products.index', [
            'min_price' => 5000000,
            'max_price' => 20000000,
        ]));

        $response->assertStatus(200);
        $products = $response->viewData('danhSachSanPham');
        $this->assertEquals(1, $products->total());
        $this->assertEquals('Máy ảnh giá rẻ', $products->first()->name);
    }

    public function test_public_user_can_view_single_product_detail(): void
    {
        $product = Product::factory()->create([
            'name' => 'Fujifilm X-T5 Mirrorless',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'status' => ProductStatus::ACTIVE,
        ]);

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewHas('sanPham');
        $response->assertSee('Fujifilm X-T5 Mirrorless');
    }

    /* =========================================================================
     * 3. STAFF PRODUCT CRUD (THÊM, SỬA, XÓA & UPLOAD ẢNH)
     * ========================================================================= */

    public function test_staff_can_create_product_with_image_upload(): void
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('camera-sony.jpg', 800, 600);

        $productData = [
            'name' => 'Sony Alpha A7R V Chính Hãng',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'SON-A7R5-CRUD',
            'price' => 84990000,
            'original_price' => 89990000,
            'stock' => 15,
            'warranty_months' => 24,
            'status' => 'active',
            'description' => 'Máy ảnh cảm biến Full-frame 61MP cực nét.',
            'image' => $fakeImage,
        ];

        $response = $this->actingAs($this->staff)->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Sony Alpha A7R V Chính Hãng',
            'sku' => 'SON-A7R5-CRUD',
            'price' => 84990000,
            'stock' => 15,
        ]);

        $newProduct = Product::where('sku', 'SON-A7R5-CRUD')->first();
        $this->assertNotNull($newProduct);
        $this->assertCount(1, $newProduct->productImages);
        $this->assertTrue((bool) $newProduct->productImages->first()->is_primary);
    }

    public function test_store_product_validates_required_fields_and_unique_sku(): void
    {
        // 1. Thử gửi dữ liệu trống
        $response = $this->actingAs($this->staff)->post(route('admin.products.store'), []);
        $response->assertSessionHasErrors(['name', 'category_id', 'brand_id', 'sku', 'price', 'stock']);

        // 2. Thử trùng mã SKU
        $existing = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'DUPLICATE-SKU-TEST',
        ]);

        $responseDup = $this->actingAs($this->staff)->post(route('admin.products.store'), [
            'name' => 'Máy ảnh khác',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'DUPLICATE-SKU-TEST',
            'price' => 20000000,
            'stock' => 5,
        ]);

        $responseDup->assertSessionHasErrors(['sku']);
    }

    public function test_store_product_validates_original_price_cannot_be_less_than_selling_price(): void
    {
        $response = $this->actingAs($this->staff)->post(route('admin.products.store'), [
            'name' => 'Máy ảnh giảm giá ảo',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'INVALID-PRICE-SKU',
            'price' => 30000000,
            'original_price' => 25000000, // Giá gốc thấp hơn giá bán
            'stock' => 5,
        ]);

        $response->assertSessionHasErrors(['original_price']);
    }

    public function test_staff_can_update_product_details(): void
    {
        $product = Product::factory()->create([
            'name' => 'Nikon Z8 Cũ',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'NIK-Z8-ORIGINAL',
            'price' => 90000000,
            'stock' => 8,
        ]);

        $updateData = [
            'name' => 'Nikon Z8 Mới Cập Nhật',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'NIK-Z8-ORIGINAL',
            'price' => 88000000,
            'stock' => 12,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->staff)->put(route('admin.products.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nikon Z8 Mới Cập Nhật',
            'price' => 88000000,
            'stock' => 12,
        ]);
    }

    public function test_staff_can_delete_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'sku' => 'DELETE-ME-TEST',
        ]);

        $response = $this->actingAs($this->staff)->delete(route('admin.products.destroy', $product->id));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /* =========================================================================
     * 4. MÀN HÌNH QUẢN LÝ KHO & CẬP NHẬT TỒN KHO NHANH (ĐỢT 3)
     * ========================================================================= */

    public function test_staff_can_view_inventory_dashboard_and_filter_low_stock(): void
    {
        // 1. Máy ảnh còn nhiều (an toàn)
        Product::factory()->create([
            'name' => 'Sony Tồn Kho Nhiều',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 20,
        ]);

        // 2. Máy ảnh sắp hết hàng (< 5)
        Product::factory()->create([
            'name' => 'Canon Sắp Hết Hàng',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 3,
        ]);

        // 3. Máy ảnh đã hết hàng (0)
        Product::factory()->create([
            'name' => 'Nikon Đã Hết Hàng',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 0,
        ]);

        // Xem toàn bộ kho
        $response = $this->actingAs($this->staff)->get(route('admin.inventory.index'));
        $response->assertStatus(200);
        $response->assertViewHas(['thongKeKho', 'danhSachSanPham']);

        $thongKe = $response->viewData('thongKeKho');
        $this->assertEquals(3, $thongKe['tong_san_pham']);
        $this->assertEquals(1, $thongKe['sap_het_hang']);
        $this->assertEquals(1, $thongKe['da_het_hang']);
        $this->assertEquals(1, $thongKe['an_toan']);

        // Lọc máy ảnh sắp hết hàng
        $responseLow = $this->actingAs($this->staff)->get(route('admin.inventory.index', ['stock_status' => 'low_stock']));
        $responseLow->assertStatus(200);
        $lowProducts = $responseLow->viewData('danhSachSanPham');
        $this->assertEquals(1, $lowProducts->total());
        $this->assertEquals('Canon Sắp Hết Hàng', $lowProducts->first()->name);

        // Lọc máy ảnh hết hàng
        $responseOut = $this->actingAs($this->staff)->get(route('admin.inventory.index', ['stock_status' => 'out_of_stock']));
        $responseOut->assertStatus(200);
        $outProducts = $responseOut->viewData('danhSachSanPham');
        $this->assertEquals(1, $outProducts->total());
        $this->assertEquals('Nikon Đã Hết Hàng', $outProducts->first()->name);
    }

    public function test_staff_can_quick_update_stock(): void
    {
        $product = Product::factory()->create([
            'name' => 'Fujifilm Cần Nhập Kho',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 2,
        ]);

        $response = $this->actingAs($this->staff)->patch(route('admin.inventory.quick-update', $product->id), [
            'stock' => 25,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(25, $product->fresh()->stock);
    }

    public function test_quick_update_stock_validates_non_negative_number(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->staff)->patch(route('admin.inventory.quick-update', $product->id), [
            'stock' => -5,
        ]);

        $response->assertSessionHasErrors(['stock']);
        $this->assertEquals(10, $product->fresh()->stock);
    }
}
