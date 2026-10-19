<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ToppingController as AdminToppingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TrackOrderController;
use Illuminate\Support\Facades\Route;

// ---------------- Public / Storefront ----------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// Giỏ hàng
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/ma-giam-gia', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/gio-hang/ma-giam-gia', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/dat-hang/thanh-cong', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/track-order', [TrackOrderController::class, 'create'])->name('track.form');
Route::post('/track-order', [TrackOrderController::class, 'store'])->name('track.store');
Route::get('/track-order/ket-qua', [TrackOrderController::class, 'result'])->name('track.result');
Route::post('/track-order/huy', [TrackOrderController::class, 'cancel'])->name('track.cancel');

// ---------------- Auth ----------------
Route::middleware('guest')->group(function () {
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register']);
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login']);
});
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

// ---------------- Cần đăng nhập (khách hàng) ----------------
Route::middleware('auth')->group(function () {
    Route::get('/don-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/don-hang/{code}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/don-hang/{code}/huy', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// ---------------- Vận hành quán (admin + staff) ----------------
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders/check-new', [AdminOrderController::class, 'checkNew'])->name('orders.check');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/{product}/availability', [AdminProductController::class, 'availability'])->name('products.availability');
    Route::get('/toppings', [AdminToppingController::class, 'index'])->name('toppings.index');
    Route::post('/toppings/{topping}/availability', [AdminToppingController::class, 'availability'])->name('toppings.availability');

    Route::middleware('admin')->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('products', AdminProductController::class)->except(['index', 'show']);
        Route::resource('toppings', AdminToppingController::class)->except(['index', 'show']);
    });
});
