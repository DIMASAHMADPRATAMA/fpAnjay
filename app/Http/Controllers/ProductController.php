<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->latest()->get();
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imageUrl = asset('storage/' . $imagePath);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image_url' => $imageUrl
        ]);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $product->update($request->only(['name', 'price', 'stock', 'description', 'category_id']));

        if ($request->hasFile('image')) {
            if ($product->image_url) {
                $path = str_replace(asset('storage/'), '', $product->image_url);
                Storage::disk('public')->delete($path);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_url = asset('storage/' . $imagePath);
            $product->save();
        }

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image_url) {
            $path = str_replace(asset('storage/'), '', $product->image_url);
            Storage::disk('public')->delete($path);
        }
        $product->delete();

        return response()->json(['message' => 'Produk dihapus']);
    }
}
