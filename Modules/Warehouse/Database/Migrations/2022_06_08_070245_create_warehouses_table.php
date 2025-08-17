<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('user_id')->nullable();
            $table->string('logo')->nullable()->default(null);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->enum('status',['active','deActive'])->default('deActive');
            $table->string('address')->nullable()->default(null);
            $table->timestamps();
            //$table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
