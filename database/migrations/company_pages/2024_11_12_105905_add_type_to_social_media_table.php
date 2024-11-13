<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToSocialMediaTable extends Migration
{
    public function up()
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->string('type')->default('other'); // Default to 'other'
        });
    }

    public function down()
    {
        Schema::table('social_media', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
