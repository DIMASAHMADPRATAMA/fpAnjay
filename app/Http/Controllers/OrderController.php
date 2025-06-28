<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ✅ PROSES CHECKOUT
    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        $total = 0;

        foreach ($carts as $item) {
            if (!$item->product) {
                return response()->json(['message' => 'Produk tidak ditemukan untuk salah satu item di keranjang.'], 400);
            }

            $total += $item->product->price * $item->quantity;
        }

        $kode_order = 'ORDER-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id' => $user->id,
            'kode_order' => $kode_order,
            'address' => $request->address,
            'total' => $total,
            'status' => 'pending', // status awal sesuai Midtrans
        ]);

        foreach ($carts as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'order' => $order,
        ]);
    }

    // ✅ RIWAYAT PESANAN PENGGUNA
    public function userOrders()
    {
        return Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
