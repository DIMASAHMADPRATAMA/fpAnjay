<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ProductAdminController extends Controller
{
    // ✅ Menampilkan daftar produk & user di dashboard
    public function index()
    {
        $products = Product::with('category')->get();
        $users = User::where('role', 'user')->get();

        $adminId = Auth::id();
        $unreadByUser = Message::where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->groupBy('sender_id')
            ->selectRaw('sender_id, COUNT(*) as total')
            ->pluck('total', 'sender_id');

        return view('admin.dashboard', compact('products', 'users', 'unreadByUser'));
    }

    // ✅ Menampilkan form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // ✅ Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // ✅ Menampilkan form edit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // ✅ Menyimpan update produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // ✅ Menghapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
