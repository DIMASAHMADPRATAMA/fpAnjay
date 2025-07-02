<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    // Halaman Dashboard: tampilkan semua data (produk, user, pesanan)
    public function dashboard()
    {
        $products = Product::with('category')->get();
        $users = User::all();
        $orders = Order::with(['user', 'items.product'])->latest()->get(); // ✅ include items & product

        return view('admin.dashboard', compact('products', 'users', 'orders'));
    }

    // Halaman edit status pesanan
    public function editOrder($id)
    {
        $order = Order::with('user')->findOrFail($id);

        // ✅ ubah path view ke folder yang benar
        return view('admin.products.editOrder', compact('order'));
    }

    // Proses update status pesanan
    public function updateOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // ✅ validasi disesuaikan dengan enum di database
        $request->validate([
            'payment_status'   => 'required|in:unpaid,paid',
            'shipping_status'  => 'required|in:pending,processing,shipped',
        ]);

        $order->update([
            'payment_status'  => $request->payment_status,
            'shipping_status' => $request->shipping_status,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
