<?php

namespace App\Modules\Category\Repositories;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * Lấy toàn bộ danh sách danh mục (kèm đếm số sản phẩm và hỗ trợ phân trang)
     */
    public function layTatCaDanhMuc(array $boLoc = [], int $soLuongMoiTrang = 10): LengthAwarePaginator
    {
        $truyVan = Category::query()->withCount('products');

        if (! empty($boLoc['tu_khoa'])) {
            $tuKhoa = trim($boLoc['tu_khoa']);
            $truyVan->where(function ($q) use ($tuKhoa) {
                $q->where('name', 'like', "%{$tuKhoa}%")
                    ->orWhere('slug', 'like', "%{$tuKhoa}%");
            });
        }

        if (isset($boLoc['trang_thai']) && $boLoc['trang_thai'] !== '') {
            $truyVan->where('is_active', (bool) $boLoc['trang_thai']);
        }

        return $truyVan->latest('id')->paginate($soLuongMoiTrang);
    }

    /**
     * Lấy danh sách danh mục đang kích hoạt (để hiển thị trên dropdown sản phẩm)
     */
    public function layDanhSachKichHoat(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Tìm danh mục theo mã định danh (ID)
     */
    public function timTheoId(int|string $maDanhMuc): ?Category
    {
        return Category::withCount('products')->find($maDanhMuc);
    }

    /**
     * Thêm mới danh mục vào cơ sở dữ liệu
     */
    public function taoDanhMuc(array $duLieu): Category
    {
        return Category::create($duLieu);
    }

    /**
     * Cập nhật thông tin danh mục
     */
    public function capNhatDanhMuc(Category|int|string $danhMuc, array $duLieu): Category
    {
        $banGhi = $danhMuc instanceof Category ? $danhMuc : $this->timTheoId($danhMuc);

        if ($banGhi) {
            $banGhi->update($duLieu);
        }

        return $banGhi;
    }

    /**
     * Xóa danh mục khỏi cơ sở dữ liệu
     */
    public function xoaDanhMuc(Category|int|string $danhMuc): bool
    {
        $banGhi = $danhMuc instanceof Category ? $danhMuc : $this->timTheoId($danhMuc);

        return $banGhi ? (bool) $banGhi->delete() : false;
    }
}
