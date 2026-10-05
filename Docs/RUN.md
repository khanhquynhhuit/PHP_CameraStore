# TEAM GUIDE — Hướng dẫn cho thành viên nhóm

Tài liệu này gồm 3 phần:

1. **Cài đặt và chạy dự án** sau khi vừa lấy code về.
2. **Quy trình Git** làm việc nhóm để không đè code nhau.
3. **Trình tự code một chức năng**: vào thư mục nào, tạo file nào, theo thứ tự nào.

> Đọc kèm `AGENTS.md` (quy tắc kỹ thuật và nghiệp vụ của dự án). Mọi lệnh bên dưới viết cho **PowerShell trên Windows**.

---

## PHẦN 1. CÀI ĐẶT VÀ CHẠY DỰ ÁN SAU KHI LẤY CODE VỀ

### 1.1. Cài sẵn trên máy (làm một lần)

| Công cụ | Yêu cầu | Kiểm tra |
|---|---|---|
| PHP | 8.2 trở lên (đủ để chạy bản Laravel của dự án) | `php -v` |
| Composer | Bản 2.x | `composer -V` |
| Node.js + npm | Bản LTS | `node -v` và `npm -v` |
| MySQL | 8.0.16 trở lên (hoặc MariaDB 10.2+) | Mở phpMyAdmin hoặc HeidiSQL |
| Git | Bản mới | `git --version` |

Cách nhanh nhất trên Windows: cài **Laragon** (có sẵn PHP, MySQL, Composer, Node). Sau khi cài, mở terminal của Laragon hoặc PowerShell.

Bật các extension PHP cần thiết trong `php.ini` (bỏ dấu `;` đầu dòng): `extension=fileinfo`, `extension=mbstring`, `extension=openssl`, `extension=pdo_mysql`, `extension=zip`, `extension=intl`. Laragon thường đã bật sẵn.

### 1.2. Các bước build (làm theo đúng thứ tự)

```powershell
# 1. Lấy code về (thay link bằng link repo của nhóm)
git clone <LINK_REPO> camera-shop
cd camera-shop

# 2. Cài thư viện PHP
composer install

# 3. Tạo file cấu hình môi trường từ file mẫu
Copy-Item .env.example .env

# 4. Tạo khóa ứng dụng
php artisan key:generate
```

**Bước 5: tạo database rồi sửa `.env`.**

Tạo database rỗng tên `camera_shop` (utf8mb4) bằng phpMyAdmin/HeidiSQL, hoặc chạy trong MySQL:

```sql
CREATE DATABASE camera_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Mở file `.env` và sửa các dòng sau cho đúng máy của bạn:

```
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=camera_shop
DB_USERNAME=root
DB_PASSWORD=
```

(`DB_PASSWORD` để trống nếu MySQL của bạn không đặt mật khẩu. Laragon mặc định là trống.)

```powershell
# 6. Tạo bảng và nạp dữ liệu mẫu
php artisan migrate:fresh --seed

# 7. Liên kết thư mục ảnh upload
php artisan storage:link

# 8. Cài và build giao diện (JS/CSS)
npm install
npm run build
```

> Muốn code giao diện và thấy thay đổi ngay: dùng `npm run dev` (để cửa sổ đó mở), và mở **một cửa sổ PowerShell thứ hai** để chạy server.

```powershell
# 9. Chạy server
php artisan serve
```

Mở trình duyệt vào `http://localhost:8000`.

### 1.3. Tài khoản mẫu (chỉ dùng trên máy dev)

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Admin | `admin@camera.test` | `password` |
| Staff | `staff@camera.test` | `password` |
| Customer | `customer@camera.test` | `password` |

Đăng nhập thử cả 3 tài khoản để chắc chắn mỗi vai trò vào đúng khu vực của mình.

### 1.4. Mỗi lần `git pull` xong, cần làm gì?

Chỉ chạy những lệnh tương ứng với thứ đã thay đổi:

| Nếu bản kéo về có thay đổi... | Chạy |
|---|---|
| `composer.json` / `composer.lock` | `composer install` |
| `package.json` / `package-lock.json` | `npm install` rồi `npm run build` |
| Thư mục `database/migrations` (migration mới) | `php artisan migrate` |
| Seeder/Factory (cần dữ liệu mẫu mới) | `php artisan migrate:fresh --seed` (xóa sạch dữ liệu dev, hỏi nhóm trước nếu bạn đang có dữ liệu tự nhập) |
| `config/`, `routes/`, hoặc thấy lỗi lạ | `php artisan optimize:clear` |
| File trong `resources/js`, `resources/css` | `npm run build` (hoặc đang chạy `npm run dev` thì không cần) |

