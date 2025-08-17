<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable()->default('customer');
            $table->string('register_type')->nullable()->default('dashboard');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->unique();
            $table->string('password')->nullable();
            $table->string('national_code')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'deActive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('opening_balance')->default(0);
            $table->string('opening_balance_type', 20)->default('receive');
            $table->integer('credit_period')->default(0);
            $table->double('credit_limit')->default(0);

            $table->integer('online_order_count')->default(0);
            $table->integer('purchase_order_count')->default(0);
            $table->integer('purchase_return_count')->default(0);
            $table->integer('sales_order_count')->default(0);
            $table->integer('sales_return_count')->default(0);

            $table->double('total_amount')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('due_amount')->default(0);

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
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('users');
    }
}
