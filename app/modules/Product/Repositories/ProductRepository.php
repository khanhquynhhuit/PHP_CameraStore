<?php

namespace App\Modules\Product\Repositories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * Tìm kiếm và lọc sản phẩm đa tiêu chí kết hợp phân trang (Bắt buộc Eager Load chống lỗi N+1)
     * Hỗ trợ tìm kiếm theo từ khóa tên máy ảnh, SKU, lọc theo danh mục, thương hiệu, khoảng giá và sắp xếp.
     */
    public function timKiemVaLocSanPham(array $boLoc = [], int $soLuongMoiTrang = 9): LengthAwarePaginator
    {
        $truyVan = Product::query()
            ->with(['category', 'brand', 'productImages']);

        // 1. Tìm theo từ khóa (Tên sản phẩm hoặc mã SKU)
        if (!empty($boLoc['tu_khoa'])) {
            $tuKhoa = trim($boLoc['tu_khoa']);
            $truyVan->where(function ($q) use ($tuKhoa) {
                $q->where('name', 'like', "%{$tuKhoa}%")
                  ->orWhere('sku', 'like', "%{$tuKhoa}%");
            });
        }

        // 2. Lọc theo danh mục (ID hoặc Slug)
        if (!empty($boLoc['category_id'])) {
            $truyVan->where('category_id', $boLoc['category_id']);
        } elseif (!empty($boLoc['category_slug'])) {
            $slugDanhMuc = $boLoc['category_slug'];
            $truyVan->whereHas('category', function ($q) use ($slugDanhMuc) {
                $q->where('slug', $slugDanhMuc);
            });
        }

        // 3. Lọc theo thương hiệu (ID hoặc Tên hãng)
        if (!empty($boLoc['brand_id'])) {
            $truyVan->where('brand_id', $boLoc['brand_id']);
        } elseif (!empty($boLoc['brand'])) {
            $tenThuongHieu = $boLoc['brand'];
            $truyVan->whereHas('brand', function ($q) use ($tenThuongHieu) {
                $q->where('name', 'like', "%{$tenThuongHieu}%");
            });
        }

        // 4. Lọc theo khoảng giá (min_price -> max_price)
        if (isset($boLoc['gia_tu']) && is_numeric($boLoc['gia_tu']) && $boLoc['gia_tu'] > 0) {
            $truyVan->where('price', '>=', (float) $boLoc['gia_tu']);
        }

        if (isset($boLoc['gia_den']) && is_numeric($boLoc['gia_den']) && $boLoc['gia_den'] > 0) {
            $truyVan->where('price', '<=', (float) $boLoc['gia_den']);
        }

        // 5. Lọc theo trạng thái kinh doanh
        if (!empty($boLoc['status'])) {
            $truyVan->where('status', $boLoc['status']);
        }

        // 6. Lọc sản phẩm còn hàng
        if (!empty($boLoc['con_hang'])) {
            $truyVan->where('stock', '>', 0);
        }

        // 7. Sắp xếp thứ tự
        $kieuSapXep = $boLoc['sap_xep'] ?? 'moi_nhat';
        switch ($kieuSapXep) {
            case 'gia_tang_dan':
            case 'price_asc':
                $truyVan->orderBy('price', 'asc');
                break;
            case 'gia_giam_dan':
            case 'price_desc':
                $truyVan->orderBy('price', 'desc');
                break;
            case 'ten_a_z':
            case 'name_asc':
                $truyVan->orderBy('name', 'asc');
                break;
            case 'moi_nhat':
            case 'latest':
            default:
                $truyVan->latest('id');
                break;
        }

        return $truyVan->paginate($soLuongMoiTrang);
    }

    /**
     * Bí danh tương thích theo đúng tài liệu phân công: ProductRepository::searchAndFilter()
     */
    public function searchAndFilter(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        return $this->timKiemVaLocSanPham($filters, $perPage);
    }

    /**
     * Bí danh lấy danh sách sản phẩm
     */
    public function layDanhSachSanPham(array $boLoc = [], int $soLuongMoiTrang = 9): LengthAwarePaginator
    {
        return $this->timKiemVaLocSanPham($boLoc, $soLuongMoiTrang);
    }

    /**
     * Tìm kiếm một sản phẩm theo ID kèm toàn bộ quan hệ liên kết (đánh giá, ảnh, danh mục, thương hiệu)
     */
    public function timTheoId(int|string $maSanPham): ?Product
    {
        return Product::query()
            ->with([
                'productImages',
                'category',
                'brand',
                'reviews' => fn($query) => $query->with('user')->latest(),
            ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->find($maSanPham);
    }

    /**
     * Bí danh findById
     */
    public function findById(int|string $id): ?Product
    {
        return $this->timTheoId($id);
    }

    /**
     * Thêm mới sản phẩm máy ảnh vào cơ sở dữ liệu
     */
    public function taoSanPham(array $duLieu): Product
    {
        return Product::create($duLieu);
    }

    /**
     * Cập nhật thông tin sản phẩm
     */
    public function capNhatSanPham(Product|int|string $sanPham, array $duLieu): Product
    {
        $banGhi = $sanPham instanceof Product ? $sanPham : $this->timTheoId($sanPham);

        if ($banGhi) {
            $banGhi->update($duLieu);
        }

        return $banGhi;
    }

    /**
     * Xóa sản phẩm (Soft delete)
     */
    public function xoaSanPham(Product|int|string $sanPham): bool
    {
        $banGhi = $sanPham instanceof Product ? $sanPham : $this->timTheoId($sanPham);

        return $banGhi ? (bool) $banGhi->delete() : false;
    }

    /**
     * Lấy danh sách thương hiệu đang kích hoạt (dùng cho form tạo/sửa & bộ lọc sản phẩm)
     */
    public function layDanhSachThuongHieuDangKichHoat(): Collection
    {
        return Brand::query()
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Lưu hoặc cập nhật ảnh đại diện chính của sản phẩm
     */
    public function luuAnhDaiDien(Product $sanPham, string $duongDanAnh): ProductImage
    {
        // Đặt các ảnh cũ về is_primary = false nếu có
        $sanPham->productImages()->update(['is_primary' => false]);

        return $sanPham->productImages()->create([
            'path' => $duongDanAnh,
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }
}
