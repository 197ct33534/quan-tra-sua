# KẾ HOẠCH CHI TIẾT — Website Đặt Hàng Trà Sữa & Bánh Tráng

> Website bán **trà sữa + bánh tráng trộn + bánh tráng nướng**, cho phép khách đặt hàng online.
> Stack: **Laravel 12 + MySQL + Blade + Bootstrap 5**.

---

## 1. Phạm vi & Quyết định kỹ thuật

| Hạng mục | Lựa chọn |
|----------|----------|
| Framework | Laravel 12 (PHP 8.2) |
| Database | MySQL (`tra_sua_shop`) qua XAMPP |
| Giao diện | Blade + Bootstrap 5 (CDN) + Bootstrap Icons |
| Auth | Custom (đăng ký/đăng nhập/đăng xuất) — **bắt buộc đăng nhập để đặt hàng** |
| Phân quyền | Cột `role` trên `users`: `customer` / `admin` + middleware `admin` |
| Giỏ hàng | Lưu trong **session** |
| Thanh toán | **COD** (thanh toán khi nhận hàng) |
| Upload ảnh SP | `storage/app/public` + `php artisan storage:link` |

---

## 2. Sơ đồ cơ sở dữ liệu

### `users`
`id, name, email (unique), phone, address, password, role [customer|admin], timestamps`

### `categories`
`id, name, slug (unique), description, sort_order, is_active, timestamps`

### `products`
`id, category_id (FK), name, slug (unique), description, price (decimal), image, is_available, has_options (bool — drink có size/topping), sort_order, timestamps`

### `toppings`
`id, name, price (decimal), is_active, timestamps`

### `orders`
`id, order_code (unique), user_id (FK), customer_name, phone, address, note, subtotal, shipping_fee, total, payment_method [cod], status [pending|confirmed|preparing|delivering|completed|cancelled], timestamps`

### `order_items`
`id, order_id (FK), product_id (FK nullable), product_name, unit_price, quantity, size [null|S|M|L], toppings (JSON), options_fee, line_total, timestamps`

**Quan hệ:** Category 1—n Product · User 1—n Order · Order 1—n OrderItem · Product 1—n OrderItem.

---

## 3. Luồng nghiệp vụ

### Khách hàng
1. Xem **Trang chủ** → sản phẩm nổi bật + danh mục.
2. Vào **Menu** → lọc theo danh mục, tìm kiếm.
3. Mở **chi tiết sản phẩm** → (nếu là đồ uống) chọn size + topping → thêm vào giỏ.
4. **Giỏ hàng** → sửa số lượng / xóa.
5. **Đăng nhập / đăng ký** (bắt buộc) → **Checkout** (điền tên, SĐT, địa chỉ, ghi chú).
6. Đặt hàng thành công → sinh `order_code`, xóa giỏ → trang **theo dõi đơn**.
7. **Đơn hàng của tôi** → xem lịch sử + trạng thái.

### Admin (`/admin`)
1. **Dashboard**: số đơn theo trạng thái, doanh thu, đơn mới nhất.
2. **Danh mục**: CRUD.
3. **Sản phẩm**: CRUD + upload ảnh + bật/tắt còn hàng.
4. **Topping**: CRUD.
5. **Đơn hàng**: danh sách, xem chi tiết, cập nhật trạng thái.

---

## 4. Bản đồ Route

### Public / Customer
```
GET  /                         Home
GET  /menu                     Danh sách SP (lọc category, search)
GET  /san-pham/{slug}          Chi tiết SP
GET  /gio-hang                 Xem giỏ
POST /gio-hang/them            Thêm vào giỏ
POST /gio-hang/cap-nhat        Cập nhật số lượng
POST /gio-hang/xoa             Xóa item
GET  /dang-ky | POST           Đăng ký
GET  /dang-nhap | POST         Đăng nhập
POST /dang-xuat                Đăng xuất
-- (auth) --
GET  /thanh-toan               Trang checkout
POST /thanh-toan               Đặt hàng
GET  /don-hang                 Đơn của tôi
GET  /don-hang/{code}          Chi tiết đơn
```

### Admin (auth + middleware `admin`, prefix `/admin`)
```
GET  /admin                    Dashboard
resource categories            CRUD danh mục
resource products              CRUD sản phẩm
resource toppings              CRUD topping
GET  /admin/orders             Danh sách đơn
GET  /admin/orders/{order}     Chi tiết đơn
PUT  /admin/orders/{order}     Cập nhật trạng thái
```

---

## 5. Cấu trúc thư mục chính

```
app/
  Http/
    Controllers/
      Auth/        AuthController
      HomeController, MenuController, ProductController
      CartController, CheckoutController, OrderController
      Admin/       DashboardController, CategoryController,
                   ProductController, ToppingController, OrderController
    Middleware/    AdminMiddleware
  Models/          User, Category, Product, Topping, Order, OrderItem
  Services/        CartService (logic giỏ hàng session)
database/
  migrations/      (6 bảng nghiệp vụ)
  seeders/         AdminUserSeeder, CategorySeeder, ProductSeeder, ToppingSeeder
resources/views/
  layouts/         app.blade.php (storefront), admin.blade.php
  partials/        navbar, footer
  home, menu, products/show
  cart/index, checkout/index
  orders/index, orders/show
  auth/login, auth/register
  admin/  dashboard, categories/*, products/*, toppings/*, orders/*
routes/web.php
```

---

## 6. Các bước thực hiện (thứ tự code)

1. ✅ Scaffold Laravel + cấu hình `.env` MySQL + tạo DB.
2. Migrations + Models (quan hệ, fillable, casts).
3. Seeders: 1 admin, danh mục, ~12 sản phẩm mẫu, topping → `db:seed`.
4. `AdminMiddleware` + Auth (register/login/logout) + views.
5. `CartService` + storefront (home, menu, chi tiết, giỏ, checkout, đơn hàng).
6. Admin panel (dashboard + CRUD + quản lý đơn).
7. Layout Bootstrap, `storage:link`, migrate + seed, chạy `serve` & sửa lỗi.

---

## 7. Tài khoản mặc định sau khi seed

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | `admin@trasua.test` | `password` |
| Khách (demo) | `khach@trasua.test` | `password` |

---

## 8. Cách chạy

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
# Mở http://localhost:8000  — Admin: http://localhost:8000/admin
```
