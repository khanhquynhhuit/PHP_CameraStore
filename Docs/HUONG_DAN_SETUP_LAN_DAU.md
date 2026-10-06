# Hướng Dẫn Setup Dự Án Lần Đầu (Clone Mới)

Tài liệu này hướng dẫn chi tiết các bước thiết lập và khởi chạy dự án **CameraStore** khi bạn mới tải (clone) mã nguồn từ Git về máy tính.

---

## Yêu cầu môi trường
- **PHP:** `>= 8.3` (Kích hoạt sẵn trong Laragon)
- **Composer:** Phiên bản mới nhất
- **Node.js & npm:** Khuyến nghị Node `>= 18.x`
- **Cơ sở dữ liệu:** MySQL (trên Laragon / XAMPP)

---

## Các bước thực hiện

### Bước 1: Clone dự án về máy
Mở Terminal / PowerShell tại thư mục muốn lưu dự án (ví dụ `C:\laragon\www` hoặc `D:\...\www`), chạy:

```bash
git clone <URL_REPO_CUA_BAN>
cd CameraStore
```

---

### Bước 2: Cài đặt các thư viện PHP (Composer)
Tải toàn bộ dependencies của Laravel vào thư mục `vendor/`:

```bash
composer install
```

---

### Bước 3: Tạo file cấu hình môi trường `.env`
Nhân bản file cấu hình mẫu `.env.example`:

- **Trên Windows (PowerShell / CMD):**
  ```powershell
  copy .env.example .env
  ```
- **Trên macOS / Linux:**
  ```bash
  cp .env.example .env
  ```

---

### Bước 4: Tạo APP_KEY cho ứng dụng
Sinh khóa bí mật mã hóa cho Laravel:

```bash
php artisan key:generate
```

---

### Bước 5: Cấu hình Cơ sở dữ liệu (Database)

1. Mở phần mềm quản lý MySQL (Laragon / phpMyAdmin / HeidiSQL / DBeaver).
2. Tạo một database mới tên là: `camerastore` (Bảng mã: `utf8mb4_unicode_ci`).
3. Mở file `.env` vừa tạo ở Bước 3, tìm và cập nhật các dòng kết nối Database như sau:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camerastore
DB_USERNAME=root
DB_PASSWORD=
```
*(Nếu MySQL của bạn có mật khẩu, hãy điền vào `DB_PASSWORD`)*

---

### Bước 6: Khởi tạo bảng và nạp dữ liệu mẫu (Seeders)
Chạy lệnh migrate kèm nạp dữ liệu mẫu:

```bash
php artisan migrate --seed
```

> **Dữ liệu mẫu sau khi nạp bao gồm:**
> - Danh mục sản phẩm, thương hiệu (Sony, Canon,...), danh sách máy ảnh, phụ kiện.
> - Mã giảm giá, địa chỉ và đơn hàng mẫu.
> - **Tài khoản đăng nhập kiểm thử:**
>   - **Admin:** `admin@camera.test` | Mật khẩu: `password`
>   - **Staff:** `staff@camera.test` | Mật khẩu: `password`
>   - **Khách hàng:** `customer@camera.test` | Mật khẩu: `password`

---

### Bước 7: Tạo liên kết thư mục hình ảnh (Storage Link)
Dự án lưu ảnh sản phẩm tại `storage/app/public/products`, bạn cần tạo symbolic link sang `public/storage`:

```bash
php artisan storage:link
```

---

### Bước 8: Cài đặt thư viện Frontend và Build giao diện
Dự án sử dụng Tailwind CSS, Vite và Alpine.js:

```bash
# Cài đặt các gói npm
npm install

# Build tài nguyên CSS / JS cho môi trường chạy thử
npm run build
```

---

## Khởi chạy dự án

### Cách 1: Chạy song song cả Backend và Frontend (Hot-reload cho Dev)
```bash
composer run dev
```
*(Lệnh này tự động kích hoạt cả `php artisan serve` và Vite `npm run dev`)*

### Cách 2: Sử dụng máy chủ ảo Laragon
Nếu bạn dùng Laragon, truy cập thẳng trình duyệt qua domain ảo:
```
http://camerastore.test
```
*(Lưu ý: Nhớ bấm **Reload Apache/Nginx** trên Laragon nếu chưa nhận domain)*
