<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Admin\ProductAdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/

// 🔐 Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🛍 Produk & Kategori (Frontend User - Ionic)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Login dengan Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // 👤 Profile
    Route::get('/profile', function () {
        return response()->json(auth()->user());
    });

    Route::put('/profile', function (Request $request) {
        $request->validate(['name' => 'required|string|max:255']);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    });

    // 🛒 Keranjang
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // 🧾 Checkout & Riwayat
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/user/orders', [OrderController::class, 'userOrders']);

    // 💬 Pesan
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages/send', [MessageController::class, 'send']);

    // 💳 Midtrans
    Route::post('/midtrans/transaction', [PaymentController::class, 'createTransaction']);
    Route::get('/payment/snap/{orderId}', [PaymentController::class, 'generateSnapToken']);

    // 🛠 Debug opsional
    Route::post('/debug-update', function (Request $request) {
        try {
            $user = \App\Models\User::first();
            $user->update($request->only(['name', 'email']));
            return $user;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('is_admin')->prefix('admin')->group(function () {
        Route::get('/products', [ProductAdminController::class, 'index']);
        Route::post('/products', [ProductAdminController::class, 'store']);
        Route::get('/products/create', [ProductAdminController::class, 'create']);
        // Tambahkan edit/delete jika perlu
    });
});
