<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Models\User;
use App\Http\Controllers\Admin\NewsController;
use App\Models\News;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Warehouse\OrderController as WarehouseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('welcome');

//Public routes that require login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $users = User::all();
        return view('dashboard', ['users' => $users]);
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Special routes for system administrators
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    //Product Management Paths
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index'); //To display the product list

    //for deleting a product
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    //for editing and updating
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');

    //User role management path
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // News Management Paths
    Route::resource('news', NewsController::class);

    // Order Management Paths
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/confirm', [AdminOrderController::class, 'confirm'])->name('orders.confirm');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
});

// Special routes for warehouse keepers
Route::middleware(['auth', 'warehouse'])->prefix('warehouse')->name('warehouse.')->group(function () {
    Route::get('/orders', [WarehouseOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/deliver', [WarehouseOrderController::class, 'deliver'])->name('orders.deliver');

    //show order details
    Route::get('/orders/{order}', [WarehouseOrderController::class, 'show'])->name('orders.show');
});

// Public Product and Cart Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// This route requires the user to be logged in to add to cart
Route::middleware(['auth'])->group(function () {
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::patch('/cart/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Routes for customer order management
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

//Language change path
Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

require __DIR__ . '/auth.php';
