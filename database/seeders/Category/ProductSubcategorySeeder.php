<?php

namespace Database\Seeders\Category;

use App\Models\Category\Subcategory;
use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSubcategorySeeder extends Seeder
{
    public function run()
    {
        $subcategories = Subcategory::all();

        // Assuming Product::main() returns a query builder
        $products = Product::main()->get();

        foreach ($products as $product) {
            // Ensure the random number does not exceed the count of available subcategories
            $subcategoriesCount = $subcategories->count();
            if ($subcategoriesCount > 0) {
                $numberOfSubcategories = rand(1, min(3, $subcategoriesCount));
                $assignedSubcategories = $subcategories->random($numberOfSubcategories);
                $product->subcategories()->syncWithoutDetaching($assignedSubcategories->pluck('id')->toArray());
            }
        }
    }
}
