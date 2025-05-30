<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserOrdersController;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/', function (Request $request) {
    $type = $request->query('type');
    $products = $type
        ? \App\Models\Products::where('type', $type)->get()
        : \App\Models\Products::all();
    return view('welcome', compact('products', 'type'));
});

Auth::routes();

// Route untuk semua authenticated user (admin & user biasa)
Route::middleware('auth')->group(function () {
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Dashboard berdasarkan role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    });
});

// ======================
// ADMIN ROUTES
// ======================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');
    
    // Products Management
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('admin.products.index');
        Route::get('/create', [ProductsController::class, 'create'])->name('admin.products.create');
        Route::post('/store', [ProductsController::class, 'store'])->name('admin.products.store');
        Route::get('/{product}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{product}', [ProductsController::class, 'update'])->name('admin.products.update');
        Route::delete('/{product}/delete', [ProductsController::class, 'destroy'])->name('admin.products.destroy');
    });
    
    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('admin.orders.index');
        Route::put('/{order}/update-status', [OrdersController::class, 'updateStatus'])->name('admin.orders.update_status');
        Route::get('/{order}', [OrdersController::class, 'show'])->name('admin.orders.show');
    });
});

// ======================
// USER ROUTES (Pelanggan)
// ======================
Route::prefix('user')->middleware(['auth', 'role:user'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        $profile = Profile::where('user_id', auth()->id())->first();
        return view('user.index', compact('profile'));
    })->name('user.dashboard');

    // Profile
    Route::post('/profile', [ProfileController::class, 'store'])->name('user.profile.store');
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');

    // Order
    Route::get('/orders', [UserOrdersController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{id}', [UserOrdersController::class, 'show'])->name('user.orders.show');

    
    Route::post('/orders/{order}/rate', [RatingController::class, 'store'])->name('user.orders.rate');
});

// ======================
// VISITOR ROUTES (Tidak perlu login)
// ======================
Route::get('/products/{id}', [ProductsController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity']);
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/order', [CheckoutController::class, 'store'])->name('orders.store');