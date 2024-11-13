<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoCallRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('video_call_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp_number');
            $table->string('subject');
            $table->string('address');
            $table->enum('status', ['pending', 'rejected', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_call_requests');
    }
}
