<?php

use App\Models\Category\Subcategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToSubcategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            // Step 1: Add the slug column without a unique constraint initially
            $table->string('slug')->nullable();
        });

        // Step 2: Generate unique slugs for existing records
        $this->generateSlugsForExistingSubcategories();

        // Step 3: Add a unique constraint to the slug column
        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function generateSlugsForExistingSubcategories()
    {
        // Fetch all subcategories
        $subcategories = Subcategory::withoutGlobalScopes()->get();

        foreach ($subcategories as $subcategory) {
            // Generate a slug based on the name if the slug is empty
            if (empty($subcategory->slug)) {
                $originalSlug = Str::slug($subcategory->name);
                $slug = $originalSlug;
                $counter = 1;

                // Ensure the slug is unique by appending a counter if necessary
                while (Subcategory::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                // Update the subcategory with the unique slug
                $subcategory->slug = $slug;
                $subcategory->save();
            }
        }
    }
}
