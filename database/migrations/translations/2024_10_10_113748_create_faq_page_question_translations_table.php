<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqPageQuestionTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('faq_page_question_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_page_question_id')->constrained('faq_page_questions')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('question');
            $table->text('answer')->nullable();
            $table->timestamps();

            // Specify a shorter name for the unique index
            $table->unique(['faq_page_question_id', 'locale'], 'faq_question_locale_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq_page_question_translations');
    }
}
