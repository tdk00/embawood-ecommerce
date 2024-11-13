<?php

use App\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Step 1: Add the slug column without a unique constraint initially
            $table->string('slug')->nullable();
        });

        // Step 2: Generate unique slugs for existing records
        $this->generateSlugsForExistingProducts();

        // Step 3: Add a unique constraint to the slug column
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function generateSlugsForExistingProducts()
    {
        // Fetch all products
        $products = Product::withoutGlobalScopes()->get();

        $counter = 1;
        foreach ($products as $product) {
            // Generate a slug from the name if the slug is empty
            if (empty($product->slug)) {
                $originalSlug = Str::slug($product->name);
                $slug = $originalSlug . '-' . $counter;


                $counter++;
                // Ensure the slug is unique by appending a counter if necessary
                while (Product::where('slug', $slug)->exists()) {
                    $counter++;
                    $slug = $originalSlug . '-' . $counter;
                }

                // Update the product with the unique slug
                $product->slug = $slug;
                $product->save();
            }
        }
    }
}
