<?php

namespace App\Modules\Product\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
     * Nơi viết các truy vấn Database liên quan đến sản phẩm
     */
    public function getAll(array $filters = [])
    {
        // TODO: Viết query lấy danh sách sản phẩm
    }

    public function findById(int|string $id)
    {
        // TODO: Viết query tìm sản phẩm theo ID
    }

    public function create(array $data)
    {
        // TODO: Viết query tạo mới sản phẩm
    }

    public function update(Product|int|string $product, array $data)
    {
        // TODO: Viết query cập nhật sản phẩm
    }

    public function delete(Product|int|string $product)
    {
        // TODO: Viết query xóa sản phẩm
    }
}
