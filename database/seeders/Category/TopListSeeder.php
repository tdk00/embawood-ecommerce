<?php

namespace Database\Seeders\Category;

use App\Models\Category\Subcategory;
use App\Models\Category\TopList;
use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopListSeeder extends Seeder
{
    public function run()
    {
        // Get all subcategories and products
        $subcategories = Subcategory::all();
        $products = Product::all();

        // Clear existing data
        TopList::truncate();

        foreach ($subcategories as $subcategory) {
            // Determine the number of products to select
            $productCount = min($products->count(), rand(3, 10));

            // Select a random subset of products
            $topProducts = $products->random($productCount);

            foreach ($topProducts as $index => $product) {
                TopList::create([
                    'subcategory_id' => $subcategory->id,
                    'product_id' => $product->id,
                    'position' => $index + 1,
                ]);
            }
        }
    }
}
