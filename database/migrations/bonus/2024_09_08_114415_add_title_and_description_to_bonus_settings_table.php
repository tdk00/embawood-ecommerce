<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleAndDescriptionToBonusSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('bonus_settings', function (Blueprint $table) {
            $table->string('title')->after('id'); // Adds a title column after the id
            $table->text('description')->nullable()->after('title'); // Adds a description column after the title
        });
    }

    public function down()
    {
        Schema::table('bonus_settings', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']); // Drops the columns if rolled back
        });
    }
}
