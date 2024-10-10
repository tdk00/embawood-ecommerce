<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafetyInformationTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('safety_information_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('safety_information_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('safety_information_id')->references('id')->on('safety_informations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('safety_information_translations');
    }
}
