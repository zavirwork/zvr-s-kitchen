<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Products
Route::prefix('products')->middleware('auth')->group(function () {
    Route::get('/', [ProductsController::class, 'index'])->name('index.products');
    Route::get('/create', [ProductsController::class, 'create'])->name('create.products');
    Route::post('/store', [ProductsController::class, 'store'])->name('store.products');
    Route::get('/{product}/edit', [ProductsController::class, 'edit'])->name('edit.products');
    Route::put('/{product}', [ProductsController::class, 'update'])->name('update.products');
    Route::delete('/{product}/delete', [ProductsController::class, 'destroy'])->name('destroy.products');
});
Route::prefix('orders')->middleware('auth')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->name('index.orders');
    Route::put('/{order}/update-status', [OrdersController::class, 'updateStatus'])->name('update_status.orders');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('show.orders');
});

Route::get('/checkout', function () {
    $products = \App\Models\Products::all();
    return view('checkout', compact('products'));
})->name('checkout.page');

Route::post('/order/store', [OrdersController::class, 'store'])->name('orders.store');
