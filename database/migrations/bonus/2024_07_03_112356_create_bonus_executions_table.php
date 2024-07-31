<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusExecutionsTable extends Migration
{
    public function up()
    {
        Schema::create('bonus_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bonus_id')->constrained()->onDelete('cascade');
            $table->timestamp('executed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonus_executions');
    }
}
