<?php

namespace Database\Seeders\Category;

use App\Models\Category\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'All kinds of electronic items',
            'banner_image' => 'electronics_banner.jpg',
            'widget_view_image' => 'electronics_widget.jpg'
        ]);

        // Add more categories as needed
    }
}
