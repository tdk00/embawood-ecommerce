<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopListsTable extends Migration
{
    public function up()
    {
        Schema::create('top_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');  // Changed from subcategory_id to category_id
            $table->unsignedBigInteger('product_id');
            $table->integer('position');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');  // Changed to categories table
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('top_lists');
    }
}
