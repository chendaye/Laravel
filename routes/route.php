<?php
//todo:第一个路由
Route::get('first', function (){
    return 'first route';
});

//todo:路由方法
Route::get('get', function () {
    return 'get';
});
Route::post('post', function () {
    //todo:
});
Route::put('put', function () {
    //todo:
});
Route::patch('patch', function () {
    //todo:
});
Route::delete('delete', function () {
    //todo:
});
Route::options('options', function () {
    //todo:
});


//todo:响应多个路由方法
Route::match(['get', 'post'], 'match', function () {
    return 'match';
});

Route::any('any', function () {
    return 'any';
});

//todo:路由参数
Route::get('param/{param}', function ($param) {
    return $param;  //把参数解析成 $param
});

Route::get('param1/{param1}/param2/{param2}', function ($postId, $commentId) {
    return $postId + $commentId;    // a  b  对应两个变量
});

//todo:可选路由参数
Route::get('chose1/{name?}', function ($name = null) {
    return $name;
});

Route::get('chose2/{name?}', function ($name = 'John') {
    return $name;
});


//todo:正则表达式约束 参数格式
Route::get('reg/{name}', function ($name) {
    return $name;
})->where('name', '[A-Za-z]+');

Route::get('reg/{id}', function ($id) {
    return $id;
})->where('id', '[0-9]+');

