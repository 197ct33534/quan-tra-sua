# KẾ HOẠCH CHI TIẾT — Quản Trà Sữa

> **Website đặt hàng trà sữa, bánh tráng trộn, bánh tráng nướng online.**
> Stack: Laravel 12 · PHP 8.3 · SQLite (local) / MySQL (production) · Blade · Tailwind CSS (storefront) · Bootstrap 5 (admin)

---

## Trạng thái hiện tại

| Hạng mục | Trạng thái |
|-----------|-----------|
| Cài đặt dependencies (`vendor/`) | ✅ Hoàn thành |
| File `.env` | ✅ Đã tạo (SQLite, local) |
| Database migrations + seed | ✅ Hoàn thành (SQLite) |
| Storage link | ✅ Hoàn thành |
| Dev server | ✅ Đang chạy tại **http://127.0.0.1:8080** |

---

## Thông tin truy cập local

| URL | Mô tả |
|-----|-------|
| **http://127.0.0.1:8080** | Trang chủ khách hàng |
| **http://127.0.0.1:8080/menu** | Menu sản phẩm |
| **http://127.0.0.1:8080/ho-so** | Hồ sơ cá nhân |
| **http://127.0.0.1:8080/doi-mat-khau** | Đổi mật khẩu |
| **http://127.0.0.1:8080/admin** | Trang quản trị Admin |
| **http://127.0.0.1:8080/dang-nhap** | Đăng nhập |

### Tài khoản mặc định (sau seed)

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| **Admin** | `admin@trasua.test` | `password` |
| **Khách hàng** | `khach@trasua.test` | `password` |

### Lệnh khởi động lại server (mỗi lần mở mới)

```powershell
# Chạy trong PowerShell tại thư mục dự án
$PHP = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
cd "D:\nghia\laptrinh\quan-tra-sua"
& $PHP artisan serve --host=127.0.0.1 --port=8080
```

---

## Cấu trúc Database

### Bảng đã tạo

| Bảng | Mô tả |
|------|-------|
| `users` | id, name, email, phone, address, password, role (customer/admin) |
| `categories` | id, name, slug, description, sort_order, is_active |
| `products` | id, category_id, name, slug, description, price, image, is_available, has_options, sort_order |
| `toppings` | id, name, price, is_active |
| `orders` | id, order_code, user_id, customer_name, phone, address, note, subtotal, shipping_fee, total, payment_method (cod), status |
| `order_items` | id, order_id, product_id, product_name, unit_price, quantity, size (S/M/L), toppings (JSON), options_fee, line_total |
| `sessions`, `cache`, `jobs` | Laravel defaults |

### Dữ liệu mẫu sau seed

- **2 users**: admin + khách hàng demo
- **3 danh mục**: Trà Sữa, Bánh Tráng Trộn, Bánh Tráng Nướng
- **13 sản phẩm** (chưa có ảnh thực)
- **6 toppings**: Trân châu đen, Thạch dừa, Pudding, v.v.

---

## Các Chức Năng Đã Hoàn Thành ✅

### A. Storefront (Khách hàng)

#### A1. Trang chủ (`GET /`)
- [x] Hiển thị banner/hero giới thiệu shop
- [x] Sản phẩm nổi bật (featured)
- [x] Danh sách sản phẩm theo danh mục
- [x] Cart badge hiển thị số lượng trên navbar

#### A2. Trang Menu (`GET /menu`)
- [x] Lọc sản phẩm theo danh mục (query param `?category=slug`)
- [x] Tìm kiếm theo tên sản phẩm (`?search=keyword`)
- [x] Hiển thị thẻ sản phẩm (ảnh, tên, giá, nút thêm giỏ)
- [x] Placeholder khi không có ảnh (`placehold.co`)

