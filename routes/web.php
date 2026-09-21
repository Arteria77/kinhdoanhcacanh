<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController; // Thêm Controller chat Admin
use App\Http\Controllers\User\ChatController as UserChatController;   // Thêm Controller chat User
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Nhóm route quản lý giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::match(['POST', 'PATCH'], '/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');

// Route AJAX tính phí ship GHN (Hỗ trợ đồng thời cả 2 tên route để tránh mọi lỗi gọi nhầm từ Blade)
Route::post('/api/calculate-shipping-fee', [CartController::class, 'calculateShippingFee'])->name('shipping.calculate');
Route::post('/api/calculate-shipping-fee-alias', [CartController::class, 'calculateShippingFee'])->name('api.shipping.fee');

// Route trung gian lấy danh sách Tỉnh/Huyện/Xã chuẩn từ GHN (Giải quyết triệt để lỗi to district not found)
Route::get('/api/ghn/provinces', [CartController::class, 'getProvinces']);
Route::get('/api/ghn/districts', [CartController::class, 'getDistricts']);
Route::get('/api/ghn/wards', [CartController::class, 'getWards']);

// Tuyến đường xác thực email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Liên kết xác thực không hợp lệ.');
    }

    if (!$request->hasValidSignature()) {
        abort(403, 'Liên kết xác thực đã hết hạn hoặc không hợp lệ.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    if (!Auth::check()) {
        Auth::login($user);
    }

    return redirect('/')->with('success', 'Xác thực tài khoản email thành công! Bây giờ bạn đã có thể thoải mái thêm sản phẩm vào giỏ hàng và đặt mua hàng.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route dành cho Khách hàng đã đăng nhập và xác thực email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Trang thông báo đặt hàng thành công / thất bại
    Route::get('/checkout/success/{id}', [OrderController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failed', [OrderController::class, 'failed'])->name('checkout.failed');

    // Route xử lý áp dụng và gỡ mã giảm giá
    Route::post('/coupon/apply', [OrderController::class, 'applyCoupon'])->name('coupon.apply');
    Route::post('/coupon/remove', [OrderController::class, 'removeCoupon'])->name('coupon.remove');

    // Tuyến đường thanh toán và kiểm tra trạng thái SePay
    Route::get('/payment/sepay/{order_id}', [OrderController::class, 'sepayPayment'])->name('payment.sepay');
    Route::get('/payment/sepay/check-status/{order_id}', [OrderController::class, 'checkSepayStatus'])->name('payment.sepay.check');

    // --- Bổ sung Route Live Chat cho User ---
    Route::post('/chat/send', [UserChatController::class, 'send'])->name('user.chat.send');
    Route::get('/chat/messages', [UserChatController::class, 'getMessages'])->name('user.chat.messages');
});

// Route nhận kết quả trả về từ VNPay
Route::get('/payment/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('payment.vnpay.return');

// Route Webhook tự động nhận thông báo biến động số dư từ SePay
Route::post('/api/sepay/webhook', [OrderController::class, 'sepayWebhook'])->name('payment.sepay.webhook');

// ==========================================
// TOÀN BỘ KHU VỰC QUẢN TRỊ ADMIN (FASHU ADMIN)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard & Báo cáo doanh thu
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Quản lý danh mục loài cá (Fish Categories)
    Route::resource('categories', CategoryController::class);

    // 3. Quản lý sản phẩm cá cảnh
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // 4. Quản lý đơn hàng & Vận chuyển GHN
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/create-ghn', [OrderAdminController::class, 'createGhnOrder'])->name('orders.createGhn');

    // 5. Mã giảm giá
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // 6. Quản lý khách hàng
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // --- Bổ sung Route Live Chat cho Admin (Đã khớp chuẩn tên gọi) ---
    Route::get('/chat/users', [AdminChatController::class, 'getUsers'])->name('chat.users');
    Route::get('/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [AdminChatController::class, 'send'])->name('chat.send');
});

// Trang chủ hiển thị danh sách sản phẩm
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');

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
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});