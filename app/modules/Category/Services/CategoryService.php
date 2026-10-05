<?php

namespace App\Modules\Category\Services;

use App\Modules\Category\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Nơi viết các logic nghiệp vụ liên quan đến danh mục
     */
    public function listCategories(array $filters = [])
    {
        // TODO: Viết logic lấy danh sách danh mục
    }

    public function getCategoryDetail(int|string $id)
    {
        // TODO: Viết logic lấy chi tiết danh mục
    }

    public function createCategory(array $data)
    {
        // TODO: Viết logic tạo danh mục
    }

    public function updateCategory(int|string $id, array $data)
    {
        // TODO: Viết logic cập nhật danh mục
    }

    public function deleteCategory(int|string $id)
    {
        // TODO: Viết logic xóa danh mục
    }
}
