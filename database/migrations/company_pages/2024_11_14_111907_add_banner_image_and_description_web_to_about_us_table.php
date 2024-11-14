<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerImageAndDescriptionWebToAboutUsTable extends Migration
{
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->string('banner_image')->nullable(); // for storing image path
            $table->text('description_web')->nullable(); // for storing rich text
        });
    }

    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'description_web']);
        });
    }
}
