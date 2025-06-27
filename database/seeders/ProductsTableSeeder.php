<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $category = Category::first();

        if ($category) {
            Product::create([
                'name' => 'White T-Shirt',
                'price' => 150000,
                'description' => 'Kaos putih premium',
                'category_id' => $category->id,
                'image_url' => 'https://via.placeholder.com/150'
            ]);

            Product::create([
                'name' => 'Black Jeans',
                'price' => 250000,
                'description' => 'Celana jeans hitam stylish',
                'category_id' => $category->id,
                'image_url' => 'https://via.placeholder.com/150'
            ]);
        }
    }
}
