<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('limit');
            $table->tinyInteger('percent');
            $table->integer('min_basket_amount')->nullable();
            $table->integer('allowed_qty')->default(1);
            $table->integer('used_qty')->default(0);
            $table->enum('status',['disable','enable']);
            $table->date('expire_date');
            $table->bigInteger('couponable_id')->nullable();
            $table->string('couponable_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
