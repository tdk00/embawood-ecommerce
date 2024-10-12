<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegionIdToStoresTable extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Add the region_id column and set it as a foreign key
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Drop the foreign key and the region_id column
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
        });
    }
}
