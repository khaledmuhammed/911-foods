<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\api\FaqController;
use \App\Http\Controllers\api\FieldController;
use \App\Http\Controllers\api\MainController;
use \App\Http\Controllers\api\MarketController;
use \App\Http\Controllers\api\ProductController;
use \App\Http\Controllers\api\CategorieController;
use \App\Http\Controllers\api\CouponController;
use \App\Http\Controllers\api\SlideController;
use \App\Http\Controllers\api\GalleryController;
use \App\Http\Controllers\api\FavoriteController;

Route::prefix('/default')->group(function () {
    Route::get('/currency', [MainController::class, 'defaultCurrency']);
    Route::get('/currencyRight', [MainController::class, 'currencyRight']);
});
Route::prefix('/faq')->group(function () {
    Route::get('/', [FaqController::class, 'indexFaq']);
    Route::get('/categorys', [FaqController::class, 'indexCategorys']);
});
Route::prefix('/markets')->group(function () {
    #/api/markets/search?search=
    // Route::get('/search', [MarketController::class, 'search']);
    Route::get('/total', [MarketController::class, 'total']);
    Route::get('/topRated', [MarketController::class, 'topRated']);
    Route::get('/detail/{market:id}', [MarketController::class, 'show']);
    /**
     * api/markets/filter?fields[]=1&fields[]=2&fields[]=3
     * api/markets/filter?price[min]=0&price[max]=20
     * api/markets/filter?rate=3
     */
    Route::get('/filter', [MarketController::class, 'filter']);
    Route::get('/{skip}', [MarketController::class, 'index']);
});
Route::prefix('/products')->group(function () {
    Route::get('/topRated', [ProductController::class, 'topRated']);
});
Route::prefix('/fields')->group(function () {
    Route::get('/', [FieldController::class, 'index']);
    Route::get('/random', [FieldController::class, 'random']);
});
Route::prefix('/category')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);
    Route::get('/random', [CategorieController::class, 'random']);
});
Route::prefix('/coupon')->group(function () {
    Route::get('/check', [CouponController::class, 'check']);
});
Route::prefix('/slides')->group(function () {
    Route::get('/', [SlideController::class, 'index']);
});
Route::prefix('/galleries')->group(function () {
    Route::get('/market/{market:id}', [GalleryController::class, 'marketGallerie']);
});
Route::prefix('/favorites')->group(function () {
    Route::post('/{product_id}/{user_id}', [FavoriteController::class, 'store']);
});

Route::get('payments/paymob/checkout', [MainController::class, 'paymob_checkout']);
// to solve api problem with search on the app
Route::get('market/search/', [MainController::class, 'search']);

