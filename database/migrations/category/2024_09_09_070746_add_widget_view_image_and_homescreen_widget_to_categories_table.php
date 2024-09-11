<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWidgetViewImageAndHomescreenWidgetToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('widget_view_image', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->after('description');
            $table->tinyInteger('homescreen_widget')->default(0)->after('widget_view_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('widget_view_image');
            $table->dropColumn('homescreen_widget');
        });
    }
}
