<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//文章路由
//文章列表
Route::get('posts', '\App\Http\Controllers\PostController@index');
//创建文章
Route::get('posts/create', '\App\Http\Controllers\PostController@create');
Route::post('posts', '\App\Http\Controllers\PostController@store');
//文章搜索
Route::get('posts/search', '\App\Http\Controllers\PostController@search');

//编辑文章
Route::get('posts/{post}/edit', '\App\Http\Controllers\PostController@edit');
Route::put('posts/{post}', '\App\Http\Controllers\PostController@update');
//删除文章
Route::get('posts/{post}/delete', '\App\Http\Controllers\PostController@delete');
//文章详情
Route::get('posts/{post}', '\App\Http\Controllers\PostController@show');
//图片上传
Route::post('posts/img/upload', '\App\Http\Controllers\PostController@imgUpload');
//文章评论
Route::post('posts/{post}/comment', '\App\Http\Controllers\PostController@comment');

//赞
Route::get('posts/{post}/zan', '\App\Http\Controllers\PostController@zan');
//取消赞
Route::get('posts/{post}/cancelZan', '\App\Http\Controllers\PostController@cancelZan');

//用户模块
//注册页面
Route::get('register', '\App\Http\Controllers\RegisterController@index');
//注册行为
Route::post('register', '\App\Http\Controllers\RegisterController@register');

//登录页面
//登录页面
Route::get('login', '\App\Http\Controllers\LoginController@index');
//登录行为
Route::post('login', '\App\Http\Controllers\LoginController@login');
//登出行为
Route::get('logout', '\App\Http\Controllers\LoginController@logout');

//个人设置页面
//设置页面
Route::get('user/me/set', '\App\Http\Controllers\UserController@set');
Route::post('user/me/set', '\App\Http\Controllers\UserController@store');


