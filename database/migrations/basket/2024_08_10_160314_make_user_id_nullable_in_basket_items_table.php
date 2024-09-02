<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserIdNullableInBasketItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basket_items', function (Blueprint $table) {
            // Modify the 'user_id' column to be nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basket_items', function (Blueprint $table) {
            // Revert the 'user_id' column back to not nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
}
