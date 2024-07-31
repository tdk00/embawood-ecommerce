<?php

namespace Database\Seeders\Ideas;

use App\Models\Ideas\SubIdeaItem;
use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSubIdeaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subIdeaItems = SubIdeaItem::all();
        $products = Product::main()->get();

        foreach ($products as $product) {
            $subIdeaItemsCount = $subIdeaItems->count();
            if ($subIdeaItemsCount > 0) {
                $numberOfSubIdeaItems = rand(1, min(3, $subIdeaItemsCount));
                $assignedSubIdeaItems = $subIdeaItems->random($numberOfSubIdeaItems);
                $product->subIdeaItems()->syncWithoutDetaching($assignedSubIdeaItems->pluck('id')->toArray());
            }
        }
    }
}