#### A3. Chi tiết sản phẩm (`GET /san-pham/{slug}`)
- [x] Thông tin đầy đủ sản phẩm
- [x] Chọn **size** S/M/L (nếu `has_options = true`) → phụ thu +5k/+10k VND
- [x] Chọn **topping** (checkbox nhiều lựa chọn)
- [x] Nút thêm vào giỏ hàng
- [x] Sản phẩm liên quan (cùng danh mục)

#### A4. Giỏ hàng (`GET /gio-hang`)
- [x] Xem tất cả sản phẩm trong giỏ (session-based)
- [x] Cập nhật số lượng từng item
- [x] Xóa sản phẩm khỏi giỏ
- [x] Tổng tiền tạm tính
- [x] Nút "Tiến hành đặt hàng"

#### A5. Đăng ký / Đăng nhập / Đăng xuất
- [x] Form đăng ký (`GET/POST /dang-ky`) — name, email, phone, address, password
- [x] Form đăng nhập (`GET/POST /dang-nhap`)
- [x] Đăng xuất (`POST /dang-xuat`)
- [x] Redirect về trang checkout sau khi đăng nhập (nếu đến từ checkout)

#### A6. Checkout (`GET/POST /thanh-toan`) — Yêu cầu đăng nhập
- [x] Form điền thông tin giao hàng (tên, SĐT, địa chỉ, ghi chú)
- [x] Hiển thị tóm tắt đơn hàng
- [x] Phí ship cố định: **15.000 VND**
- [x] Thanh toán: **COD** (tiền mặt khi nhận)
- [x] Tạo `order_code` dạng `TS260603GW03`
- [x] Xóa giỏ hàng sau đặt thành công
- [x] Redirect về trang chi tiết đơn hàng

#### A7. Quản lý đơn hàng của tôi
- [x] Danh sách đơn hàng (`GET /don-hang`) — chỉ đơn của user hiện tại
- [x] Chi tiết đơn (`GET /don-hang/{code}`) — sản phẩm, giá, trạng thái

---

### B. Admin Panel (`/admin`) — Yêu cầu role=admin

#### B1. Dashboard (`GET /admin`)
- [x] Thống kê số đơn theo từng trạng thái
- [x] Tổng doanh thu (đơn completed)
- [x] Danh sách đơn mới nhất

#### B2. Quản lý Danh mục (`/admin/categories`)
- [x] Danh sách danh mục + sort_order + trạng thái
- [x] Thêm danh mục (name, slug auto-gen, description, sort_order, is_active)
- [x] Sửa danh mục
- [x] Xóa danh mục

#### B3. Quản lý Sản phẩm (`/admin/products`)
- [x] Danh sách sản phẩm (tên, danh mục, giá, trạng thái)
- [x] Thêm sản phẩm (name, category, description, price, image upload, is_available, has_options)
- [x] Sửa sản phẩm
- [x] Xóa sản phẩm
- [x] Upload ảnh lưu vào `storage/app/public/products/`

#### B4. Quản lý Topping (`/admin/toppings`)
- [x] Danh sách topping
- [x] Thêm / Sửa / Xóa topping
- [x] Bật/tắt topping (is_active)

#### B5. Quản lý Đơn hàng (`/admin/orders`)
- [x] Danh sách đơn (filter theo trạng thái, tìm theo mã đơn)
- [x] Chi tiết đơn (thông tin KH, sản phẩm, tổng tiền)
- [x] Cập nhật trạng thái đơn: `pending → confirmed → preparing → delivering → completed / cancelled`

---

### C. Hệ thống / Kỹ thuật

- [x] Custom middleware `AdminMiddleware` (kiểm tra role)
- [x] Blade directive `@vnd(amount)` — format VND (vd: `25.000 ₫`)
- [x] View Composer — cart badge count tự động inject vào mọi view
- [x] `CartService` — quản lý giỏ hàng trong session (thêm, cập nhật, xóa, tính tổng)
- [x] Trang lỗi tùy chỉnh: 403, 404, 419, 500, 503
- [x] 21 automated tests (PHPUnit): CartService, OrderFlow, AdminAccess

---

