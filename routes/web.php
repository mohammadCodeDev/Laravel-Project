<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index'); //To display the product list

    //for deleting a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    //for editing and updating
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    //User role management path
    Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

//Language change path
Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

require __DIR__ . '/auth.php';
