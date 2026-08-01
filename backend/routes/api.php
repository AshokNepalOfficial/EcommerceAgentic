<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/update', [CartController::class, 'update']);
        Route::delete('/remove', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/add', [WishlistController::class, 'add']);
        Route::delete('/remove', [WishlistController::class, 'remove']);
        Route::delete('/clear', [WishlistController::class, 'clear']);
    });
    Route::prefix('checkout')->group(function () {
        Route::post('/place', [CheckoutController::class, 'place']);
    });
});