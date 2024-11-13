<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToProductImagesTable extends Migration
{
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('is_main');
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