Route::get('reg/{id}/{name}', function ($id, $name) {
    return [$name, $id];
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);

//todo:路由全局约束  在 RouteServiceProvider 的 boot 方法里定义这些模式   Route::pattern('global', '[0-9]+');
Route::get('global/{global}', function ($name) {
    //Pattern 一旦被定义，便会自动应用到所有使用该参数名称的路由上
    return $name;
});

//todo:命名路由
Route::get('named/profile', function () {
    //命名路由可以方便的生成 URL 或者重定向
    return $name = 777;
})->name('profile');

Route::get('named/profile/{name}', function ($name) {
    //命名路由可以方便的生成 URL 或者重定向
    return $name;
})->name('profile_name');

//可以为控制器方法指定路由名称：
Route::get('test/named', '\App\Test\Controllers\RouteController@named')->name('alais');


//todo:路由组  路由组允许共享路由属性，例如中间件和命名空间等，我们没有必要为每个路由单独设置共有属性，
//todo:共有属性会以数组的形式放到 Route::group 方法的第一个参数中

//中间件  待测
Route::get('middle/one', function () {
    //设置一个中间件
    return 'middlewareTest';
})->middleware('middlewareTest');


Route::get('middle/classname', function () {
    //设置一个中间件  用中间间类名
    return \App\Http\Middleware\TestMiddleware::class;
})->middleware(\App\Http\Middleware\TestMiddleware::class);

Route::get('middle/many', function () {
    //设置多个中间件
    return 'many';
})->middleware(\App\Http\Middleware\BeforeMiddleware::class, 'middlewareTest');

//中间件组 web 是一个中间件组
Route::get('middle/group', function () {
    return 'web';
})->middleware('web');

//为一组路由设置 一个中间件
Route::group(['middleware' => 'middlewareTest'], function () {
    Route::get('middle/routeGroup', function ()    {
        return 'routeGroup';
    });
});

//中间件参数
Route::get('middle/param', function () {
    //指定中间件参数可以通过冒号 : 来隔开中间件与参数，多个参数可以使用逗号分
    return 'middle-param';
})->middleware('middlewareTest:param');




//命名空间
Route::group(['namespace' => 'Admin'], function () {
    // 在 "App\Http\Controllers\Admin" 命名空间下的控制器
    //貌似只支持  App\Http\Controllers  空间下的
});



//子域名路由  待测
Route::group(['domain' => '{account}.myapp.com'], function () {
    //路由组也可以用作子域名的通配符，子域名可以像 URI 一样当作路由组的参数，
    //因此允许把捕获的子域名一部分用于我们的路由或控制器。
    //可以使用路由组属性的 domain 键声明子域名。
    Route::get('user/{id}', function ($account, $id) {
        return [$account, $id];
    });
});

//路由前缀
Route::group(['prefix' => 'test'], function () {
    Route::get('prefix', function ()    {
        // 匹配包含 "/admin/prefix" 的 URL
        return 'prefix';
    });
});

//todo:路由模型绑定
/*当向路由控制器中注入模型 ID 时，我们通常需要查询这个 ID 对应的模型，
Laravel 路由模型绑定提供了一个方便的方法自动将模型注入到我们的路由中，
例如，除了注入一个用户的 ID，你也可以注入与指定 ID 匹配的完整 User 类实例。*/

//隐式绑定  Laravel 会自动解析定义在路由或控制器方法（方法包含和路由片段匹配的已声明类型变量）中的 Eloquent 模型
//参数名和模型名对应
Route::get('eloquent/{user}', function (App\User $user) {
    /*在这个例子中，由于类型声明了 Eloquent 模型 App\User，对应的变量名 $user 会匹配路由片段中的 {user}，
    这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。*/
    dd($user);
});

//自定义键名
Route::get('eloquentKey/{productsInstockShipping}', function (App\ProductsInstockShipping $productsInstockShipping) {
    //如果你想要隐式模型绑定除 id 以外的数据库字段，你可以重写 Eloquent 模型类的getRouteKeyName 方法：
    dd($productsInstockShipping);
});

//todo:显式绑定
/*使用路由的 model 方法来为已有参数声明 class 。
你应该在 RouteServiceProvider 类中的 boot 方法内定义这些显式绑定：*/
Route::get('eloquentKeyObs/{pis}', function (App\ProductsInstockShipping $p) {
    //Route::model('pis', ProductsInstockShipping::class);
    dd($p);
});

//自定义解析逻辑
Route::get('eloquentKeySelf/{self}', function (App\ProductsInstockShipping $p) {
    /*
     * 如果你想要使用自定义的解析逻辑，需要使用 Route::bind 方法，
     * 传递到 bind 方法的闭包会获取到 URI 请求参数中的值，并且返回你想要在该路由中注入的类实例
     *
     * Route::bind('self', function ($value) {
        return ProductsInstockShipping::where('products_instock_id', $value)->first();
    *});
    */
    dd($p);
});


//todo:获取当前路由信息
Route::get('test/current', '\App\Test\Controllers\RouteController@current');
Route::get('test/route', function (){
    $route = Route::current();
    $name = Route::currentRouteName();
    $action = Route::currentRouteAction();
    dd([
        $route,
        $name,
        $action
    ]);
});

//todo:单一方法控制器
Route::get('single', '\App\Test\Controllers\SignleController');

//todo:资源路由控制器

//如果你想在默认的资源路由之外增加资源控制器路由，
//你应该在调用 Route::resource 之前定义这些路由；否则，resource 方法定义的路由可能会不小心覆盖你的附加路由：
Route::get('source/method', '\App\Test\Controllers\ResourceController@method');

//完全资源路由
Route::resource('source', '\App\Test\Controllers\ResourceController');

//声明资源路由的时候，你可以指定控制器处理部分操作，而不必使用全部默认的操作：
Route::resource('photo', '\App\Test\Controllers\PhotoController', ['only' => [
    'index', 'show'
]]);

Route::resource('photo', '\App\Test\Controllers\PhotoController', ['except' => [
    'create', 'store', 'update', 'destroy'
]]);

//命名资源路由
//默认地，所有的资源路由操作都有一个路由名称；不过你可以在参数选项中传入一个 names 数组来重写这些名称：
Route::resource('photo', '\App\Test\Controllers\PhotoController', ['names' => [
    'create' => 'photo.build'
]]);

//命名资源路由参数
//默认地，Route::resource 会基于资源名称的「单数」形式生成路由参数。
//你可以在选项数组中传入 parameters 参数，实现每个资源基础中参数名称的重写。
//parameters 应该是一个将资源名称和参数名称联系在一起的数组：
//将会为 show 方法的路由生成如下的 URI：/user/{admin_user}
Route::resource('user', 'AdminUserController', ['parameters' => [
    'user' => 'admin_user'
]]);


//todo:请求
Route::group(['prefix' => 'request'], function (){
    Route::get('/', '\App\Test\Controllers\RequestController@request');
    //路径
    Route::get('/path', '\App\Test\Controllers\RequestController@path');
    //url
    Route::get('/url', '\App\Test\Controllers\RequestController@url');
    //方法
    Route::get('/method', '\App\Test\Controllers\RequestController@method');
    //请求输入值
    Route::get('/input/{name}', '\App\Test\Controllers\RequestController@input');
    //旧输入数据
    Route::get('/oldData/{name}', '\App\Test\Controllers\RequestController@oldData');
    //注入测试
    Route::get('/closure', '\App\Test\Controllers\RequestController@closure');
    //文件资源
    Route::get('/file', '\App\Test\Controllers\RequestController@file');
    //通过路由闭包获取请求
    Route::get('/inject', function (\Illuminate\Http\Request $request){
        dd($request);
    });
    Route::get('/{id}', '\App\Test\Controllers\RequestController@param');

});