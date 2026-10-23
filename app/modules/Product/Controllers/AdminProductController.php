<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Hiển thị danh sách sản phẩm quản trị (kèm tìm kiếm, lọc đa tiêu chí và phân trang)
     */
    public function index(Request $request): View
    {
        $boLoc = [
            'keyword' => $request->query('keyword') ?? $request->query('search'),
            'category_id' => $request->query('category_id'),
            'brand_id' => $request->query('brand_id'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'status' => $request->query('status'),
            'sort' => $request->query('sort', 'moi_nhat'),
        ];

        $danhSachSanPham = $this->productService->timKiemVaLocSanPham($boLoc, 10);
        $duLieuBoTro = $this->productService->layDuLieuForm();

        return view('modules.Product.pages.index', array_merge([
            'products' => $danhSachSanPham,
            'danhSachSanPham' => $danhSachSanPham,
            'boLoc' => $boLoc,
        ], $duLieuBoTro));
    }

    /**
     * Hiển thị giao diện form thêm mới máy ảnh
     */
    public function create(): View
    {
        $duLieuBoTro = $this->productService->layDuLieuForm();

        return view('modules.Product.pages.create', $duLieuBoTro);
    }

    /**
     * Tiếp nhận dữ liệu Form Request và tạo mới sản phẩm kèm upload ảnh
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $duLieuDauVao = $request->validated();
        $tepAnh = $request->file('image');

        $this->productService->taoSanPhamMoi($duLieuDauVao, $tepAnh);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm mới sản phẩm máy ảnh thành công!');
    }

    /**
     * Xem chi tiết sản phẩm
     */
    public function show(string $id): View
    {
        $sanPham = $this->productService->layChiTietSanPham($id);

        return view('modules.Product.pages.show', [
            'product' => $sanPham,
            'sanPham' => $sanPham,
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa thông tin sản phẩm
     */
    public function edit(string $id): View
    {
        $sanPham = $this->productService->layChiTietSanPham($id);
        $duLieuBoTro = $this->productService->layDuLieuForm();

        return view('modules.Product.pages.edit', array_merge([
            'sanPham' => $sanPham,
            'product' => $sanPham,
        ], $duLieuBoTro));
    }

    /**
     * Cập nhật thông tin sản phẩm và cập nhật ảnh mới nếu có
     */
    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $duLieuDauVao = $request->validated();
        $tepAnh = $request->file('image');

        $this->productService->capNhatSanPham($id, $duLieuDauVao, $tepAnh);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật thông tin sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm khỏi hệ thống
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->productService->xoaSanPham($id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm thành công!');
    }

    /**
     * Màn hình Quản lý kho máy ảnh (Đợt 3: Lọc máy ảnh sắp hết hàng, cập nhật nhanh tồn kho)
     */
    public function inventory(Request $request): View
    {
        $boLoc = [
            'keyword' => $request->query('keyword') ?? $request->query('search'),
            'category_id' => $request->query('category_id'),
            'brand_id' => $request->query('brand_id'),
            'stock_status' => $request->query('stock_status', 'all'),
            'sort' => $request->query('sort', 'stock_asc'),
        ];

        $thongKeKho = $this->productService->layThongKeKhoHang();
        $danhSachSanPham = $this->productService->timKiemVaLocSanPham($boLoc, 15);
        $duLieuBoTro = $this->productService->layDuLieuForm();

        return view('modules.Product.pages.inventory', array_merge([
            'products' => $danhSachSanPham,
            'danhSachSanPham' => $danhSachSanPham,
            'thongKeKho' => $thongKeKho,
            'boLoc' => $boLoc,
        ], $duLieuBoTro));
    }

    /**
     * Cập nhật nhanh số lượng tồn kho (Đợt 3: Inline quick stock update)
     */
    public function quickUpdateStock(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ], [
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
        ]);

        $sanPham = $this->productService->capNhatNhanhTonKho($id, (int) $validated['stock']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật tồn kho máy ảnh [{$sanPham->name}] thành {$sanPham->stock} chiếc!",
                'product' => [
                    'id' => $sanPham->id,
                    'stock' => $sanPham->stock,
                ],
            ]);
        }

        return redirect()->back()->with('success', "Đã cập nhật tồn kho máy ảnh [{$sanPham->name}] thành {$sanPham->stock} chiếc!");
    }
}
