<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            $table->unsignedBigInteger('variant_id');
            $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete();

            $table->unsignedBigInteger('value_id');
            $table->foreign('value_id')->references('id')->on('variant_values')->cascadeOnDelete();
            $table->string('code')->nullable()->unique();
            $table->bigInteger('purchase_price')->default(0);
            $table->bigInteger('sales_price')->default(0);
            $table->bigInteger('quantity')->default(0);

            //$table->unique(['product_id', 'variant_id'], 'product_variant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
