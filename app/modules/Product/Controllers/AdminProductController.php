<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về danh sách sản phẩm quản trị
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Viết phần trả về view tạo sản phẩm
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // TODO: Viết phần lưu sản phẩm mới
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Viết phần trả về xem chi tiết sản phẩm
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Viết phần trả về form chỉnh sửa sản phẩm
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        // TODO: Viết phần cập nhật sản phẩm
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Viết phần xóa sản phẩm
    }
}
