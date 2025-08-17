<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->from(1000);
            $table->string('name');
            $table->string('slug');
            $table->string('barcode')->nullable()->unique();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('category_id')->on('categories')->references('id')->nullOnDelete();
            $table->foreign('brand_id')->on('brands')->references('id')->nullOnDelete();
            $table->foreign('unit_id')->on('units')->references('id')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('product_details',function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->on('products')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->on('warehouses')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('current_stock')->nullable();
            $table->bigInteger('purchase_price')->nullable();
            $table->bigInteger('sales_price')->nullable();
            $table->bigInteger('weight')->nullable()->default(0);
            $table->bigInteger('length')->nullable()->default(0);
            $table->bigInteger('width')->nullable()->default(0);
            $table->bigInteger('height')->nullable()->default(0);
            $table->bigInteger('preparation_time')->nullable()->default(0);
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->on('taxes')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('stock_quantity_alert')->nullable();
            $table->enum('status',['out_of_stock','in_stock','disable'])->nullable();
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

        Schema::dropIfExists('product_details');
        Schema::dropIfExists('products');
    }
}
