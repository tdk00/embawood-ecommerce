<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWidgetViewImageToSubcategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('widget_view_image')->nullable()->after('image');
        });
    }

    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('widget_view_image');
        });
    }
}
