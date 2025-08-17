<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->on('orders')->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->on('product_variants')->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            $table->integer('quantity');
            $table->integer('unit_price');
            $table->float('discount_rate',8,2)->default(0);
            $table->integer('total_discount')->default(0);

            $table->integer('commission')->default(0);
            $table->integer('subtotal');

            $table->unique(['order_id','product_id','variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
