<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ✅ Ambil semua item keranjang untuk user saat ini
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        foreach ($cartItems as $item) {
            if ($item->product && $item->product->image_url) {
                // Pakai image_url langsung
                $item->product->image = $item->product->image_url;
            } else {
                // Fallback default image
                $item->product->image = asset('images/default.png');
            }
        }

        return response()->json($cartItems);
    }

    // ✅ Tambahkan item ke keranjang atau update jumlah dengan benar
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $validated['quantity'];
            $existingCart->save();
            return response()->json($existingCart);
        } else {
            $newCart = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
            return response()->json($newCart);
        }
    }

    // ✅ Hapus item dari keranjang
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);}
}