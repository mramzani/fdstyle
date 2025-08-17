<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->enum('status',['published','draft'])->default('published');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('home_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('home_id');
            $table->foreign('home_id')->on('homes')->references('id')->cascadeOnDelete();
            $table->string('rowable_type')->comment('Category|Banner|Slider');
            $table->string('rowable_id');
            $table->integer('priority');
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
        Schema::dropIfExists('home_items');
        Schema::dropIfExists('homes');
    }
}
