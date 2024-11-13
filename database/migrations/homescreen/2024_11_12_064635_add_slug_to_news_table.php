<?php

use App\Models\News\News;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToNewsTable extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            // Step 1: Add the slug column without a unique constraint initially
            $table->string('slug')->nullable();
        });

        // Step 2: Generate unique slugs for existing records
        $this->generateSlugsForExistingNews();

        // Step 3: Add a unique constraint to the slug column
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function generateSlugsForExistingNews()
    {
        // Fetch all news items
        $newsItems = News::all();

        foreach ($newsItems as $news) {
            // Generate a slug from the title if the slug is empty
            if (empty($news->slug)) {
                $originalSlug = Str::slug($news->title);
                $slug = $originalSlug;
                $counter = 1;

                // Ensure the slug is unique by appending a counter if necessary
                while (News::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                // Update the news item with the unique slug
                $news->slug = $slug;
                $news->save();
            }
        }
    }
}
