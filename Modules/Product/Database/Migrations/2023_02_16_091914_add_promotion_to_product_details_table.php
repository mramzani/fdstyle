<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromotionToProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->bigInteger('promotion_price')->nullable()->after('sales_price');
            $table->dateTime('promotion_start_date')->nullable()->after('promotion_price');
            $table->dateTime('promotion_end_date')->nullable()->after('promotion_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn('promotion_price');
            $table->dropColumn('promotion_start_date');
            $table->dropColumn('promotion_end_date');
        });
    }
}
