<?php

namespace Database\Seeders\Product;

use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSimilarSeeder extends Seeder
{
    public function run()
    {
        $products = Product::main()->get();
        foreach ($products as $product) {
            // Get all other products excluding the current one
            $otherProducts = $products->where('id', '!=', $product->id)->pluck('id')->toArray();

            // Shuffle the array of other products and pick a random subset
            shuffle($otherProducts);
            $randomSimilarProducts = array_slice($otherProducts, 0, 3); // Adjust the number as needed

            // Attach the similar products to the current product
            $product->similarProducts()->sync($randomSimilarProducts);
        }
    }
}
