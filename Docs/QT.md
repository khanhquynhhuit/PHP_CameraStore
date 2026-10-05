# AGENTS.md — Website thương mại điện tử bán máy ảnh

> File này là nguồn sự thật (single source of truth) cho mọi AI agent làm việc trong repo.
> **Đọc toàn bộ file trước khi viết bất kỳ dòng code nào.** Nếu yêu cầu của người dùng mâu thuẫn với file này, hãy nêu rõ mâu thuẫn và hỏi lại, không tự ý bỏ qua

## 1. Quy trình làm việc của agent

1. **Đọc trước, code sau.** Xem các file liên quan trong repo để theo đúng phong cách và cấu trúc hiện có.
2. **Làm từng bước nhỏ**, mỗi lần một nhóm chức năng rõ ràng (một use case hoặc một sprint). Không "làm hết một lượt".
3. Trước khi code một chức năng, **nêu ngắn gọn kế hoạch** (file sẽ tạo/sửa) rồi mới thực hiện.
4. Sau mỗi bước phải **chạy được**: không để repo ở trạng thái lỗi. Chạy `php artisan route:list` / `migrate:status` / `php artisan test` khi liên quan và báo kết quả thật.
5. **Viết test Feature** cho mỗi luồng quan trọng (checkout, đổi trạng thái đơn, phân quyền, CRUD sản phẩm). Mỗi luồng thay thế (A#) và quy tắc nghiệp vụ (BR-#) nên có ít nhất một test.
6. **Không sửa ngoài phạm vi yêu cầu.** Nếu thấy lỗi/điểm cần cải thiện ở chỗ khác, ghi chú lại và đề xuất, đừng tự sửa.
7. **Không bịa.** Nếu thiếu thông tin (ví dụ phiên bản Laravel, tên cột, quy tắc), hỏi lại hoặc nêu rõ giả định.
8. Khi kết thúc mỗi bước, báo cáo: danh sách file đã tạo/sửa, lệnh đã chạy và kết quả, các **TODO còn lại**, và điểm cần người dùng xác nhận.
9. Commit nhỏ, thông điệp rõ ràng theo dạng `feat:`, `fix:`, `test:`, `docs:`, `refactor:` (nếu người dùng yêu cầu dùng Git).

---

## 2. Những điều CẤM

- Cấm viết logic nghiệp vụ (tính tiền, trừ kho, đổi trạng thái) trong Controller hoặc Blade.
- Cấm chuyển trạng thái đơn bằng cách gán thẳng `$order->status = ...` ngoài `OrderStatusService`.
- Cấm xóa vật lý sản phẩm đã phát sinh đơn hàng.
- Cấm tính lại tổng đơn cũ từ giá sản phẩm hiện tại.
- Cấm đưa `role` vào `$fillable` của luồng đăng ký công khai.
- Cấm chỉ kiểm tra quyền ở giao diện mà bỏ qua server.
- Cấm cài thêm package hoặc đổi công nghệ khi chưa được đồng ý.
- Cấm sửa migration đã chạy; cấm chạy `migrate:fresh` nếu người dùng chưa xác nhận đang ở môi trường dev.
- Cấm commit `.env` hoặc dữ liệu nhạy cảm.
- Cấm để lại code chết, `dd()`, `dump()`, `console.log` gỡ lỗi trong sản phẩm cuối.

---

## 3. Định nghĩa "Hoàn thành" (Definition of Done)

Một chức năng chỉ được coi là xong khi:

- [ ] Chạy đúng luồng chính và các luồng thay thế đã đặc tả.
- [ ] Có validation đầy đủ bằng Form Request, thông báo lỗi tiếng Việt.
- [ ] Kiểm tra phân quyền đúng (thử với cả 3 vai trò và guest).
- [ ] Có phân trang/tìm kiếm nếu là danh sách.
- [ ] Có test Feature đi kèm và `php artisan test` xanh.
- [ ] Không còn lỗi N+1 rõ ràng, không còn code gỡ lỗi.
- [ ] Giao diện dùng được trên màn hình nhỏ (Bootstrap responsive).
- [ ] Báo cáo bàn giao theo mục 1.8.

---

