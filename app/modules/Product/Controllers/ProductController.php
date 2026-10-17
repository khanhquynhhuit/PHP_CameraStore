<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Modules\Review\Services\ReviewService;
use Illuminate\View\View;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductService $productService,
        protected ReviewService $reviewService
    ) {}
    /**
     * Hiển thị danh sách sản phẩm công khai dành cho khách hàng (Tìm kiếm, Lọc đa tiêu chí & Phân trang 9 sản phẩm/trang)
     */
    public function index(Request $request): View
    {
        $boLoc = [
            'keyword' => $request->query('keyword') ?? $request->query('search'),
            'category_id' => $request->query('category_id'),
            'category' => $request->query('category'),
            'brand_id' => $request->query('brand_id'),
            'brand' => $request->query('brand'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'sort' => $request->query('sort', 'moi_nhat'),
            'in_stock' => $request->query('in_stock'),
            'status' => 'active', // Chỉ hiển thị sản phẩm đang kinh doanh trên storefront
        ];

        // Phân trang 9 sản phẩm / trang theo chuẩn rubric môn học
        $danhSachSanPham = $this->productService->timKiemVaLocSanPham($boLoc, 9);
        $duLieuBoTro = $this->productService->layDuLieuForm();

        return view('modules.Product.pages.index', array_merge([
            'products' => $danhSachSanPham,
            'danhSachSanPham' => $danhSachSanPham,
            'boLoc' => $boLoc,
        ], $duLieuBoTro));
    }

    /**
     * Hiển thị trang chi tiết một sản phẩm
     */
    public function show(Request $request, string $id): View
    {
        $sanPham = $this->productService->layChiTietSanPham($id);

        $reviewableOrders = collect();
        $user = $request->user();

        if ($user && $user->isCustomer()) {
            $reviewableOrders = $this->reviewService->getReviewableOrders(
                $user->id,
                $sanPham->id
            );
        }

        return view('modules.Product.pages.show', [
            'product' => $sanPham,
            'sanPham' => $sanPham,
            'reviewableOrders' => $reviewableOrders,
        ]);
    }
}
