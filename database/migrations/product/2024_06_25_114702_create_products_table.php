<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description');
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->timestamp('discount_ends_at')->nullable();
            $table->boolean('is_set')->default(false);
            $table->timestamps();
        });

        Schema::create('product_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_set');
        Schema::dropIfExists('products');
    }
}
