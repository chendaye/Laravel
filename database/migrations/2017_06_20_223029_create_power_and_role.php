<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowerAndRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //角色表
        Schema::create("admin_roles", function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        //权限表
        Schema::create("admin_powers", function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        //权限角色表
        Schema::create("admin_power_role", function(Blueprint $table){
            $table->increments('id');
            $table->integer("role_id");
            $table->integer("power_id");
            $table->timestamps();
        });

        //角色用户表
        Schema::create("admin_role_user", function(Blueprint $table){
            $table->increments('id');
            $table->integer("role_id");
            $table->integer("user_id");
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
        Schema::drop('admin_roles');
        Schema::drop('admin_powers');
        Schema::drop('admin_power_role');
        Schema::drop('admin_role_user');
    }
}
