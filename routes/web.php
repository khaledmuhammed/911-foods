<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\FieldsController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\MarketController;
use \App\Http\Controllers\ReviewController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\Auth\AuthSocialiteController;
use \App\Http\Controllers\Auth\UserController;

Auth::routes();
Route::view('/help', 'pages.help');
Route::view('/privacy-policy', 'pages.privacy-policy');
Route::get('/', [HomeController::class, 'index']);
Route::get('/search', [HomeController::class, 'search']);
Route::get('/category/{category:id}', [CategoryController::class, 'index']);
Route::get('/fields/{field:id}', [FieldsController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

Route::get('/top-products', [ProductController::class, 'top_products'])->name('top_products');


Route::prefix('/markets')->group(function () {
    Route::view('/', 'markets.listing');
    Route::get('/{market:id}', [MarketController::class, 'show']);
});
Route::middleware('auth')->group(function () {
    Route::redirect('/home', '/');
    Route::view('/my-account', 'auth.account.profile');
    Route::get('/my-account/edit/{id}', [UserController::class, 'edit']);
    Route::post('/my-account/update', [UserController::class, 'update']);
    Route::view('/contact-us', 'pages.contactus');
    Route::post('/contact-us/store', [HomeController::class, 'contact_us_store'])->name('contact-us.store');
    Route::view('/wishlist', 'pages.wishlist');
    Route::get('/favorites/product/{skip}', [HomeController::class, 'favoriteProducts']);
    Route::prefix('/markets')->group(function () {
        Route::prefix('/review')->group(function () {
            Route::get('/{market:id}', [ReviewController::class, 'create']);
            Route::post('/{market:id}', [ReviewController::class, 'store'])->name('review');
        });
    });
    Route::prefix('/order')->group(function () {
        Route::get('/market/{market:id}', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store'])->name('order');
        Route::get('/confirm', [OrderController::class, 'confirm']);
        Route::get('/not-confirm', [OrderController::class, 'notConfirm']);
        Route::get('/paymob-callback', [OrderController::class, 'callback']);
    });
});

Route::prefix('/auth/{driver}')->group(function () {
    Route::get('/register', [AuthSocialiteController::class, 'register']);
    Route::get('/login', [AuthSocialiteController::class, 'login']);
    Route::get('/callback', [AuthSocialiteController::class, 'callback']);
});
