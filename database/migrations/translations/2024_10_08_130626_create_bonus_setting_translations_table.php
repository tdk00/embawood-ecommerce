<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonus_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_setting_id')->constrained('bonus_settings')->onDelete('cascade');
            $table->string('locale'); // Language code, e.g., 'en', 'az'
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_setting_translations');
    }
};
