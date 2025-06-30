<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ✅ PROSES CHECKOUT DARI KERANJANG
    public function checkout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'postal_code' => 'required|string|max:10',
            'courier' => 'required|string|max:50',
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
        $summary = [];
        $totalItems = 0;

        foreach ($carts as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => 'Stok tidak mencukupi untuk produk: ' . ($item->product->name ?? 'Tidak Ditemukan')
                ], 400);
            }

            $total += $item->product->price * $item->quantity;
            $summary[] = $item->product->name . ' x' . $item->quantity;
            $totalItems += $item->quantity;
        }

        $kode_order = 'ORDER-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id' => $user->id,
            'kode_order' => $kode_order,
            'name' => $request->name,
            'phone' => $request->phone,
            'postal_code' => $request->postal_code,
            'courier' => $request->courier,
            'address' => $request->address,
            'total' => $total,
            'status' => 'pending',
            'product_summary' => implode(', ', $summary),
            'total_items' => $totalItems,
        ]);

        foreach ($carts as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }

        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'order' => $order,
        ]);
    }

    // ✅ CHECKOUT LANGSUNG TANPA KERANJANG
    public function checkoutDirect(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'postal_code' => 'required|string|max:10',
            'courier' => 'required|string|max:50',
            'address' => 'required|string|min:5',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $product = Product::find($request->product_id);
        if (!$product || $product->stock < $request->quantity) {
            return response()->json(['message' => 'Stok produk tidak mencukupi atau produk tidak ditemukan'], 400);
        }

        $total = $product->price * $request->quantity;
        $kode_order = 'ORDER-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id' => $user->id,
            'kode_order' => $kode_order,
            'name' => $request->name,
            'phone' => $request->phone,
            'postal_code' => $request->postal_code,
            'courier' => $request->courier,
            'address' => $request->address,
            'total' => $total,
            'status' => 'pending',
            'product_summary' => $product->name . ' x' . $request->quantity,
            'total_items' => $request->quantity,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        $product->stock -= $request->quantity;
        $product->save();

        return response()->json([
            'message' => 'Checkout langsung berhasil',
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
