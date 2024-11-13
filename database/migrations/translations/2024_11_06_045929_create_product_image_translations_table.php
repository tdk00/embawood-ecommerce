<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_image_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_image_id')->constrained('product_images')->onDelete('cascade');
            $table->string('locale')->index(); // Language code, e.g., 'en', 'az'
            $table->string('alt_text')->nullable(); // Alt text for the given locale
            $table->timestamps();

            $table->unique(['product_image_id', 'locale']); // Ensure unique translation per locale
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_image_translations');
    }
};
