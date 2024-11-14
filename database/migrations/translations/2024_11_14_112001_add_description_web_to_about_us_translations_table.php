<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionWebToAboutUsTranslationsTable extends Migration
{
    public function up()
    {
        Schema::table('about_us_translations', function (Blueprint $table) {
            $table->text('description_web')->nullable(); // for storing rich text descriptions
        });
    }

    public function down()
    {
        Schema::table('about_us_translations', function (Blueprint $table) {
            $table->dropColumn('description_web');
        });
    }
}
