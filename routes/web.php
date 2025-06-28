<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\AdminChatController;
use App\Models\User;

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// ✅ Login & Logout Admin
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ✅ Route khusus admin (auth & is_admin middleware)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {

    // -------------------
    // ✅ Produk - CRUD
    // -------------------
    Route::get('/products', [ProductAdminController::class, 'index'])->name('admin.products.index');        // list produk
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('admin.products.create'); // form tambah
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store');        // simpan baru
    Route::get('/products/{id}/edit', [ProductAdminController::class, 'edit'])->name('admin.products.edit'); // form edit
    Route::put('/products/{id}', [ProductAdminController::class, 'update'])->name('admin.products.update');  // simpan edit
    Route::delete('/products/{id}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy'); // hapus

    // -------------------
    // ✅ User List
    // -------------------
    Route::get('/users', function () {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    })->name('admin.users.index');

    // -------------------
    // ✅ Fitur Chat Admin ↔ User
    // -------------------
    Route::get('/chat/{id}', [AdminChatController::class, 'chat'])->name('admin.chat');        // lihat pesan
    Route::post('/chat/{id}', [AdminChatController::class, 'send'])->name('admin.chat.send');  // kirim pesan
});
