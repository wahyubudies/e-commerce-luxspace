<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGalleryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/details/{slug}', [FrontendController::class, 'detail'])->name('details');

Route::middleware(['auth:sanctum', 'verified'])->group( function() {                
    Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
    Route::post('/addcart/{id}', [FrontendController::class, 'addCart'])->name('addcart');
    Route::get('/checkout/success', [FrontendController::class, 'success'])->name('checkout.success');

    Route::group([ 'prefix' => 'dashboard', 'as' => 'dashboard.'], function() {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::middleware('admin')->group( function() {   
            Route::resource('product', ProductController::class);
            Route::resource('product.gallery', ProductGalleryController::class)->shallow()->only(['index', 'create', 'store', 'destroy']);
            Route::resource('transaction', TransactionController::class)->only(['index', 'edit', 'update', 'show']);
            Route::resource('user', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
        });
    });            
});
