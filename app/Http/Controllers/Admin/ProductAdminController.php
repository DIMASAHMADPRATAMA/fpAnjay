<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductAdminController extends Controller
{
    // Menampilkan dashboard berisi produk dan user
    public function index()
    {
        $products = Product::with('category')->get();
        $users = User::all(); // Jika ingin tampilkan user juga
        return view('admin.dashboard', compact('products', 'users'));
    }

    // Form untuk tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Menyimpan produk baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            
        ]);

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'image_url'   => $request->image_url,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }
}
