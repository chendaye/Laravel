<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //对应的模型 AdminUser
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 15);
//            $table->string('email')->unique();
            $table->string('password');
            //$table->rememberToken();  //后台简化 不用TOKEN
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
        Schema::dropIfExists('admin_users');
    }
}
