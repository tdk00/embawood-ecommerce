<?php

use App\Models\Category\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Step 1: Add the slug column without unique constraint
            $table->string('slug')->nullable();
        });

        // Step 2: Generate unique slugs for existing records, ignoring the global 'active' scope
        $this->generateSlugsForExistingData();

        // Step 3: Add unique constraint to the slug column
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function generateSlugsForExistingData()
    {
        // Fetch all categories without the global scope for 'is_active'
        $categories = Category::withoutGlobalScopes()->get();

        foreach ($categories as $category) {
            // Generate a slug from the name if slug is empty
            if (empty($category->slug)) {
                $originalSlug = Str::slug($category->name);
                $slug = $originalSlug;
                $counter = 1;

                // Ensure slug is unique by appending a counter if necessary
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                // Update the category with the unique slug
                $category->slug = $slug;
                $category->save();
            }
        }
    }
}
