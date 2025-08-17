<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Role\Entities\Role;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->on('permissions')->references('id')
                ->cascadeOnDelete();
            $table->foreign('role_id')->on('roles')->references('id')
                ->cascadeOnDelete();

            $table->primary(['permission_id','role_id']);
        });
        Schema::create('permission_user', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('permission_id')->on('permissions')->references('id')
                ->cascadeOnDelete();
            $table->foreign('user_id')->on('users')->references('id')
                ->cascadeOnDelete();

            $table->primary(['permission_id','user_id']);
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id')->unsigned();

            $table->foreign('user_id')->on('users')->references('id')
                ->cascadeOnDelete();
            $table->foreign('role_id')->on('roles')->references('id')
                ->cascadeOnDelete();

            $table->primary(['user_id','role_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('role_user');
    }
}
