<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('homescreen_widget'); // Replace 'column_name' with the column after which you want to add 'is_active'.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
