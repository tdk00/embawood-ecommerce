<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['earned', 'gift_card']);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('coupon_min', 10, 2)->nullable();
            $table->decimal('coupon_max', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
