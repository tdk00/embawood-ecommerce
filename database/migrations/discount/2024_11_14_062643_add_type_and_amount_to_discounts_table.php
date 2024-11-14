<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndAmountToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Adding the 'type' column to define if the discount is a percentage or fixed amount
            $table->enum('type', ['percentage', 'amount'])->default('percentage')->after('discount_percentage');

            // Adding the 'amount' column to store fixed discount amounts if 'type' is 'amount'
            $table->decimal('amount', 10, 2)->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Dropping the columns if the migration is rolled back
            $table->dropColumn(['type', 'amount']);
        });
    }
}
