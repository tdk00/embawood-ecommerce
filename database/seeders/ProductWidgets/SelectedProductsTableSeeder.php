<?php

namespace Database\Seeders\ProductWidgets;

use App\Models\Product\Product;
use App\Models\ProductWidgets\SelectedProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SelectedProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::main()->get();

        foreach ($products as $index => $product) {
            SelectedProduct::create([
                'product_id' => $product->id,
                'order' => $index + 1, // Assigning order based on the index
            ]);
        }
    }
}
