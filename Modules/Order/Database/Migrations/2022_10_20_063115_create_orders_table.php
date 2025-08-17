<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 20);
            $table->string('order_type', 20)->default('sales');
            $table->dateTime('order_date');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            //$table->unsignedBigInteger('tax_id')->nullable();
            //$table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade')->onUpdate('cascade');
            //$table->float('tax_rate', 8, 2)->nullable();

            //$table->decimal('tax_amount',8,0)->default(0);
            $table->integer('discount')->nullable();
            $table->integer('shipping')->nullable();
            $table->integer('profit')->default(0);
            $table->integer('total_commission')->default(0);
            $table->integer('subtotal');
            $table->integer('total');
            $table->integer('paid_amount')->default(0);
            $table->integer('due_amount')->default(0);

            $table->string('order_status', 20);
            $table->string('tracking_number')->nullable();
            $table->unsignedBigInteger('staff_user_id')->nullable();
            $table->foreign('staff_user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('payment_status', 20)->default('unpaid');
            $table->integer('total_items')->default(0);
            $table->integer('total_quantity')->default(0);

            //$table->unsignedBigInteger('cancelled_by')->nullable();
            //$table->foreign('cancelled_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('orders');
    }
}
