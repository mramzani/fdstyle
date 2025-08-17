<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->on('warehouses')->references('id')->cascadeOnDelete();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')->references('id')->cascadeOnDelete();

            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->on('product_variants')->references('id')->cascadeOnDelete();

            $table->integer('quantity');
            $table->integer('old_quantity')->default(0);
            $table->string('order_type')->nullable()->default('sales');
            $table->string('stock_type')->nullable()->default('in');
            $table->string('action_type')->nullable()->default('add');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->on('users')->references('id')->cascadeOnDelete();

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
        Schema::dropIfExists('stock_history');
    }
}
