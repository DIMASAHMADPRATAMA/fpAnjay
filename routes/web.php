<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Models\User;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Login Admin
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Grup route khusus admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    // Produk (CRUD)
    Route::get('/products', [ProductAdminController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store');

    // Menampilkan daftar user yang login/terdaftar
    Route::get('/users', function () {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    })->name('admin.users.index');
});
