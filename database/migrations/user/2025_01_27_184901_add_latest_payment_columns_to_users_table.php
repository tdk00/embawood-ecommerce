<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatestPaymentColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('latest_payment_id')->nullable();
            $table->enum('latest_payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->decimal('latest_payment_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('latest_payment_id');
            $table->dropColumn('latest_payment_status');
            $table->dropColumn('latest_payment_amount');
        });
    }
}
