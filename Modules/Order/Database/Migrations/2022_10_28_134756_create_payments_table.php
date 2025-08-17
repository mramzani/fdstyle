<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->on('orders')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->on('warehouses')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('payment_type')->default('out');
            $table->string('payment_number')->nullable();
            $table->date('date');
            $table->integer('amount')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->unsignedBigInteger('payment_mode_id');
            $table->foreign('payment_mode_id')->on('payment_modes')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('reference_id')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
