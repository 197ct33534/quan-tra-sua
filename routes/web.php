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
    Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/thanh-toan', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/don-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/don-hang/{code}', [OrderController::class, 'show'])->name('orders.show');
});

// ---------------- Admin ----------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::resource('toppings', AdminToppingController::class)->except('show');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
});
