<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use App\Models\Products;
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
    $products = Products::all();
    return view('welcome', compact('products'));
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
// Orders
Route::prefix('orders')->middleware('auth')->group(function () {
    Route::get('/', [OrdersController::class, 'index'])->name('index.orders');
    Route::put('/{order}/update-status', [OrdersController::class, 'updateStatus'])->name('update_status.orders');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('show.orders');
});

// Visitor (cart & checkout)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity']);
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/order', [CheckoutController::class, 'store'])->name('orders.store');

