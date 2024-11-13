<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySkuAndStockColumnsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop unique constraint on sku if it exists
            $table->dropUnique(['sku']);

            // Modify sku and stock to be nullable
            $table->string('sku')->nullable()->change();
            $table->integer('stock')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert sku and stock columns to be non-nullable
            $table->string('sku')->nullable(false)->change();
            $table->integer('stock')->nullable(false)->change();

            // Reinstate unique constraint on sku if needed
            $table->unique('sku');
        });
    }
}
