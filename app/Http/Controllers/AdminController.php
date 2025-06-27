<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function index() {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        return view('admin.products.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image_url' => 'nullable|url',
            'description' => 'nullable'
        ]);

        Product::create($validated);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan');
    }

    
}
