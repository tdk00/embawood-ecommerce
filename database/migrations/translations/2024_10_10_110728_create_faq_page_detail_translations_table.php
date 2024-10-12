<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqPageDetailTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_page_detail_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faq_page_detail_id');
            $table->string('locale'); // For storing language codes like 'en', 'fr', etc.

            // Translatable fields
            $table->string('email_title')->nullable();
            $table->text('email_description')->nullable();
            $table->string('phone_title')->nullable();
            $table->text('phone_description')->nullable();

            $table->timestamps();

            // Foreign key relation to the faq_page_details table
            $table->foreign('faq_page_detail_id')
                ->references('id')->on('faq_page_details')
                ->onDelete('cascade');

            // Ensure unique combinations of faq_page_detail_id and locale
            $table->unique(['faq_page_detail_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_page_detail_translations');
    }
}
