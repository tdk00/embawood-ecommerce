<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusAmountsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('total_bonus_amount', 10, 2)->default(0);
            $table->decimal('used_bonus_amount', 10, 2)->default(0);
            $table->decimal('remaining_bonus_amount', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_bonus_amount', 'used_bonus_amount', 'remaining_bonus_amount']);
        });
    }
}
