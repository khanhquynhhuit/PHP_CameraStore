# Quy Trình Đẩy Code Lên Git & Làm Việc Nhóm (Feature Branch & Pull Request)

Tài liệu này quy định quy trình làm việc nhóm chuẩn với Git cho dự án **CameraStore**:
- ❌ **Tuyệt đối KHÔNG đẩy code trực tiếp lên nhánh `main`** (`git push origin main`).
- ✅ **Mỗi thành viên tự tạo nhánh (branch) riêng** khi làm tính năng hoặc sửa lỗi.
- ✅ **Mọi thay đổi muốn đưa vào `main` đều phải thông qua Pull Request (PR)** trên GitHub để kiểm tra code.

---

## 📌 Tổng quan quy trình làm việc

```text
[main mới nhất] ──► (Tạo nhánh mới) ──► [feature/xxx]
                                            │
                                      (Code & Commit)
                                            │
                                      (Push lên GitHub)
                                            │
[main] ◄─── (Duyệt & Merge) ◄─── [Tạo Pull Request]
```

---

## 🚀 Các bước thực hiện chi tiết

### Bước 1: Luôn cập nhật nhánh `main` mới nhất trước khi làm việc mới
Mỗi khi bắt đầu một ngày làm việc hoặc nhận tính năng mới, hãy lấy code mới nhất về máy:

```bash
# Chuyển về nhánh main
git checkout main

# Kéo code mới nhất từ GitHub về
git pull origin main
```

---

### Bước 2: Tạo nhánh riêng cho tính năng của bạn
Tạo một nhánh mới từ nhánh `main` sạch sẽ:

```bash
# Cú pháp: git checkout -b <tên-nhánh>
git checkout -b feature/gio-hang
```

> **Quy ước đặt tên nhánh dễ quản lý:**
> - Thêm tính năng mới: `feature/<tên-tính-năng>` (ví dụ: `feature/gio-hang`, `feature/thanh-toan-vnpay`, `feature/quan-ly-don-hang`)
> - Sửa lỗi: `fix/<tên-lỗi>` (ví dụ: `fix/loi-dang-nhap`, `fix/loi-tinh-tien`)

---

### Bước 3: Code và kiểm tra
Thực hiện viết code, sửa giao diện hoặc thêm logic. Đảm bảo chạy thử trên máy hoạt động tốt và không bị lỗi cú pháp trước khi commit.

---

### Bước 4: Kiểm tra và Commit code vào nhánh của bạn
Sau khi hoàn thành phần việc:

```bash
# 1. Kiểm tra xem mình đã sửa/thêm những file nào
git status

# 2. Thêm các thay đổi vào khu vực chuẩn bị commit
git add .

# 3. Tạo commit với nội dung mô tả rõ ràng việc đã làm
git commit -m "Them chuc nang them san pham vao gio hang"
```

> **Lưu ý:** Không commit các file chứa mật khẩu cá nhân như `.env` (file `.gitignore` đã chặn sẵn, nhưng vẫn nên chú ý).

---

### Bước 5: Đẩy nhánh của bạn lên GitHub
Đẩy nhánh bạn vừa tạo lên remote repository (GitHub):

```bash
# Cú pháp: git push -u origin <tên-nhánh-của-bạn>
git push -u origin feature/gio-hang
```
*(Từ lần push thứ 2 trên cùng nhánh này, bạn chỉ cần gõ `git push`)*

---

### Bước 6: Tạo Pull Request (PR) trên GitHub

1. Truy cập vào trang GitHub của dự án: `https://github.com/khanhquynhhuit/CameraStore`.
2. Bạn sẽ thấy một thanh thông báo màu vàng xuất hiện với nút **Compare & pull request** cho nhánh bạn vừa push ➔ Bấm vào nút đó.
3. Nếu không thấy thanh vàng:
   - Bấm vào tab **Pull requests** ➔ Bấm **New pull request**.
   - Tại ô **base**: Chọn `main`.
   - Tại ô **compare**: Chọn nhánh của bạn (ví dụ `feature/gio-hang`).
4. Điền tiêu đề và mô tả ngắn gọn:
   - **Title:** Tóm tắt việc đã làm (ví dụ: `[Feature] Hoàn thiện chức năng giỏ hàng`).
   - **Description:** Mô tả chi tiết những gì đã làm, các màn hình bị ảnh hưởng, có file migration mới hay không để đồng đội biết.
5. Bấm nút **Create pull request**.

---

### Bước 7: Review và Hợp nhất code (Merge PR)

- Gửi link Pull Request cho các thành viên trong nhóm (hoặc trưởng nhóm) để vào xem qua code.
- Nếu không có xung đột (conflicts) và code đã đạt yêu cầu: Bấm nút **Merge pull request** ➔ chọn **Confirm merge**.
- Sau khi merge xong, code từ nhánh của bạn đã chính thức nằm trong nhánh `main` chung của cả nhóm.

---

### Bước 8: Dọn dẹp máy cá nhân sau khi xong việc

Sau khi PR đã được merge vào `main` trên GitHub, bạn quay lại máy mình:

```bash
# 1. Chuyển về nhánh main
git checkout main

# 2. Kéo code vừa merge về lại máy mình
git pull origin main

# 3. (Tùy chọn) Xóa nhánh cũ đã xong để đỡ rác máy
git branch -d feature/gio-hang
```

---

## 💡 Mẹo xử lý khi đang làm mà nhánh `main` có người khác vừa cập nhật

Nếu bạn đang làm việc trên nhánh `feature/xxx` mà nhánh `main` vừa có bạn khác merge code mới vào, bạn nên đồng bộ code mới nhất của `main` vào nhánh của mình để tránh xung đột sau này:

```bash
# 1. Lưu lại commit trên nhánh hiện tại của bạn
git add .
git commit -m "Luu tam cong viec dang lam"

# 2. Cập nhật main mới nhất
git checkout main
git pull origin main

# 3. Trộn code mới từ main vào nhánh đang làm của bạn
git checkout feature/gio-hang
git merge main
```
*(Nếu có xung đột - conflict, mở VS Code/IDE lên giải quyết các đoạn xung đột, sau đó `git add .` và `git commit -m "Merge main into branch"`)*
