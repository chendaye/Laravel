<?php
namespace App\Test\Controllers;
use App\AdminPower;
use Carbon\Carbon;

/**
 * 辅助函数
 * Class AssistController
 * @package App\Test\Controllers
 */
class AssistController extends Controller
{
    /**
     * 数组辅助函数
     */
    public function arr()
    {
        //如果给定的键不存在与数组中，array_add 就会把给定的键值对添加到数组中
        $array = array_add(['name' => 'Desk'], 'price', 100);

        //array_collapse 函数把数组里的每一个数组合并成单个数组
        $array = array_collapse([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);

        //array_divide 函数返回两个数组，一个包含原本数组的键，另一个包含原本数组的值
        list($keys, $values) = array_divide(['name' => 'Desk']);

        //array_dot 函数把多维数组压制成一维数组，并用「点」式语法表示深度
        $array = array_dot(['foo' => ['bar' => 'baz']]);

        //array_except 函数从数组移除指定的键值对：
        $array = ['name' => 'Desk', 'price' => 100];
        $array = array_except($array, ['price']);

        //array_first 函数返回数组中第一个通过指定测试的元素
        $array = [100, 200, 300];
        $value = array_first($array, function ($value, $key) {
            return $value >= 150;
        });
        //可传递第三个参数作为默认值。当没有元素通过测试时，将会返回该默认值
        //$value = array_first($array, $callback, $default);

        //array_flatten 函数将多维数组压制成一维数组
        $array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby']];
        $array = array_flatten($array);

        //array_forget 函数以「点」式语法从深度嵌套的数组中移除指定的键值对
        $array = ['products' => ['desk' => ['price' => 100]]];
        array_forget($array, 'products.desk');

        //array_get 函数使用「点」式语法从深度嵌套的数组中获取指定的值
        $array = ['products' => ['desk' => ['price' => 100]]];
        $value = array_get($array, 'products.desk');
        //array_get 函数同样也接受默认值，如果指定的键找不到时，则返回该默认值
        $value = array_get($array, 'names.john', 'default');

        //array_has 函数使用「点」式语法检查指定的项目是否存在于数组中
        $array = ['product' => ['name' => 'desk', 'price' => 100]];
        $hasItem = array_has($array, 'product.name');
        $hasItems = array_has($array, ['product.price', 'product.discount']);

        //array_last 函数返回数组中最后一个通过指定测试的元素
        $array = [100, 200, 300, 110];
        $value = array_last($array, function ($value, $key) {
            return $value >= 150;
        });

        //array_only 函数从数组返回指定的键值对
        $array = ['name' => 'Desk', 'price' => 100, 'orders' => 10];
        $array = array_only($array, ['name', 'price']);

        //array_pluck 函数从数组拉出一列指定的键值对
        $array = [
            ['developer' => ['id' => 1, 'name' => 'Taylor']],
            ['developer' => ['id' => 2, 'name' => 'Abigail']],
        ];
        //$array = array_pluck($array, 'developer.name');
        //也可以指定要以什么作为结果列的键名
        $array = array_pluck($array, 'developer.name', 'developer.id');

        //array_prepend 函数将元素加到数组的头部
        $array = ['one', 'two', 'three', 'four'];
        $array = array_prepend($array, 'zero');

        //array_pull 函数从数组移除指定键值对并返回该键值对
        $array = ['name' => 'Desk', 'price' => 100];
        $array = array_pull($array, 'name');

        //array_set 函数使用「点」式语法在深度嵌套的数组中写入值
        $array = ['products' => ['desk' => ['price' => 100]]];
        array_set($array, 'products.desk.price', 200);

        //array_sort 函数根据指定闭包的结果排序数组
        $array = [
            ['name' => 'Desk'],
            ['name' => 'Chair'],
        ];
        $array = array_values(array_sort($array, function ($value) {
            return $value['name'];
        }));

        //array_sort_recursive 函数使用 sort 函数递归排序数组
        $array = [
            [
                'Roman',
                'Taylor',
                'Li',
            ],
            [
                'PHP',
                'Ruby',
                'JavaScript',
            ],
        ];
        //对每一个元素排序
        $array = array_sort_recursive($array);

        //array_where 函数使用指定的闭包过滤数组
        $array = [100, '200', 300, '400', 500];
        $array = array_where($array, function ($value, $key) {
            return is_string($value);
        });

        //head 函数返回指定数组的第一个元素
        $array = [100, 200, 300];
        $array = head($array);

        //last 函数返回指定数组的最后一个元素
        $array = [100, 200, 300];
        $last = last($array);
        dump($array);

    }

    /**
     * 路径辅助函数
     */
    public function path()
    {
        //app_path 函数返回 app 文件夹的完整路径。你也可以使用 app_path 函数生成针对指定文件相对于 app 目录的完整路径
        $path = app_path();
        //文件完整路径
        $path = app_path('Http/Controllers/Controller.php');

        //base_path 函数返回项目根目录的完整路径。
        //你也可以使用 base_path 函数生成针对指定文件相对于项目根目录的完整路径
        $path = base_path();

        $path = base_path('vendor/bin');

        //config_path 函数返回 config 目录的完整路径
        $path = config_path();

        //database_path 函数返回 database 目录的完整路径
        $path = database_path();

        //mix 函数获取带有版本号的 mix 文件
        //mix($file);

        //public_path 函数返回 public 目录的完整路径
        $path = public_path();

        //resource_path 函数返回 resources 目录的完整路径。
        //你也可以使用 resource_path 函数生成针对指定文件相对于 resources 目录的完整路径
        $path = resource_path();

        $path = resource_path('assets/sass/app.scss');

        //storage_path 函数返回 storage 目录的完整路径。
        //你也可以使用 storage_path 函数生成针对指定文件相对于 storage 目录的完整路径
        $path = storage_path();

        $path = storage_path('app/file.txt');
        dump($path);
    }

    /**
     * 字符串辅助函数
     */
    public function string()
    {
        //camel_case 函数将指定的字符串转换成 驼峰式命名
        $string = camel_case('foo_bar');

        //class_basename 函数返回不包含命名空间的类名称
        $string = class_basename(AdminPower::class);

        //e 函数对指定字符串进行 htmlentities 把HTML标签也输出来
        echo e('<html>foo</html>');

        //ends_with 函数判断指定字符串结尾是否为指定内容
        $string = ends_with('This is my name', 'name');

        //snake_case 函数将指定的字符串转换成 蛇形命名
        $string = snake_case('fooBar');

        //str_limit 函数限制字符串的字符个数，该函数接受一个字符串作为第一个参数，第二个参数为允许的最大字符个数
        $string = str_limit('The PHP framework for web artisans.', 7);

        //starts_with 函数判断字符串开头是否为指定内容
        $string = starts_with('This is my name', 'This');

        //str_contains 函数判断字符串是否包含有指定内容
        $string = str_contains('This is my name', 'my');
        //也可以传递数组，来判断字符串是否包任意指定内容
        $string = str_contains('This is my name', ['my', 'foo']);

        //str_finish 函数添加指定内容到字符串末尾
        $string = str_finish('this/string', '/');

        //str_is 函数判断指定的字符串是否匹配指定的格式，星号可作为通配符使用
        $string = str_is('foo*', 'foobar');

        $string = str_is('baz*', 'foobar');

        //str_plural 函数把字符串转换成复数形式。该函数目前只支持英文
        $string = str_plural('car');
        $string = str_plural('child');
        //可以传入一个整数作为第二个参数，来获取字符串的单数或复数形式
        $plural = str_plural('child', 2);
        $plural = str_plural('child', 1);

        //str_random 函数生成指定长度的随机字符串。该函数使用了 PHP 自带的 random_bytes 函数
        $string = str_random(40);

        //str_singular 函数把字符串转换成单数形式。该函数目前只支持英文
        $singular = str_singular('cars');

        //str_slug 函数根据指定字符串生成 URL 友好的「slug」
        $string = str_slug('Laravel 5 Framework', '-');

        //studly_case 函数把指定字符串转换成 首字母大写
        $string = studly_case('foo_bar');

        //title_case 函数把指定字符串转换成 每个单词首字母大写
        $string = title_case('a nice title uses the correct case');

        //trans 函数根据你的 本地化文件 翻译指定的语句
        //echo trans('validation.required'):

        //trans_choice 函数根据给定数量来决定翻译指定语句是复数形式还是单数形式
        $value = trans_choice('foo.bar', 777);
        dump($string);
    }

    /**
     * 路由辅助函数
     */
    public function url()
    {
        //action 函数根据指定控制器的方法生成 URL，你不需要传入该控制器的完整命名空间。
        //只需要传入相对于 App\Http\Controllers 命名空间的控制器类名
        $url = action('\App\Test\Controllers\EloquentRelateController@preload');
        //如果该方法接受路由参数，可以作为第二个参数传入：
        $url = action('\App\Test\Controllers\EloquentRelateController@preload', ['id' => 1]);

        //根据当前请求的协议（HTTP 或 HTTPS）生成资源文件的 URL
        $url = asset('img/photo.jpg');

        //使用 HTTPS 协议生成资源文件的 URL
        $url = secure_asset('foo/bar.zip', 'title', $attributes = []);

        //route 函数生成指定路由名称的 URL：
        $url = route('666');
        //该路由接受参数，可以作为第二个参数传入
        $url = route('young', ['name' => 1]);

        //secure_url 数使用 HTTPS 协议生成指定路径的完整 URL
        $url = secure_url('assist/arr');

        $url = secure_url('assist/arr', [1]);

        //url 函数生成指定路径的完整 URL
        $url = url('user/profile');
        $url = url('user/profile', [1]);
        //如果没有提供路径参数，将会返回一个 Illuminate\Routing\UrlGenerator 实例
        $url =  url()->current();
        $url =  url()->full();
        $url =  url()->previous();
        dump($url);
    }

    public function other()
    {
        //abort 函数抛出一个将被异常处理句柄渲染的 HTTP 异常：
        //abort(401);
        //也可以传入异常的响应消息
        //abort(401, 'Unauthorized.');

        //abort_if 函数如果指定的布尔表达式值为 true 则抛出一个 HTTP 异常
        //abort_if(! Auth::user()->isAdmin(), 403);

        //abort_unless 函数如果指定的布尔表达式值为 false 则抛出一个 HTTP 异常
        //abort_unless(Auth::user()->isAdmin(), 403);

        //auth 函数返回一个 authenticator 实例，可以使用它来代替 Auth facade
        $user = auth()->user();

        //back() 函数生成一个重定向响应让用户返回到之前的位置
        //return back();

        //bcrypt 函数使用 Bcrypt 算法哈希指定的数值。你可以使用它代替 Hash facade
        $password = bcrypt('my-secret-password');

        //cache 函数尝试从缓存获取给定 key 的值。如果 key 不存在则返回默认值
        $value = cache('key');
        $value = cache('key', 'default');
        //可以传递键值对来设置缓存，第二个参数可以指定缓存的过期时间，单位分钟
        cache(['key' => 'value'], 5);

        cache(['key' => 'value'], Carbon::now()->addSeconds(10));
        $value = cache('key');

        //collect 函数根据指定的数组生成 集合 实例：
        $collection = collect(['taylor', 'abigail']);

        //config 函数用于获取配置信息的值，配置信息的值可通过「点」式语法访问，其中包含要访问的文件名以及选项名。
        //可传递一个默认值作为第二参数，当配置信息不存在时，则返回该默认值
        $value = config('app.timezone');

        $value = config('app.timezone', 777);
        //config 辅助函数也可以在运行期间，根据指定的键值对设置指定的配置信息
        config(['app.debug' => true]);

        //csrf_field 函数生成包含 CSRF 令牌内容的 HTML 表单隐藏字段
        //{{ csrf_field() }}

        //csrf_token 函数获取当前 CSRF 令牌的内容
        $token = csrf_token();

        //dd 函数输出指定变量的值并终止脚本运行
       // dd(1, 2, 3);
        //如果你不想终止脚本运行，使用 dump 函数代替

        //dispatch 函数把一个新任务推送到 Laravel 的 任务队列中
        //dispatch(new App\Jobs\SendEmails);


        //env 函数获取环境变量值或返回默认值：
        $env = env('APP_ENV');

        //event 函数派发指定的 事件 到所属的侦听器
        //event(new UserRegistered($user));

        //factory 函数根据指定类、名称以及数量生成模型工厂构造器（model factory builder）。
        //可用于 测试 或 数据填充
//        $user = factory(App\User::class)->make();
//        $user = factory(App\User::class, 100)->create();

        //info 函数以 info 级别写入日志:
        info('Some helpful information!');

        //logger()
        logger('Debug message');
        //同时支持传入数组作为参数
        logger('User has logged in.', ['id' => $user->id]);
        //如果没有传入参数，则会返回一个 日志 的实例
        logger()->error('You are not allowed here.');

        //method_field 函数生成模拟各种 HTTP 动作请求的 HTML 表单隐藏字段
     /*   <form method="POST">
            {{ method_field('DELETE') }}
        </form>*/


     //old 函数 获取 session 内一次性的历史输入值
        $value = old('value');

        $value = old('value', 'default');


        //redirect 函数返回一个 HTTP 重定向响应，如果调用时没有传入参数则返回 redirector 实例
        /*return redirect('/home');

        return redirect()->route('route.name');*/


        //request 函数返回当前 请求 实例或获取输入的项目
        $request = request();

        $value = request('key', $default = null);
        dump($value);

        //response 函数创建一个 响应 实例或获取一个 response 工厂实例
//        return response('Hello World', 200, $headers);
//
//        return response()->json(['foo' => 'bar'], 200, $headers);

        //retry 函数将会重复调用给定的回调函数，最多调用指定的次数。如果回调函数没有抛出异常并且有值返回，则 retry 函数返回该值。如果回调函数抛出异常，retry 函数将拦截异常并自动再次调用回调函数，直到调用给定的次数。
        //如果重试次数超出给定次数，拦截的异常将会抛出
//        return retry(5, function () {
//            // Attempt 5 times while resting 100ms in between attempts...
//        }, 100);

        //session 函数可用于获取或设置单个 session 项
        $value = session('key');
        //可以通过传递键值对数组给该函数设置 session 项
        session(['chairs' => 7, 'instruments' => 3]);
        //该函数在没有传递参数时，将返回 session 实例
        $value = session()->get('key');

        session()->put('key', $value);


        //value 函数返回指定数值。而当你传递一个 闭包 给该函数时，该 闭包 将被运行并返回该 闭包 的运行结果
        $value = value(function() { return 'bar'; });
        dump($value);

        //view 函数获取 视图 实例
        return view('auth.login');
    }
}
?>