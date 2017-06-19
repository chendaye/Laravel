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
    });
});
?>