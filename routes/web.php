<?php

use App\Modules\Cart\Controllers\CartController;
use App\Modules\Category\Controllers\CategoryController;
use App\Modules\Dashboard\Controllers\DashboardController;
use App\Modules\Order\Controllers\AdminOrderController;
use App\Modules\Order\Controllers\CheckoutController;
use App\Modules\Order\Controllers\OrderController;
use App\Modules\Product\Controllers\AdminProductController;
use App\Modules\Product\Controllers\ProductController;
use App\Modules\Review\Controllers\ReviewController;
use App\Modules\User\Controllers\AdminUserController;
use App\Modules\User\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Trang chủ & Sản phẩm công khai
Route::get('/', function () {
    return view('modules.Home.pages.index');
})->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Dashboard sau đăng nhập
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Khu vực khách hàng (Customer)
Route::middleware('auth')->group(function () {
    // Quản lý Profile & Địa chỉ nhận hàng
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::patch('/profile/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');

    // Giỏ hàng
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    });

    // Checkout & Đơn hàng cá nhân
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/received', [OrderController::class, 'received'])->name('received');
    });
});

// Khu vực Vận hành Cửa hàng (Dành riêng cho Staff)
Route::middleware(['auth', 'role:staff'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý Sản phẩm
    Route::resource('products', AdminProductController::class);

    // Quản lý kho máy ảnh (Đợt 3: Lọc sắp hết hàng & cập nhật nhanh tồn kho)
    Route::get('inventory', [AdminProductController::class, 'inventory'])->name('inventory.index');
    Route::patch('inventory/{id}/quick-update', [AdminProductController::class, 'quickUpdateStock'])->name('inventory.quick-update');

    // Quản lý Danh mục
    Route::resource('categories', CategoryController::class);

    // Quản lý Đơn hàng
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Khu vực Quản trị Hệ thống (Dành riêng cho Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::patch('users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::resource('users', AdminUserController::class);
});
Route::middleware(['auth', 'role:customer'])
    ->post(
        '/products/{product}/reviews',
        [ReviewController::class, 'store']
    )
    ->name('reviews.store');
require __DIR__.'/auth.php';