Lệnh chạy kiểm tra nhanh sau mỗi lần pull: `php artisan route:list` và `php artisan test`.

### 1.5. Lỗi thường gặp

| Lỗi | Nguyên nhân và cách xử lý |
|---|---|
| `Failed opening required ... vendor/autoload.php` | Chưa chạy `composer install` |
| `No application encryption key has been specified` | Chưa chạy `php artisan key:generate` |
| `SQLSTATE[HY000] [1049] Unknown database` | Chưa tạo database `camera_shop` hoặc sai tên trong `.env` |
| `SQLSTATE[HY000] [1045] Access denied` | Sai `DB_USERNAME` / `DB_PASSWORD` trong `.env` |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL chưa bật (bật trong Laragon/XAMPP) |
| `Vite manifest not found` | Chưa chạy `npm run build` (hoặc `npm run dev`) |
| Trang trắng, giao diện mất style | Chạy `npm run build`, rồi `php artisan optimize:clear` |
| Ảnh sản phẩm không hiển thị | Chưa `php artisan storage:link`; nếu báo quyền symlink trên Windows, mở PowerShell bằng **Run as administrator** hoặc bật Developer Mode |
| `Class "X" not found` | Chạy `composer dump-autoload` |
| `Duplicate column` / `Table already exists` khi migrate | Dữ liệu cũ lệch với migration mới. Trên máy dev dùng `php artisan migrate:fresh --seed` |
| Lỗi khóa ngoại khi migrate (`Cannot add foreign key constraint`) | Sai thứ tự timestamp migration (bảng cha phải có timestamp nhỏ hơn bảng con) |
| `Your lock file does not contain a compatible set of packages` | Sai phiên bản PHP hoặc thiếu extension; kiểm tra `php -v` và `php.ini` |

Nếu vẫn lỗi: chụp **toàn bộ thông báo lỗi** (không chỉ dòng đầu) gửi vào nhóm.

---

## PHẦN 2. QUY TRÌNH GIT CỦA NHÓM

### 2.1. Nhánh

| Nhánh | Mục đích | Ai được đẩy trực tiếp |
|---|---|---|
| `main` | Bản ổn định để nộp/demo | Chỉ trưởng nhóm, qua merge |
| `develop` | Nhánh tích hợp chung | Qua Pull Request |
| `feature/<tên-chức-năng>` | Mỗi người một chức năng | Chủ nhánh |

Ví dụ tên nhánh: `feature/admin-product-crud`, `feature/cart-ajax`, `feature/checkout`, `feature/order-status`.

### 2.2. Quy trình làm một chức năng

```powershell
# Trước khi bắt đầu: cập nhật develop
git checkout develop
git pull

# Tạo nhánh riêng cho chức năng
git checkout -b feature/admin-product-crud

# ... code, chạy thử, commit nhỏ nhiều lần ...
git add .
git commit -m "feat(product): them migration va model Product"

# Trước khi đẩy: gộp code mới nhất của develop vào nhánh mình
git fetch origin
git merge origin/develop

# Chạy lại test, đảm bảo build vẫn ổn
php artisan test

# Đẩy lên và tạo Pull Request vào develop
git push -u origin feature/admin-product-crud
```

### 2.3. Quy ước commit

Dạng `loại(phạm-vi): mô tả ngắn`:

- `feat`: thêm chức năng; `fix`: sửa lỗi; `test`: thêm test; `refactor`: tái cấu trúc; `docs`: tài liệu; `style`: định dạng.
- Ví dụ: `feat(checkout): them CheckoutService co transaction`, `fix(cart): sua tinh sai tong tien`.

### 2.4. Không bao giờ commit

- `.env` (chứa mật khẩu máy bạn), thư mục `vendor/`, `node_modules/`, `storage/*.key`, ảnh upload trong `storage/app/public`, file cấu hình IDE cá nhân. Các mục này đã nằm trong `.gitignore` mặc định của Laravel, **không tự ý xóa chúng khỏi `.gitignore`**.
- Nếu `.env.example` cần thêm biến mới, hãy cập nhật `.env.example` (không chứa giá trị bí mật) và thông báo cho nhóm.