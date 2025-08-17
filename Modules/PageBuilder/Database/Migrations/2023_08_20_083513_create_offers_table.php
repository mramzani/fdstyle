<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->double('percent',8,2)->default(0);
            $table->enum('status',['published','pending','draft'])->default('published');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });
        Schema::table('products',function (Blueprint $table){
            $table->unsignedBigInteger('offer_id')->nullable()->after('unit_id');
            $table->foreign('offer_id')->on('offers')->references('id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_offer_id_foreign');
            $table->dropColumn('offer_id');
        });
        Schema::dropIfExists('offers');
    }
}
