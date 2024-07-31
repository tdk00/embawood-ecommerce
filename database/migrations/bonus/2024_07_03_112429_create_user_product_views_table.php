<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProductViewsTable extends Migration
{
    public function up()
    {
        Schema::create('user_product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('viewed_date'); // Track the date of the view
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_product_views');
    }
}
