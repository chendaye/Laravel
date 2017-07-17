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
//todo:响应
Route::group(['prefix' => 'response'], function (){
    //响应字符串
    Route::get('/string', function (){
        return 'Hello World';
    });
    //响应数组 框架也会自动地将数组转为 JSON 响应
    Route::get('array', function () {
        return [1, 2, 3];
    });
    //响应集合
    Route::get('eloquent', '\App\Test\Controllers\ResponseController@eloquent');
    //响应对象
    Route::get('/obj', function (){
        return response('Hello World', 200)
            ->header('Content-Type', 'text/plain');
    });
    //附加响应头信息
    Route::get('add_header_msg', '\App\Test\Controllers\ResponseController@add_header_msg');
    //附加 Cookie 至响应
    Route::get('/cookie', function (){
        return response('cookie')
            ->header('Content-Type', 'text/plain')
            ->cookie('name', 'value', 1);
    });
    //重定向
    Route::get('/redirect', function (){
        return redirect('response/cookie');
    });
    //重定向至上级一页面  功能利用了 Session，请确保调用 back 函数的路由是使用 web 中间件组或应用了所有的 Session 中间件
    Route::post('/back', function () {
        // 验证请求...
        return back()->withInput();
    })->middleware('web');
    //重定向至命名路由
    //当调用不带参数的辅助函数 redirect 时，会返回一个 Illuminate\Routing\Redirector 实例，
    //该实例允许你调用 Redirector 实例的任何方法。
    //例如，生成一个 RedirectResponse 重定向至一个被命名的路由时，您可以使用 route 方法：
    Route::get('/redirect_route', function (){
        return redirect()->route('profile');
    });
    //如果你的路由有参数，它们可以作为 route 方法的第二个参数来传递：
    Route::get('/redirect_route_param', function (){
        return redirect()->route('profile', ['id' => 1]);
    });
    //通过 Eloquent 模型填充参数
    //  productsInstockShipping  products_instock_shipping 两种写法都可以
    Route::get('/redirect_eloquent/{products_instock_shipping}', '\App\Test\Controllers\ResponseController@redirect_eloquent')->name('eloquent');
    //如果要重定向到一个使用了 Eloquent 模型并需要传递 ID 参数的路由上，你只需传递模型本身即可，ID 会自动提取。
    //如果想要更改自动提取的路由参数的键值，你应该重写 Eloquent 模型里的 getRouteKey 方法
    Route::get('/redirectEloquent', function (){
        $eloquent = \App\ProductsInstockShipping::find(253);
        return redirect()->route('eloquent', [$eloquent]);
    });
    //重定向至控制器行为
    //你可能也会用到生成重定向至 控制器行为的响应。要实现此功能，可以向 action 方法传递控制器和行为名称作为参数来实现。
    //这里并不需要指定完整的命名空间，因为 Laravel 的 RouteServiceProvider 会自动设置基本的控制器命名空间
    //只对 Http下面的起作用
    Route::get('/action', function (){
        if(0)return redirect()->action('LoginController@index');
        //可以传递参数
        return redirect()->action(
            'UserController@profile', ['id' => 1]
        );
    });
    //重定向并附加 Session 闪存数据
    //重定向至一个新的 URL 的同时通常会 附加 Session 闪存数据。
    //一般来说，在控制器行为成功地执行之后才会向 Session 中闪存成功的消息。
    //为了方便，你可以利用链式调用的方式创建一个 RedirectResponse 的实例并闪存数据至 Session：
    //在定向的页面  就可以  使用 session('status')
    Route::post('session', function () {
        return redirect('dashboard')->with('status', 'Profile updated!');
    });
    //其他响应类型
    //视图响应 如果你的响应内容不但需要控制响应状态码和响应头信息而且还需要返回一个 视图，这时你应该使用 view 方法：
    Route::get('/view', function (){
        $a = 777;
        return response()
            ->view('test.index', compact('a'), 200)
            ->header('Content-Type', 'text/plain');
    });
    //JSON 响应 json 方法会自动将 Content-Type 响应头信息设置为 application/json，并使用 PHP 的 json_encode 函数将数组转换为 JSON 字符串。
    Route::get('/json', function (){
        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA'
        ]);
    });
    //JSONP 响应 可以使用 json 方法并结合 withCallback 函数：
    Route::get('/jsonp', function (\Illuminate\Http\Request $request){
        return response()
            ->json(['name' => 'Abigail', 'state' => 'CA'])
            ->withCallback($request->input('callback'));
    });
    //文件下载
    //download 方法可以用于生成强制让用户的浏览器下载指定路径文件的响应。
    //download 方法接受文件名称作为方法的第二个参数，此名称为用户下载文件时看见的文件名称。
    //最后，你可以传递一个包含 HTTP 头信息的数组作为第三个参数传入该方法：
    Route::get('/download', function (){
        if(0)return response()->download($pathToFile = '');
        return response()->download($pathToFile = '', $name = '', $headers = []);
    });
    //文件响应
    //file 方法可以用来显示一个文件，例如图片或者 PDF，直接在用户的浏览器中显示，而不是开始下载。
    //这个方法的第一个参数是文件的路径，第二个参数是包含头信息的数组
    Route::get('/file', function (){
        if(0)return response()->file($pathToFile = '');
        return response()->file($pathToFile = '', $headers = []);
    });
});
//todo:通过中间件授权
/*
 * 通过中间件
Laravel 包含一个可以在请求到达路由或控制器之前就进行动作授权的中间件。
默认，Illuminate\Auth\Middleware\Authorize 中间件被指定到 App\Http\Kernel 类中 can 键上。
我们用一个授权用户更新博客的例子来讲解 can 中间件的使用*/
/*
 * 传递给 can 中间件 2 个参数。
 * 第一个是需要授权的动作的名称，
 * 第二个是我们希望传递给策略方法的路由参数。
 * 这里因为使用了 隐式模型绑定，一个 Post 会被传递给策略方法。
 * 如果用户不被授权访问指定的动作，这个中间件会生成带有 403 状态码的 HTTP 响应。*/
