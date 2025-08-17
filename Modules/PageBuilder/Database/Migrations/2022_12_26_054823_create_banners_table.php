<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->enum('type',['banner1x','banner2x','banner4x'])->default('banner1x')
                ->comment('banner1x_size:1837x329,banner2x_size:820x300,banner3x_size:400x300,banner4x_size:400x300');
            $table->string('banner_1')->comment('link,img_url');
            $table->string('banner_2')->nullable()->comment('link,img_url');
            $table->string('banner_3')->nullable()->comment('link,img_url');
            $table->string('banner_4')->nullable()->comment('link,img_url');
            $table->enum('status',['published','pending','draft'])->default('published');
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
        Schema::dropIfExists('banners');
    }
}
