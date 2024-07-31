<?php

namespace Database\Seeders\Category;

use App\Models\Category\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        Subcategory::create([
            'category_id' => 1, // Assuming 1 is the ID of the Electronics category
            'name' => 'Mobile Phones',
            'description' => 'Smartphones and accessories',
            'image' => 'mobile_phones.jpg',
            'widget_view_image' => 'electronics_widget.jpg'
        ]);

        // Add more subcategories as needed
    }
}