Route::put('/post/{post}', function (Post $post) {
    // 当前用户可以更新博客...
})->middleware('can:update,post');
/*
 * 不需要指定模型的动作
同样的，一些动作，比如 create，并不需要指定模型实例。
在这种情况下，可传递一个类名给中间件。当授权动作时，这个类名将被用来判断使用哪个策略*/
Route::post('/post', function () {
    // 当前用户可以创建博客...
})->middleware('can:create,App\Post');
//todo:权限 Gate Policy 测试
Route::group(['middleware' => 'auth:admin'], function (){
    //Gate
    //用中间件 进行 Gate 授权
    Route::group(['prefix' => 'auth', 'middleware' => 'can:update-shipping'], function (){
        Route::get('/gate/update', '\App\Test\Controllers\ProductsInstockShippingController@update_gate');
    });
    //用allow denies 方法进行 Gate 授权
    Route::get('auth/gate/update_allow', '\App\Test\Controllers\ProductsInstockShippingController@update_gate_allow');
    //指定 某个用户是否有 Gate 权限
    Route::get('auth/gate/appoint', '\App\Test\Controllers\ProductsInstockShippingController@appoint_gate');
    //Policy
    Route::get('auth/policy/update', '\App\Test\Controllers\ProductsInstockShippingController@update_policy');
    Route::get('auth/policy/create', '\App\Test\Controllers\ProductsInstockShippingController@create_policy');  //不需要模型
    //辅助函数
    Route::get('auth/policy/authorize', '\App\Test\Controllers\ProductsInstockShippingController@authorize_policy');
    Route::get('auth/policy/authorize_none', '\App\Test\Controllers\ProductsInstockShippingController@authorize_policy_none_model');
    //中间件  传到中间件的参数 要和路由参数名匹配
    Route::get('auth/policy/middleware/{products_instock_shipping}', '\App\Test\Controllers\ProductsInstockShippingController@middleware_policy')
        ->middleware('can:update,products_instock_shipping');
    Route::get('auth/policy/middleware_p/{products_instock_shipping}', function (App\ProductsInstockShipping $productsInstockShipping) {
        //Route::model('pis', ProductsInstockShipping::class);
        dd($productsInstockShipping);
    });
    Route::get('auth/policy/middleware_none', '\App\Test\Controllers\ProductsInstockShippingController@middleware_policy_none')
        ->middleware('can:create,App\ProductsInstockShipping');  //不需要模型
});
//todo:数据库操作
Route::group(['prefix' => 'mysql'], function (){
    Route::get('native', '\App\Test\Controllers\DBController@native_sql');
    //数据查询
    //DB
    Route::get('/db_base', '\App\Test\Controllers\DBController@db_base');
    Route::get('/paginate', '\App\Test\Controllers\DBController@paginate');
    //Eloquent
    Route::get('/eloquent_get', '\App\Test\Controllers\EloquentController@eloquent_get');
    Route::get('/eloquent_save', '\App\Test\Controllers\EloquentController@eloquent_save');
    Route::get('/eloquent_del', '\App\Test\Controllers\EloquentController@eloquent_del');
    //scope
    Route::get('/scope', '\App\Test\Controllers\EloquentController@scope');
    //event
    Route::get('/event', '\App\Test\Controllers\EloquentController@event');
    //修改器 访问器
    Route::get('/alert', '\App\Test\Controllers\EloquentAlertController@alert');
    Route::get('/visit', '\App\Test\Controllers\EloquentAlertController@visit');
    //日期修改器
    Route::get('/date', '\App\Test\Controllers\EloquentAlertController@date');
    //序列化
    Route::get('/toArray', '\App\Test\Controllers\EloquentSerializeController@toArray');
    Route::get('/toJson', '\App\Test\Controllers\EloquentSerializeController@toJson');
});
//todo:集合操作
Route::group(['prefix' => 'collect'], function (){
    //创建集合
    Route::get('create', '\App\Test\Controllers\CollectionController@createCollect');
    //返回集合中所有项目的平均值
    Route::get('avg', '\App\Test\Controllers\CollectionController@avg');
    //将集合拆成多个指定大小的较小集合
    Route::get('chunk', '\App\Test\Controllers\CollectionController@chunk');
    //将多个数组组成的集合合成单个一维数组集合
    Route::get('collapse', '\App\Test\Controllers\CollectionController@collapse');
    //将集合的值作为「键」，合并另一个数组或者集合作为「键」对应的值
    Route::get('combine', '\App\Test\Controllers\CollectionController@combine');
    //判断集合是否含有指定项目
    Route::get('contains', '\App\Test\Controllers\CollectionController@contains');
    //返回该集合内的项目总数
    Route::get('count', '\App\Test\Controllers\CollectionController@count');
    //将集合与其它集合或纯 PHP 数组 进行值的比较，返回第一个集合中存在而第二个集合中不存在
    Route::get('diff', '\App\Test\Controllers\CollectionController@diff');
    //将集合与其它集合或纯 PHP 数组 的「键」进行比较，返回第一个集合中存在而第二个集合中不存在「键」所对应的键值对
    Route::get('diffKeys', '\App\Test\Controllers\CollectionController@diffKeys');
    //遍历集合中的项目，并将之传入回调函数
    Route::get('each', '\App\Test\Controllers\CollectionController@each');
    //判断集合中每一个元素是否都符合指定条件
    Route::get('every', '\App\Test\Controllers\CollectionController@every');
    //返回集合中除了指定键以外的所有项目
    Route::get('except', '\App\Test\Controllers\CollectionController@except');
    //使用回调函数筛选集合，只留下那些通过判断测试的项目
    Route::get('filter', '\App\Test\Controllers\CollectionController@filter');
    //返回集合第一个通过指定测试的元素
    Route::get('first', '\App\Test\Controllers\CollectionController@first');
    //对集合内所有子集遍历执行回调，并在最后转为一维集合
    Route::get('flatMap', '\App\Test\Controllers\CollectionController@flatMap');
    //将多维集合转为一维集合
    Route::get('flatten', '\App\Test\Controllers\CollectionController@flatten');
    //将集合中的键和对应的数值进行互换
    Route::get('flip', '\App\Test\Controllers\CollectionController@flip');
    //通过集合的键来移除掉集合中的一个项目
    Route::get('forget', '\App\Test\Controllers\CollectionController@forget');
    //返回可用来在指定页码上所显示项目的新集合。这个方法第一个参数是页码数，第二个参数是每页显示的个数
    Route::get('forPage', '\App\Test\Controllers\CollectionController@forPage');
    //返回指定键的项目
    Route::get('get', '\App\Test\Controllers\CollectionController@get');
    //根据指定的「键」为集合内的项目分组
    Route::get('groupBy', '\App\Test\Controllers\CollectionController@groupBy');
    //检查集合中是否含有指定的「键」
    Route::get('has', '\App\Test\Controllers\CollectionController@has');
    //implode 方法合并集合中的项目
    Route::get('implode', '\App\Test\Controllers\CollectionController@implode');
    //移除任何指定 数组 或集合内所没有的数值。最终集合保存着原集合的键
    Route::get('intersect', '\App\Test\Controllers\CollectionController@intersect');
    //如果集合是空的，isEmpty 方法会返回 true：否则返回 false
    Route::get('isEmpty', '\App\Test\Controllers\CollectionController@isEmpty');
    //以指定键的值作为集合项目的键。如果几个数据项有相同的键，那在新集合中只显示最后一项
    Route::get('keyBy', '\App\Test\Controllers\CollectionController@keyBy');
    //返回该集合所有的键
    Route::get('keys', '\App\Test\Controllers\CollectionController@keys');
    //返回集合中，最后一个通过指定测试的元素
    Route::get('last', '\App\Test\Controllers\CollectionController@last');
    //遍历整个集合并将每一个数值传入回调函数。回调函数可以任意修改并返回项目，形成修改过的项目组成的新集合
    Route::get('map', '\App\Test\Controllers\CollectionController@map');
    //遍历整个集合并将每一个数值传入回调函数。回调函数返回包含一个键值对的关联数组
    Route::get('mapWithKeys', '\App\Test\Controllers\CollectionController@mapWithKeys');
    //计算指定键的最大值
    Route::get('max', '\App\Test\Controllers\CollectionController@max');
    //合并数组进集合。数组「键」对应的数值会覆盖集合「键」对应的数值
    Route::get('merge', '\App\Test\Controllers\CollectionController@merge');
    //计算指定「键」的最小值
    Route::get('min', '\App\Test\Controllers\CollectionController@min');
    //由每隔第 n 个元素组成一个新的集合
    Route::get('nth', '\App\Test\Controllers\CollectionController@nth');
    //返回集合中指定键的所有项目
    Route::get('only', '\App\Test\Controllers\CollectionController@only');
    //将集合传给回调函数并返回结果
    Route::get('pipe', '\App\Test\Controllers\CollectionController@pipe');
    //获取集合中指定「键」所有对应的值
    Route::get('pluck', '\App\Test\Controllers\CollectionController@pluck');
    //移除并返回集合最后一个项目
    Route::get('pop', '\App\Test\Controllers\CollectionController@pop');
    //在集合前面增加一项数组的值
    Route::get('prepend', '\App\Test\Controllers\CollectionController@prepend');
    //把「键」对应的值从集合中移除并返回
    Route::get('pull', '\App\Test\Controllers\CollectionController@pull');
    //在集合的后面新添加一个元素
    Route::get('push', '\App\Test\Controllers\CollectionController@push');
    //在集合内设置一个「键/值」
    Route::get('put', '\App\Test\Controllers\CollectionController@put');
    //random 方法从集合中随机返回一个项目
    Route::get('random', '\App\Test\Controllers\CollectionController@random');
    //reduce 方法将集合缩减到单个数值，该方法会将每次迭代的结果传入到下一次迭代
    Route::get('reduce', '\App\Test\Controllers\CollectionController@reduce');
    //reject 方法以指定的回调函数筛选集合。会移除掉那些通过判断测试（即结果返回 true）的项目
    Route::get('reject', '\App\Test\Controllers\CollectionController@reject');
    //reverse 方法倒转集合内项目的顺序
    Route::get('reverse', '\App\Test\Controllers\CollectionController@reverse');
    //search 方法在集合内搜索指定的数值并返回找到的键。假如找不到项目，则返回 false
    Route::get('search', '\App\Test\Controllers\CollectionController@search');
    //shift 方法移除并返回集合的第一个项目
    Route::get('shift', '\App\Test\Controllers\CollectionController@shift');
    //shuffle 方法随机排序集合的项目
    Route::get('shuffle', '\App\Test\Controllers\CollectionController@shuffle');
    //slice 方法返回集合从指定索引开始的一部分切片
    Route::get('slice', '\App\Test\Controllers\CollectionController@slice');
    //对集合排序。排序后的集合保留着原始数组的键
    Route::get('sort', '\App\Test\Controllers\CollectionController@sort');
    //以指定的键排序集合。排序后的集合保留了原始数组键
    Route::get('sortBy', '\App\Test\Controllers\CollectionController@sortBy');
    //返回从指定的索引开始的一小切片项目，原本集合也会被切除
    Route::get('splice', '\App\Test\Controllers\CollectionController@splice');
    //将集合按指定组数分解
    Route::get('split', '\App\Test\Controllers\CollectionController@split');
    //返回集合内所有项目的总和
    Route::get('sum', '\App\Test\Controllers\CollectionController@sum');
    //返回有着指定数量项目的集合
    Route::get('take', '\App\Test\Controllers\CollectionController@take');
    //将集合转换成纯 PHP 数组。假如集合的数值是 Eloquent 模型，也会被转换成数组
    Route::get('toArray', '\App\Test\Controllers\CollectionController@toArray');
    //将集合转换成 JSON
    Route::get('toJson', '\App\Test\Controllers\CollectionController@toJson');
    //遍历集合并对集合内每一个项目调用指定的回调函数。集合的项目将会被回调函数返回的数值取代掉
    Route::get('transform', '\App\Test\Controllers\CollectionController@transform');
    //将给定的数组合并到集合中，如果数组中含有与集合一样的「键」，集合的键值会被保留
    Route::get('union', '\App\Test\Controllers\CollectionController@union');
    //unique 方法返回集合中所有唯一的项目。
    Route::get('unique', '\App\Test\Controllers\CollectionController@unique');
    //返回「键」重新被设为「连续整数」的新集合
    Route::get('values', '\App\Test\Controllers\CollectionController@values');
    //当第一个参数运算结果为 true 的时候，会执行第二个参数传入的闭包
    Route::get('when', '\App\Test\Controllers\CollectionController@when');
    //以一对指定的「键／数值」筛选集合
    Route::get('where', '\App\Test\Controllers\CollectionController@where');
    //基于参数中的键值数组进行过滤：
    Route::get('whereIn', '\App\Test\Controllers\CollectionController@whereIn');
    //zip 方法将集合与指定数组相同索引的值合并在一起
    Route::get('zip', '\App\Test\Controllers\CollectionController@zip');
    //高阶信息传递
    Route::get('HighOrder', '\App\Test\Controllers\CollectionController@HighOrder');

});

