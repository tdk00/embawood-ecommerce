<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content')->nullable();
            $table->timestamps();

            // Ensure a unique combination of page_id and locale
            $table->unique(['page_id', 'locale'], 'page_locale_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
}