## Các Chức Năng CẦN LÀM / CẦN CẢI THIỆN 🔧

### Ưu tiên CAO — Ảnh hưởng đến UX/hoạt động

#### 1. Ảnh sản phẩm thực tế
**Hiện trạng:** 13 sản phẩm seed đều không có ảnh thực, dùng `placehold.co`
**Việc cần làm:**
- [ ] Upload ảnh thực cho từng sản phẩm qua Admin Panel, HOẶC
- [ ] Chạy lệnh fetch ảnh tự động từ Pexels (cần API key miễn phí):
  ```bash
  # 1. Đăng ký API key tại https://www.pexels.com/api/
  # 2. Thêm vào .env: PEXELS_API_KEY=your_key_here
  # 3. Chạy:
  php artisan products:fetch-images
  ```

#### 2. Giao diện Storefront — Đồng bộ Bootstrap/Tailwind
**Hiện trạng:** `KE-HOACH.md` đề xuất Bootstrap 5, nhưng storefront đang dùng Tailwind CDN. Vite + Tailwind v4 đã setup nhưng chưa dùng.
**Việc cần làm:**
- [ ] Quyết định 1 hướng: Bootstrap 5 hoặc Tailwind v4
- [ ] Nếu dùng Tailwind v4 qua Vite: thêm `@vite(...)` vào `layouts/app.blade.php` và chạy `npm run dev`
- [ ] Nếu giữ CDN: xóa `vite.config.js` + `resources/css/app.css` cho gọn

#### 3. Responsive Mobile
**Hiện trạng:** Chưa test kỹ trên mobile
**Việc cần làm:**
- [ ] Kiểm tra navbar collapse trên mobile
- [ ] Kiểm tra trang chi tiết sản phẩm (size/topping selector)
- [ ] Kiểm tra trang giỏ hàng và checkout trên màn hình nhỏ

---

### Ưu tiên TRUNG — Tính năng bổ sung

#### 4. Khách hàng huỷ đơn ✅ XONG
- [x] Nút "Huỷ đơn" trên trang `orders/show.blade.php` (chỉ hiện khi status = `pending`)
- [x] Route: `DELETE /don-hang/{code}/huy`
- [x] Controller: `OrderController@cancel` — đổi status thành `cancelled`

#### 5. Trang hồ sơ cá nhân ✅ XONG
- [x] Route: `GET /ho-so` (hiện thông tin), `PUT /ho-so` (cập nhật)
- [x] View: form sửa name, phone, address với 2 tab
- [x] Controller: `ProfileController@show`, `@update`

#### 6. Đổi mật khẩu ✅ XONG
- [x] Route: `GET /doi-mat-khau`, `PUT /doi-mat-khau`
- [x] Controller: xác nhận mật khẩu cũ → cập nhật mới
- [x] View: form đổi mật khẩu (tab thứ 2 trong profile)

#### 7. Tìm kiếm nâng cao trên Menu ✅ XONG
- [x] Lọc kết hợp: danh mục + tên + khoảng giá
- [x] Sort: giá tăng/giảm, mới nhất
- [x] Phân trang (`paginate(12)`) khi sản phẩm nhiều

#### 8. Thống kê Admin nâng cao ✅ XONG
- [x] Biểu đồ doanh thu 7 ngày gần nhất (bar chart CSS thuần)
- [x] Top 5 sản phẩm bán chạy nhất
- [ ] Xuất báo cáo Excel/CSV (chưa làm)

---

### Ưu tiên THẤP — Polish & Production

#### 9. Thông báo email đơn hàng
**Hiện trạng:** `MAIL_MAILER=log` (chỉ ghi log, không gửi thật)
**Việc cần làm:**
- [ ] Tạo `OrderConfirmationMail` Mailable
- [ ] Gửi email xác nhận đặt hàng cho khách
- [ ] Gửi email thông báo khi admin cập nhật trạng thái
- [ ] Cấu hình SMTP thật (Gmail, Mailgun, v.v.)

