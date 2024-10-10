<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubIdeaTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('sub_idea_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_idea_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();

            $table->unique(['sub_idea_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_idea_translations');
    }
}
