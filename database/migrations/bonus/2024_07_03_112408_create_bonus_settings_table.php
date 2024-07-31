<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('bonus_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('target_count')->nullable();
            $table->decimal('bonus_amount', 8, 2);
            $table->enum('period', ['daily', 'weekly'])->default('daily');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonus_settings');
    }
}
