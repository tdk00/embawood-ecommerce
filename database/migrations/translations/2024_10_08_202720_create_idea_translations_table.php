<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('idea_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title_category_view');
            $table->string('title_homepage_tab_view')->nullable();
            $table->timestamps();

            $table->unique(['idea_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('idea_translations');
    }
}
