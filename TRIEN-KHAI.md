# TRIỂN KHAI — Các bước để hoàn thiện & chạy website Trà Sữa

> Stack: **Laravel 12 (PHP 8.2) + MySQL (XAMPP) + Blade**. Storefront dùng **Tailwind CDN**, admin dùng **Bootstrap CDN** → **không cần `npm`/vite build**.
>
> Database đã chốt: **MySQL** (`tra_sua_shop`) — `.env` đã cấu hình sẵn.

---

## 📊 Hiện trạng (đã kiểm tra thực tế)

Source **gần như hoàn chỉnh**. Code đã viết đủ logic; việc còn lại là **chạy + kiểm thử + hoàn thiện + triển khai**.

| Thành phần | Trạng thái |
|---|---|
| Controllers (13 file, client + admin) | ✅ Đầy đủ logic |
| Models (6), relationships, casts, scopes | ✅ Xong |
| CartService (giỏ session, size/topping/phí) | ✅ Xong |
| AdminMiddleware, Auth (đăng ký/nhập/xuất) | ✅ Xong |
| Migrations (6 bảng nghiệp vụ) | ✅ Schema đầy đủ |
| DatabaseSeeder (admin + khách + 3 danh mục + 14 SP + 6 topping) | ✅ Xong |
| Views Blade (28 file) | ✅ Xong |
| `.env` (APP_KEY set, MySQL `tra_sua_shop`) | ✅ Sẵn sàng |
| Command `products:fetch-images` (Pexels) | ✅ Xong (cần API key) |
| `public/storage` symlink | ✅ Đã tạo |
| `vendor/` (PHP deps) | ✅ Có |
| `composer` / `mysql` CLI trong PATH | ❌ Không thấy |
| **DB đã migrate dữ liệu nghiệp vụ** | ❌ **Chưa** |
| Tests | ⚠️ Chỉ 2 placeholder |

> Việc duy nhất khiến web "chưa chạy" là **chưa migrate + seed dữ liệu vào MySQL**.

---

> ⏱️ **GĐ 0–2 đã hoàn tất & verify ngày 2026-06-03** (xem mục cuối file).

## ✅ GIAI ĐOẠN 0 — Chuẩn bị môi trường

- [x] **Bật MySQL (MariaDB 10.4 của XAMPP)** — đã khởi động `mysqld`, port 3306 OPEN.
- [x] **Database `tra_sua_shop`** — đã tồn tại sẵn trong data dir.
- [x] Kiểm tra kết nối: `php artisan migrate:status` → OK.
- [ ] *(Composer không có trong PATH — bỏ qua được vì `vendor/` đã có.)*

---

## ✅ GIAI ĐOẠN 1 — Khởi tạo dữ liệu (bước khiến web "sống dậy")

- [x] `php artisan migrate:fresh --seed` → 8 bảng, nạp 2 user, 3 danh mục, **13 sản phẩm**, 6 topping.
- [x] Symlink `public/storage` → OK.
- [ ] **(Tùy chọn — CHƯA làm) Ảnh sản phẩm:** hiện 0/13 SP có ảnh (đang dùng placeholder `placehold.co`). lấy API key tại pexels.com/api → điền `PEXELS_API_KEY` trong `.env` → chạy:
  ```bash
  php artisan products:fetch-images --all
  ```
  *(Bỏ qua: SP dùng ảnh placeholder — kiểm tra view hiển thị đẹp khi thiếu ảnh.)*

---

## ✅ GIAI ĐOẠN 2 — Chạy & kiểm thử (E2E) — ĐÃ VERIFY

- [x] `php artisan serve` → `http://127.0.0.1:8000` (200).

**Luồng khách hàng (verify qua HTTP, có session + CSRF):**
- [x] Trang chủ / Menu / Chi tiết SP / Giỏ hàng → 200
- [x] Bắt buộc đăng nhập checkout → 302 về login khi chưa auth
- [x] Đăng nhập → thêm SP (size M, qty 2) vào giỏ → checkout 200
- [x] Đặt COD → sinh `order_code` **TS260603GW03**, redirect trang theo dõi đơn
- [x] Tính tiền đúng: 60.000 (SP) + 15.000 (ship) = **75.000đ**
- [x] "Đơn hàng của tôi" + chi tiết đơn → 200

**Luồng admin** (`admin@trasua.test` / `password`):
- [x] Dashboard / Categories / Products / Toppings / Orders → 200
- [x] Đơn hàng hiển thị trong danh sách + chi tiết → 200
- [x] Đổi trạng thái đơn → `confirmed` (lưu DB OK)
- [x] Khách thường vào `/admin` → **403** (middleware chặn đúng)
- [ ] *Còn lại test thủ công trên trình duyệt: upload ảnh SP, lọc/tìm kiếm trực quan.*

---

## ✅ GIAI ĐOẠN 3 — Sửa lỗi & hoàn thiện — ĐÃ LÀM

- [x] **Trang lỗi 404 / 403 / 419 / 500 / 503 tùy biến** đồng bộ giao diện storefront (`resources/views/errors/`) — verify 404 & 403 render đúng.
- [x] Trạng thái rỗng (giỏ trống, chưa có đơn, menu không kết quả) — đã có sẵn trong view.
- [x] Format tiền VND qua directive `@vnd`; phí size/topping verify đúng qua test.
- [ ] Chuẩn hoá thêm thông báo validation tiếng Việt (tùy chọn).
- [ ] Kiểm tra responsive mobile thủ công trên trình duyệt.

---

## ✅ GIAI ĐOẠN 4 — Chất lượng & độ tin cậy — ĐÃ LÀM

- [x] **Test CartService** (`tests/Feature/CartServiceTest.php`, 10 test): phí size M/L, topping, gộp dòng, cập nhật, xoá, clear.
- [x] **Test luồng đặt hàng** (`tests/Feature/OrderFlowTest.php`): chặn guest, thêm giỏ, đặt đơn + xoá giỏ, validation, xem đơn.
- [x] **Test phân quyền admin** (`tests/Feature/AdminAccessTest.php`): guest→login, customer→403, admin→200, đổi trạng thái đơn.
- [x] **`php artisan test` → 21 passed (55 assertions).**
- [ ] Tách validation inline → FormRequest (tùy chọn refactor).
- [ ] Rà soát index DB (slug đã unique).

---

## ✅ GIAI ĐOẠN 5 — Triển khai production

- [ ] `.env` prod: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` thật, DB/mail thật
- [ ] Tối ưu: `php artisan config:cache route:cache view:cache`
- [ ] Bảo mật: CSRF (đã có `@csrf`), HTTPS, quyền `storage/`, không commit `.env`
- [ ] Deploy VPS/hosting (Apache/Nginx + PHP 8.2 + MySQL): `migrate --force` + `storage:link` + `db:seed`

---

## Tài khoản mặc định sau seed

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Admin | `admin@trasua.test` | `password` |
| Khách demo | `khach@trasua.test` | `password` |

## Lệnh chạy nhanh

```bash
# Sau khi bật MySQL + tạo DB tra_sua_shop
php artisan migrate:fresh --seed
php artisan storage:link        # nếu cần
php artisan serve
# http://localhost:8000  — Admin: http://localhost:8000/admin
```
