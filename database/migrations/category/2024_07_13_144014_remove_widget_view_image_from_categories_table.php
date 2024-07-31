<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveWidgetViewImageFromCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('widget_view_image');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('widget_view_image')->nullable();
        });
    }
}
