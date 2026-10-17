<?php

namespace App\Modules\Product\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Product;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected CategoryService $categoryService
    ) {}

    /**
     * Tìm kiếm và lọc sản phẩm đa tiêu chí (Từ khóa, Danh mục, Thương hiệu, Khoảng giá, Sắp xếp)
     */
    public function timKiemVaLocSanPham(array $boLoc = [], int $soLuongMoiTrang = 9): LengthAwarePaginator
    {
        // Chuẩn hóa các tham số đầu vào
        $boLocChuanHoa = [
            'tu_khoa' => $boLoc['keyword'] ?? $boLoc['search'] ?? $boLoc['tu_khoa'] ?? null,
            'category_id' => $boLoc['category_id'] ?? null,
            'category_slug' => $boLoc['category_slug'] ?? $boLoc['category'] ?? null,
            'brand_id' => $boLoc['brand_id'] ?? null,
            'brand' => $boLoc['brand'] ?? null,
            'gia_tu' => $boLoc['min_price'] ?? $boLoc['gia_tu'] ?? null,
            'gia_den' => $boLoc['max_price'] ?? $boLoc['gia_den'] ?? null,
            'status' => $boLoc['status'] ?? null,
            'con_hang' => $boLoc['in_stock'] ?? $boLoc['con_hang'] ?? null,
            'sap_xep' => $boLoc['sort'] ?? $boLoc['sap_xep'] ?? 'moi_nhat',
        ];

        return $this->productRepository->timKiemVaLocSanPham($boLocChuanHoa, $soLuongMoiTrang);
    }

    /**
     * Bí danh tìm kiếm theo đề bài
     */
    public function searchAndFilter(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        return $this->timKiemVaLocSanPham($filters, $perPage);
    }

    /**
     * Lấy danh sách sản phẩm phân trang
     */
    public function layDanhSachSanPham(array $boLoc = [], int $soLuongMoiTrang = 9): LengthAwarePaginator
    {
        return $this->timKiemVaLocSanPham($boLoc, $soLuongMoiTrang);
    }

    /**
     * Lấy thông tin chi tiết một sản phẩm theo ID (Ném ResourceNotFoundException nếu không thấy)
     */
    public function layChiTietSanPham(int|string $maSanPham): Product
    {
        $sanPham = $this->productRepository->timTheoId($maSanPham);

        if (!$sanPham) {
            throw new ResourceNotFoundException('Sản phẩm máy ảnh không tồn tại trong hệ thống.');
        }

        return $sanPham;
    }

    /**
     * Bí danh tương thích cho việc lấy chi tiết sản phẩm
     */
    public function getProductDetail(int|string $id): Product
    {
        return $this->layChiTietSanPham($id);
    }

    /**
     * Chuẩn bị dữ liệu danh mục & thương hiệu bổ trợ cho giao diện tạo/sửa & bộ lọc sản phẩm
     */
    public function layDuLieuForm(): array
    {
        return [
            'danhSachDanhMuc' => $this->categoryService->layDanhSachDanhMucDangKichHoat(),
            'danhSachThuongHieu' => $this->productRepository->layDanhSachThuongHieuDangKichHoat(),
        ];
    }

    /**
     * Tạo mới sản phẩm máy ảnh kèm xử lý upload file ảnh vào storage/app/public/products
     */
    public function taoSanPhamMoi(array $duLieu, ?UploadedFile $tepAnh = null): Product
    {
        return DB::transaction(function () use ($duLieu, $tepAnh) {
            $tenSanPham = trim($duLieu['name']);
            $duongDanTinh = Str::slug($tenSanPham);

            // Kiểm tra ràng buộc giá niêm yết
            if (isset($duLieu['original_price']) && $duLieu['original_price'] !== '') {
                if ((float)$duLieu['original_price'] < (float)$duLieu['price']) {
                    throw new BusinessException('Giá gốc niêm yết không được nhỏ hơn giá bán thực tế!');
                }
            } else {
                $duLieu['original_price'] = null;
            }

            $duLieuCanLuu = [
                'category_id' => $duLieu['category_id'],
                'brand_id' => $duLieu['brand_id'],
                'sku' => strtoupper(trim($duLieu['sku'])),
                'name' => $tenSanPham,
                'slug' => $duongDanTinh,
                'price' => $duLieu['price'],
                'original_price' => $duLieu['original_price'],
                'stock' => $duLieu['stock'] ?? 0,
                'warranty_months' => $duLieu['warranty_months'] ?? 12,
                'status' => $duLieu['status'] ?? 'active',
                'description' => $duLieu['description'] ?? null,
                'created_by' => auth()->id(),
            ];

            $sanPhamMoi = $this->productRepository->taoSanPham($duLieuCanLuu);

            // Xử lý upload file ảnh vào thư mục storage/app/public/products
            if ($tepAnh && $tepAnh->isValid()) {
                $duongDanLuu = $tepAnh->store('products', 'public');
                $this->productRepository->luuAnhDaiDien($sanPhamMoi, $duongDanLuu);
            }

            return $sanPhamMoi;
        });
    }

    /**
     * Cập nhật thông tin sản phẩm và cập nhật ảnh đại diện nếu có tải lên mới
     */
    public function capNhatSanPham(int|string $maSanPham, array $duLieu, ?UploadedFile $tepAnh = null): Product
    {
        return DB::transaction(function () use ($maSanPham, $duLieu, $tepAnh) {
            $sanPhamHienTai = $this->layChiTietSanPham($maSanPham);

            $tenSanPham = trim($duLieu['name']);
            $duongDanTinh = Str::slug($tenSanPham);

            // Kiểm tra ràng buộc giá
            if (isset($duLieu['original_price']) && $duLieu['original_price'] !== '') {
                if ((float)$duLieu['original_price'] < (float)$duLieu['price']) {
                    throw new BusinessException('Giá gốc niêm yết không được nhỏ hơn giá bán thực tế!');
                }
            } else {
                $duLieu['original_price'] = null;
            }

            $duLieuCanCapNhat = [
                'category_id' => $duLieu['category_id'],
                'brand_id' => $duLieu['brand_id'],
                'sku' => strtoupper(trim($duLieu['sku'])),
                'name' => $tenSanPham,
                'slug' => $duongDanTinh,
                'price' => $duLieu['price'],
                'original_price' => $duLieu['original_price'],
                'stock' => $duLieu['stock'] ?? 0,
                'warranty_months' => $duLieu['warranty_months'] ?? 12,
                'status' => $duLieu['status'] ?? 'active',
                'description' => $duLieu['description'] ?? null,
            ];

            $sanPhamDaCapNhat = $this->productRepository->capNhatSanPham($sanPhamHienTai, $duLieuCanCapNhat);

            // Nếu người dùng tải lên ảnh mới
            if ($tepAnh && $tepAnh->isValid()) {
                $duongDanLuu = $tepAnh->store('products', 'public');
                $this->productRepository->luuAnhDaiDien($sanPhamDaCapNhat, $duongDanLuu);
            }

            return $sanPhamDaCapNhat;
        });
    }

    /**
     * Xóa sản phẩm máy ảnh
     */
    public function xoaSanPham(int|string $maSanPham): bool
    {
        $sanPham = $this->layChiTietSanPham($maSanPham);

        return $this->productRepository->xoaSanPham($sanPham);
    }
}
