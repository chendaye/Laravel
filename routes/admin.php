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

        //todo:Gate 权限控制
        Route::group(['middleware' => 'can:system'], function (){
            //用户
            //管理员列表
            Route::get('/users', '\App\Admin\Controllers\UserController@index');
            //创建管理员
            Route::get('/users/create', '\App\Admin\Controllers\UserController@create');
            //保存管理员
            Route::post('/users/store', '\App\Admin\Controllers\UserController@store');
            //赋予角色
            Route::get('/users/{user}/role', '\App\Admin\Controllers\UserController@role');
            //保存角色
            Route::post('/users/{user}/role', '\App\Admin\Controllers\UserController@roleStore');

            //角色
            //角色列表
            Route::get('/roles', '\App\Admin\Controllers\RoleController@index');
            //创建角色
            Route::get('/roles/create', '\App\Admin\Controllers\RoleController@create');
            //保存角色
            Route::post('/roles/store', '\App\Admin\Controllers\RoleController@store');
            //授权
            Route::get('/roles/{role}/power', '\App\Admin\Controllers\RoleController@power');
            //保存授权
            Route::post('/roles/{role}/power', '\App\Admin\Controllers\RoleController@powerStore');

            //权限
            //权限列表
            Route::get('/powers', '\App\Admin\Controllers\PowerController@index');
            //创建管权限
            Route::get('/powers/create', '\App\Admin\Controllers\PowerController@create');
            //保存管权限
            Route::post('/powers/store', '\App\Admin\Controllers\PowerController@store');
        });

        //todo: Policy Gate 策略 Gate 权限控制
        Route::group(['middleware' => 'can:post'], function (){
            //后台文章列表页
            Route::get('/posts', '\App\Admin\Controllers\PostController@index');
            //后台文章审核
            Route::post('/posts/{post}/status', '\App\Admin\Controllers\PostController@status');
        });

        //专题管理
        Route::group(['middleware' => 'can:topic'], function (){
            //专题页面的resource方法 制定控制器 会自动创建 rest方法  可以指定具体创建那些方法
            Route::resource('/topics', '\App\Admin\Controllers\TopicController', ['only' => [
                'index', 'create', 'store', 'destroy'
            ]]);
        });

        //通知管理
        Route::group(['middleware' => 'can:notice'], function (){
            Route::resource('/notices', '\App\Admin\Controllers\NoticeController', ['only' => [
                'index', 'create', 'store'
            ]]);
        });
    });
});
?>