<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = ['T-Shirt', 'Jeans', 'Shoes', 'Jackets', 'Accessories'];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}

