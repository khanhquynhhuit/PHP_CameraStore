<?php

use App\Modules\Cart\Controllers\CartController;
use App\Modules\Category\Controllers\CategoryController;
use App\Modules\Order\Controllers\AdminOrderController;
use App\Modules\Order\Controllers\CheckoutController;
use App\Modules\Order\Controllers\OrderController;
use App\Modules\Product\Controllers\AdminProductController;
use App\Modules\Product\Controllers\ProductController;
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
Route::get('/dashboard', function () {
    return view('modules.Dashboard.pages.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Khu vực khách hàng (Customer)
Route::middleware('auth')->group(function () {
    // Quản lý Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Giỏ hàng
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
    });

    // Checkout & Đơn hàng cá nhân
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});

// Khu vực Quản trị (Admin & Staff)
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý Sản phẩm
    Route::apiResource('products', AdminProductController::class);

    // Quản lý Danh mục
    Route::apiResource('categories', CategoryController::class);

    // Quản lý Đơn hàng
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Quản lý Người dùng (chỉ Admin)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', AdminUserController::class);
    });
});

require __DIR__ . '/auth.php';
