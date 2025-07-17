<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;

Route::patch('/users/{user}/update-role', [UserController::class, 'updateRole'])
    ->name('admin.users.updateRole')
    ->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = User::all(); // geting all the users
    return view('dashboard', ['users' => $users]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

require __DIR__ . '/auth.php';
