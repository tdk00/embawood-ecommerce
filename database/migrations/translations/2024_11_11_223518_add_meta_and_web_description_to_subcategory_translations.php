<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaAndWebDescriptionToSubcategoryTranslations extends Migration
{
    public function up()
    {
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('description_web')->nullable();
        });
    }

    public function down()
    {
        Schema::table('subcategory_translations', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'description_web']);
        });
    }
}
