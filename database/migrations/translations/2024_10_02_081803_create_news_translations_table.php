<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('news_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('content')->nullable();
            $table->timestamps();

            // Ensure a unique combination of news_id and locale
            $table->unique(['news_id', 'locale'], 'news_locale_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_translations');
    }
}
