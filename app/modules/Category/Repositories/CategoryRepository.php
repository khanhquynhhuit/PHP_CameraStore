<?php

namespace App\Modules\Category\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Nơi viết các truy vấn Database liên quan đến danh mục
     */
    public function getAll(array $filters = [])
    {
        // TODO: Viết query lấy danh sách danh mục
    }

    public function findById(int|string $id)
    {
        // TODO: Viết query tìm danh mục theo ID
    }

    public function create(array $data)
    {
        // TODO: Viết query tạo danh mục
    }

    public function update(Category|int|string $category, array $data)
    {
        // TODO: Viết query cập nhật danh mục
    }

    public function delete(Category|int|string $category)
    {
        // TODO: Viết query xóa danh mục
    }
}
