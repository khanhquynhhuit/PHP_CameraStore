# Hướng Dẫn Cập Nhật Code Những Lần Tiếp Theo (Git Pull)

Tài liệu này áp dụng cho những lần làm việc hàng ngày sau khi máy của bạn **đã thiết lập dự án thành công lần đầu**.

---

## 🚀 Combo lệnh chuẩn sau mỗi lần kéo code

Mỗi khi có code mới trên Git (đồng đội vừa đẩy lên hoặc bạn vừa merge nhánh), hãy mở Terminal tại thư mục `CameraStore` và chạy:

```bash
# 1. Kéo code mới nhất từ nhánh chính về
git pull origin main

# 2. Cập nhật thư viện PHP (nếu có bổ sung package mới)
composer install

# 3. Cập nhật cơ sở dữ liệu (chạy các file migration mới)
php artisan migrate

# 4. Cài đặt package frontend & build lại giao diện (nếu có sửa CSS/JS)
npm install
npm run build

# 5. Dọn dẹp cache cũ của Laravel
php artisan optimize:clear
```

## 🛠️ Một số lệnh hữu ích khi gặp lỗi

| Tình huống | Lệnh xử lý |
| :--- | :--- |
| Giao diện bị vỡ, không nhận file CSS/JS mới | `npm run build` |
| Route báo 404 hoặc không nhận biến môi trường `.env` mới | `php artisan optimize:clear` |
| Không hiển thị được ảnh trong thư mục `storage` | `php artisan storage:link` |
| Bị kẹt lỗi xung đột khi `git pull` vì đang có sửa đổi dở dang | `git stash` -> `git pull origin main` -> `git stash pop` |
