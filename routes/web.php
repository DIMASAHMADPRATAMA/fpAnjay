<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\AdminChatController;
use App\Models\User;

// Halaman Welcome
Route::get('/', function () {
    return view('welcome');
});

// ============================
// 🔐 Login & Logout
// ============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================
// 🛠️ Admin Routes (Protected by Middleware)
// ============================
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {

    // ✅ Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // ============================
    // 📦 Manajemen Produk
    // ============================
    Route::get('/products', [ProductAdminController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductAdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductAdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductAdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy');

    // ============================
    // 👥 Daftar Pengguna
    // ============================
    Route::get('/users', function () {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    })->name('admin.users.index');

    // ============================
    // 💬 Chat Admin ↔ User
    // ============================
    Route::get('/chat/{id}', [AdminChatController::class, 'chat'])->name('admin.chat');
    Route::post('/chat/{id}', [AdminChatController::class, 'send'])->name('admin.chat.send');

    // ============================
    // 🚚 Manajemen Pesanan
    // ============================
    Route::get('/orders/{id}/edit', [AdminController::class, 'editOrder'])->name('admin.orders.edit');
    Route::put('/orders/{id}', [AdminController::class, 'updateOrder'])->name('admin.orders.update');

});
