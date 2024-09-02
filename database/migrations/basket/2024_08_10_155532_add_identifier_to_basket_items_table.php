<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentifierToBasketItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basket_items', function (Blueprint $table) {
            // Adding a new 'identifier' column
            $table->string('identifier')->nullable()->index()->after('user_id');

            // If necessary, you can also remove the 'user_id' column if you're replacing it with 'identifier'
            // $table->dropColumn('user_id');
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
            // Removing the 'identifier' column
            $table->dropColumn('identifier');

            // Re-adding the 'user_id' column if it was removed
            // $table->unsignedBigInteger('user_id')->index()->nullable();
        });
    }
}
