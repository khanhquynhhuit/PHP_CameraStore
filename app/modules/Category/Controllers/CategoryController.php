<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Requests\UpdateCategoryRequest;
use App\Modules\Category\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về danh sách danh mục
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Viết phần trả về view tạo danh mục
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // TODO: Viết phần lưu danh mục mới
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Viết phần trả về xem chi tiết danh mục
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Viết phần trả về form chỉnh sửa danh mục
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        // TODO: Viết phần cập nhật danh mục
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Viết phần xóa danh mục
    }
}
