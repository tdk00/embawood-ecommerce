<?php

namespace Database\Seeders;

use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Category\ProductSubcategorySeeder;
use Database\Seeders\Category\SubcategorySeeder;
use Database\Seeders\Category\TopListSeeder;
use Database\Seeders\HomeScreen\HomeScreenSliderSeeder;
use Database\Seeders\Ideas\IdeaSeeder;
use Database\Seeders\Ideas\IdeaWidgetItemSeeder;
use Database\Seeders\Ideas\IdeaWidgetTabSeeder;
use Database\Seeders\Ideas\ProductSubIdeaItemSeeder;
use Database\Seeders\Ideas\SubIdeaItemImageSeeder;
use Database\Seeders\Ideas\SubIdeaItemSeeder;
use Database\Seeders\Ideas\SubIdeaSeeder;
use Database\Seeders\News\NewsSeeder;
use Database\Seeders\Product\ReviewSeeder;
use Database\Seeders\ProductWidgets\MostViewedProductsTableSeeder;
use Database\Seeders\ProductWidgets\NewProductsTableSeeder;
use Database\Seeders\ProductWidgets\SelectedProductsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            NewsSeeder::class,
            HomeScreenSliderSeeder::class,
            NewProductsTableSeeder::class,
            MostViewedProductsTableSeeder::class,
            SelectedProductsTableSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductSubcategorySeeder::class,
            IdeaSeeder::class,
            SubIdeaSeeder::class,
            SubIdeaItemSeeder::class,
            SubIdeaItemImageSeeder::class,
            ProductSubIdeaItemSeeder::class,
            IdeaWidgetTabSeeder::class,
            IdeaWidgetItemSeeder::class,
            TopListSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