//todo:模型关联
Route::group(['prefix' => 'eloquent_relate'], function (){
    //一对一
    Route::get('one_to_one', '\App\Test\Controllers\EloquentRelateController@one_to_one');
    //反向一对一
    Route::get('reverse_one_to_one', '\App\Test\Controllers\EloquentRelateController@reverse_one_to_one');
    //一对多
    Route::get('one_to_many', '\App\Test\Controllers\EloquentRelateController@one_to_many');
    //反向一对多
    Route::get('reverse_one_to_many', '\App\Test\Controllers\EloquentRelateController@reverse_one_to_many');
    //多对多
    Route::get('many_to_many', '\App\Test\Controllers\EloquentRelateController@many_to_many');
    //远程一对多
    Route::get('away_many_to_many', '\App\Test\Controllers\EloquentRelateController@away_many_to_many');
    //多态关联
    Route::get('status_many', '\App\Test\Controllers\EloquentRelateController@status_many');
    //多态多对多
    Route::get('status_many_to_many', '\App\Test\Controllers\EloquentRelateController@status_many_to_many');
    //查找关联
    Route::get('eloquent_where', '\App\Test\Controllers\EloquentRelateController@eloquent_where');
    //动态属性 关联方法
    Route::get('auto_attribute', '\App\Test\Controllers\EloquentRelateController@auto_attribute');
    //查找关联是否存在
    Route::get('eloquent_exist', '\App\Test\Controllers\EloquentRelateController@eloquent_exist');
    //关联统计
    Route::get('relate_count', '\App\Test\Controllers\EloquentRelateController@relate_count');
    //预加载
    Route::get('preload', '\App\Test\Controllers\EloquentRelateController@preload');
});