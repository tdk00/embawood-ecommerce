<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeScreenSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('home_screen_sliders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('slider_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_screen_sliders');
    }
}
