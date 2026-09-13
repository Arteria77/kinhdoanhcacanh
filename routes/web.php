<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\CartController;

// Nhóm route quản lý giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');

// Route AJAX tính phí ship GHN
Route::post('/api/calculate-shipping-fee', [CartController::class, 'calculateShippingFee'])->name('api.shipping.fee');

// Route trung gian lấy danh sách Tỉnh/Huyện/Xã chuẩn từ GHN (Giải quyết triệt để lỗi to district not found)
Route::get('/api/ghn/provinces', [CartController::class, 'getProvinces']);
Route::get('/api/ghn/districts', [CartController::class, 'getDistricts']);
Route::get('/api/ghn/wards', [CartController::class, 'getWards']);

// Route dành cho Khách hàng (cần đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

// Route dành cho Admin quản lý đơn hàng (Đã bổ sung route xem chi tiết)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Trang chủ hiển thị danh sách sản phẩm
Route::get('/', [HomeController::class, 'index']);

// Trang tìm kiếm sản phẩm
Route::get('/search', [HomeController::class, 'search'])->name('shop.search');

// Trang chi tiết sản phẩm cho khách hàng
Route::get('/products/{id}', [HomeController::class, 'show'])->name('products.show');

// Nhóm route cho khách chưa đăng nhập
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Nhóm route yêu cầu đã đăng nhập
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.show');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Nhóm route quản lý dành riêng cho Admin (Sản phẩm & Khách hàng)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý sản phẩm
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Quản lý khách hàng
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});