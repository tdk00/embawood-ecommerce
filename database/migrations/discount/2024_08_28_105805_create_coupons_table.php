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
            $table->unsignedInteger('discount_percentage');
            $table->unsignedInteger('usage_limit')->default(0);
            $table->json('description'); // Multilingual description
            $table->decimal('min_required_amount', 8, 2);
            $table->decimal('max_required_amount', 8, 2)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('usage_count')->default(0); // Tracks how many times the coupon has been used
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
