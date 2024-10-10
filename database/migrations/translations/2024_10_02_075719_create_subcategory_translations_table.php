<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoryTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('subcategory_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            // Ensure a unique combination of subcategory_id and locale
            $table->unique(['subcategory_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subcategory_translations');
    }
}
