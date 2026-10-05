<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductRepository;

class ProductService
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    /**
     * Nơi viết các logic nghiệp vụ liên quan đến sản phẩm
     */
    public function listProducts(array $filters = [])
    {
        // TODO: Viết logic lấy danh sách sản phẩm
    }

    public function getProductDetail(int|string $id)
    {
        // TODO: Viết logic lấy chi tiết sản phẩm
    }

    public function createProduct(array $data)
    {
        // TODO: Viết logic nghiệp vụ tạo sản phẩm
    }

    public function updateProduct(int|string $id, array $data)
    {
        // TODO: Viết logic nghiệp vụ cập nhật sản phẩm
    }

    public function deleteProduct(int|string $id)
    {
        // TODO: Viết logic nghiệp vụ xóa sản phẩm (kiểm tra đơn hàng...)
    }
}
