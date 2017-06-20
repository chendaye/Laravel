<?php
Route::group(['prefix' => 'admin'], function (){
    /*Route::get('/login',function (){
        return 'this is admin`s group route';
    });*/

    //登录页面
    Route::get('/login', '\App\Admin\Controllers\LoginController@index');
    //登录行为
    Route::post('/login', '\App\Admin\Controllers\LoginController@login');
    //登出行为
    Route::get('/logout', '\App\Admin\Controllers\LoginController@logout');

    //用中间件进行验证  指定 guards => admin (预先配置好的)  这个路由组里的路由都必须先经过 admin 中间件
    Route::group(['middleware' => 'auth:admin'], function (){
        //首页
        Route::get('/home', '\App\Admin\Controllers\HomeController@index');
        //管理员列表
        Route::get('/users', '\App\Admin\Controllers\UserController@index');
        //创建管理员
        Route::get('/users/create', '\App\Admin\Controllers\UserController@create');
        //保存管理员
        Route::post('/users/store', '\App\Admin\Controllers\UserController@store');

        //后台文章列表页
        Route::get('/posts', '\App\Admin\Controllers\PostController@index');
        //后台文章审核
        Route::post('/posts/{post}/status', '\App\Admin\Controllers\PostController@status');
    });
});
?>