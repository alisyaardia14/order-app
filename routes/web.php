<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'home'])->name('home');
Route::get('/menu', [OrderController::class, 'menu'])->name('menu');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/cart/items', [OrderController::class, 'addToCart'])->name('cart.items.store');
Route::delete('/cart/items/{menuId}', [OrderController::class, 'removeFromCart'])->name('cart.items.destroy');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth('admin')->check() ? 'admin.payments.index' : 'admin.login');
    })->name('index');

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [AdminMenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/payments', [AdminOrderController::class, 'payments'])->name('payments.index');
        Route::get('/revenue/{period?}', [AdminOrderController::class, 'revenueReport'])->name('revenue.index');
        Route::get('/revenue/{period?}/pdf', [AdminOrderController::class, 'downloadRevenuePdf'])->name('revenue.pdf');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });
});
