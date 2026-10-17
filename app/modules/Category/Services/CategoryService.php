<?php

namespace App\Modules\Category\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Category;
use App\Modules\Category\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Lấy danh sách phân trang danh mục kèm lọc và tìm kiếm
     */
    public function layDanhSachDanhMuc(array $boLoc = [], int $soLuongMoiTrang = 10): LengthAwarePaginator
    {
        return $this->categoryRepository->layTatCaDanhMuc($boLoc, $soLuongMoiTrang);
    }

    /**
     * Lấy toàn bộ danh mục đang kích hoạt (dùng cho các dropdown chọn danh mục)
     */
    public function layDanhSachDanhMucDangKichHoat(): Collection
    {
        return $this->categoryRepository->layDanhSachKichHoat();
    }

    /**
     * Lấy thông tin chi tiết một danh mục theo ID (ném ResourceNotFoundException nếu không thấy)
     */
    public function layChiTietDanhMuc(int|string $maDanhMuc): Category
    {
        $danhMuc = $this->categoryRepository->timTheoId($maDanhMuc);

        if (!$danhMuc) {
            throw new ResourceNotFoundException('Danh mục được yêu cầu không tồn tại trong hệ thống.');
        }

        return $danhMuc;
    }

    /**
     * Tạo mới danh mục sản phẩm kèm tự sinh đường dẫn tĩnh (Slug)
     */
    public function taoDanhMucMoi(array $duLieu): Category
    {
        $tenDanhMuc = trim($duLieu['name']);
        $duongDanTinh = !empty($duLieu['slug']) ? Str::slug($duLieu['slug']) : Str::slug($tenDanhMuc);

        $duLieuCanLuu = [
            'name' => $tenDanhMuc,
            'slug' => $duongDanTinh,
            'is_active' => isset($duLieu['is_active']) ? (bool) $duLieu['is_active'] : true,
        ];

        return $this->categoryRepository->taoDanhMuc($duLieuCanLuu);
    }

    /**
     * Cập nhật thông tin danh mục
     */
    public function capNhatDanhMuc(int|string $maDanhMuc, array $duLieu): Category
    {
        $danhMucHienTai = $this->layChiTietDanhMuc($maDanhMuc);

        $tenDanhMuc = trim($duLieu['name']);
        $duongDanTinh = !empty($duLieu['slug']) ? Str::slug($duLieu['slug']) : Str::slug($tenDanhMuc);

        $duLieuCanCapNhat = [
            'name' => $tenDanhMuc,
            'slug' => $duongDanTinh,
            'is_active' => isset($duLieu['is_active']) ? (bool) $duLieu['is_active'] : true,
        ];

        return $this->categoryRepository->capNhatDanhMuc($danhMucHienTai, $duLieuCanCapNhat);
    }

    /**
     * Xóa danh mục với kiểm tra ràng buộc toàn vẹn dữ liệu
     */
    public function xoaDanhMuc(int|string $maDanhMuc): bool
    {
        $danhMuc = $this->layChiTietDanhMuc($maDanhMuc);

        // Quy tắc nghiệp vụ: Tuyệt đối không cho xóa nếu danh mục đang chứa sản phẩm
        if ($danhMuc->products()->count() > 0) {
            throw new BusinessException('Không thể xóa danh mục này vì hiện đang có sản phẩm thuộc danh mục!');
        }

        return $this->categoryRepository->xoaDanhMuc($danhMuc);
    }
}