#### 10. FormRequest Validation
**Hiện trạng:** Validation inline trong controller
**Việc cần làm:**
- [ ] Tạo `StoreOrderRequest`, `StoreProductRequest`, v.v.
- [ ] Di chuyển logic validation vào FormRequest classes
- [ ] Thêm Vietnamese validation messages (`lang/vi/validation.php`)

#### 11. README thực tế
**Hiện trạng:** README mặc định của Laravel
**Việc cần làm:**
- [ ] Viết README với hướng dẫn cài đặt, cấu hình, chạy dự án
- [ ] Screenshot giao diện
- [ ] Danh sách tính năng

#### 12. Tối ưu production
**Hiện trạng:** Chỉ chạy local
**Việc cần làm:**
- [ ] Cấu hình `.env` production (MySQL, SMTP, APP_DEBUG=false)
- [ ] `php artisan config:cache && route:cache && view:cache`
- [ ] Setup HTTPS
- [ ] Chọn hosting (shared hosting, VPS, v.v.)

#### 13. Đơn vị test bổ sung
**Hiện trạng:** 21 tests cơ bản, các file `ExampleTest.php` placeholder vẫn còn
**Việc cần làm:**
- [ ] Xóa/thay thế ExampleTest placeholder
- [ ] Thêm test cho: đăng ký user, trang sản phẩm, trang menu, checkout flow đầy đủ
- [ ] Test admin CRUD (category, product, topping)

---

## Cấu trúc Files quan trọng

```
app/
  Http/Controllers/
    Auth/AuthController.php          ← Đăng ký, đăng nhập, đăng xuất
    HomeController.php               ← Trang chủ
    MenuController.php               ← Menu sản phẩm
    ProductController.php            ← Chi tiết sản phẩm
    CartController.php               ← Giỏ hàng
    CheckoutController.php           ← Đặt hàng
    OrderController.php              ← Đơn hàng khách hàng
    Admin/DashboardController.php    ← Admin dashboard
    Admin/CategoryController.php     ← CRUD danh mục
    Admin/ProductController.php      ← CRUD sản phẩm
    Admin/ToppingController.php      ← CRUD topping
    Admin/OrderController.php        ← Quản lý đơn hàng
  Http/Middleware/AdminMiddleware.php
  Models/                            ← User, Category, Product, Topping, Order, OrderItem
  Services/CartService.php           ← Logic giỏ hàng session

resources/views/
  layouts/
    app.blade.php                    ← Layout storefront (Tailwind CDN)
    admin.blade.php                  ← Layout admin (Bootstrap 5)
  home.blade.php, menu.blade.php
  products/show.blade.php
  cart/index.blade.php
  checkout/index.blade.php
  orders/{index,show}.blade.php
  auth/{login,register}.blade.php
  admin/{dashboard, categories/*, products/*, toppings/*, orders/*}

routes/web.php                       ← Tất cả routes
database/
  migrations/                        ← 8 bảng
  seeders/DatabaseSeeder.php         ← Dữ liệu mẫu
  backups/tra_sua_shop.sql           ← Backup MySQL
```

---

## Môi trường Local (Laragon)

| Thành phần | Phiên bản | Path |
|-----------|-----------|------|
| PHP | 8.3.30 (Laragon) | `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe` |
| MySQL | 8.4.3 (Laragon) | `C:\laragon\bin\mysql\mysql-8.4.3-winx64` |
| Database (local) | SQLite | `database/database.sqlite` (auto-tạo) |
| Node.js | 18.17.1 (system) | — |

> **Ghi chú:** Hiện tại dùng SQLite để đơn giản. Nếu muốn dùng MySQL của Laragon, mở Laragon → Start All → tạo DB `tra_sua_shop` → sửa `.env` thành `DB_CONNECTION=mysql` + credentials.

---

*Tạo ngày: 07/06/2026 | Dự án: quan-tra-sua (Laravel 12)*
