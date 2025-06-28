<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ✅ Ambil semua item keranjang untuk user saat ini
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        foreach ($cartItems as $item) {
            if ($item->product && $item->product->image_url) {
                // LANGSUNG pakai image_url karena itu sudah berupa URL lengkap
                $item->product->image = $item->product->image_url;
            } else {
                // Fallback ke default image lokal
                $item->product->image = asset('images/default.png');
            }
        }

        return response()->json($cartItems);
    }

    // ✅ Tambahkan item ke keranjang atau update jumlah
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $cart = Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $validated['product_id']],
            ['quantity' => DB::raw("quantity + {$validated['quantity']}")]
        );

        return response()->json($cart);
    }

    // ✅ Hapus item dari keranjang
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);
    }
}
