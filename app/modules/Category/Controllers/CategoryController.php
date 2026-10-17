<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Requests\UpdateCategoryRequest;
use App\Modules\Category\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Hiển thị danh sách danh mục sản phẩm (hỗ trợ tìm kiếm & phân trang)
     */
    public function index(Request $request): View
    {
        $boLoc = [
            'tu_khoa' => $request->query('search'),
            'trang_thai' => $request->query('status'),
        ];

        $danhSachDanhMuc = $this->categoryService->layDanhSachDanhMuc($boLoc);

        return view('modules.Category.pages.index', compact('danhSachDanhMuc', 'boLoc'));
    }

    /**
     * Hiển thị giao diện tạo mới danh mục
     */
    public function create(): View
    {
        return view('modules.Category.pages.create');
    }

    /**
     * Tiếp nhận dữ liệu Form Request và lưu danh mục mới
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $duLieuDauVao = $request->validated();

        $this->categoryService->taoDanhMucMoi($duLieuDauVao);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã thêm danh mục mới thành công!');
    }

    /**
     * Xem chi tiết một danh mục
     */
    public function show(string|int $id): View
    {
        $danhMuc = $this->categoryService->layChiTietDanhMuc($id);

        return view('modules.Category.pages.show', compact('danhMuc'));
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit(string|int $id): View
    {
        $danhMuc = $this->categoryService->layChiTietDanhMuc($id);

        return view('modules.Category.pages.edit', compact('danhMuc'));
    }

    /**
     * Tiếp nhận dữ liệu và cập nhật danh mục vào hệ thống
     */
    public function update(UpdateCategoryRequest $request, string|int $id): RedirectResponse
    {
        $duLieuDauVao = $request->validated();

        $this->categoryService->capNhatDanhMuc($id, $duLieuDauVao);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã cập nhật thông tin danh mục thành công!');
    }

    /**
     * Xóa danh mục khỏi hệ thống
     */
    public function destroy(string|int $id): RedirectResponse
    {
        $this->categoryService->xoaDanhMuc($id);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục thành công!');
    }
}
