<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 所谓数据库迁移就是 对数据库表级别的操作 也交给框架来做
 * Class CreatePostsTable
 */
class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *执行
     * @return void
     */
    public function up()
    {
        //创建表用create  更新表用table方法
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            //设置长度varchar长度就是设置的 不设置就是 laravel 默认的长度  可以给字段设置默认值
            $table->string('title', '191')->default("")->comment('文章标题！');
            $table->text('content')->comment('文章内容');
            $table->integer('user_id')->default(0);
            //创建时间 create_at   新增时间 update_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *回滚
     * @return void
     */
    public function down()
    {
//        Schema::drop('users');
        //回滚
        Schema::dropIfExists('posts');
    }
}
