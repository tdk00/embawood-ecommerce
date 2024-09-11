<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('safety_informations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();  // If you want to allow admin to set an icon for each section.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('safety_informations');
    }
};
