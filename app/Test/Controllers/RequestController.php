<?php
namespace App\Test\Controllers;
use Illuminate\Http\Request;
use Mockery\Matcher\Closure;

/**
 * 请求
 * Class RequestController
 * @package App\Test\Controllers
 */
class RequestController extends Controller
{
    /**
     * 要通过依赖注入的方式来获取当前 HTTP 请求的实例，
     * 你应该在控制器方法中使用 Illuminate\Http\Request 类型提示。当前的请求实例将通过 服务容器 自动注入：
     * @param Request $request
     * @return string
     */
    public function request(Request $request)
    {
        dd($request);
    }

    /**
     * 依赖注入 & 路由参数
     * 依赖注入是框架在调用控制器方法时通过反射做的
     * @param $id
     * @param Request $request
     */
    public function param($id, Request $request)
    {
        dump([
            $request,
            $id,
        ]);
    }

    //闭包
    public function closure()
    {
        call_user_func(function (Request $request){
            dd($request);
        }, new Request());
    }

    /**
     * 获取请求路径
     * @param Request $request
     */
    public function path(Request $request)
    {
        //path 方法返回请求路径信息。所以，如果收到的请求目标地址是 http://domain.com/foo/bar，
        //那么 path 将会返回 foo/bar：
        $uri = $request->path();

        //is 方法可以验证收到的请求路径和指定规则是否匹配。使用这个方法的时候你也可以传递一个 * 字符作为通配符：
        if ($request->is('request/*')) {
            dump($uri);
        }
    }

    /**
     * 你可以使用 url 或 fullUrl 方法去获取传入请求的完整 URL。
     * url 方法返回不带有查询字符串的 URL，而 fullUrl 方法的返回值包含查询字符串：
     * @param Request $request
     */
    public function url(Request $request)
    {
        // Without Query String...
        $url = $request->url();
        dump($url);
        // With Query String...
        $url = $request->fullUrl();
        dump($url);
    }

    /**
     * 获取请求方法
     * 对于传入的请求 method 方法将返回 HTTP 的请求方式。
     * 你也可以使用 isMethod 方法去验证 HTTP 的请求方式与指定规则是否相配：
     * @param Request $request
     */
    public function method(Request $request)
    {
        $method = $request->method();

        if ($request->isMethod('get')) {
            dd($method);
        }
    }

    /**
     * 获取指定输入值
     * @param Request $request
     */
    public function input(Request $request)
    {
        //all 方法以 数组 形式获取到所有输入数据:
        $input = $request->all();
        dump($input);

        //input 方法通常被用来获取用户输入数据：
        $name = $request->input('name');
        dump($name);

        //给 input 方法的第二个参数传入一个默认值。当请求的输入数据不存在于此请求时，返回该默认值：
        $name = $request->input('name', 'Sally');
        dump($name);

        //如果传输表单数据中包含「数组」形式的数据，那么可以使用「点」语法来获取数组：
        $name = $request->input('products.0.name');
        dump($name);

        $names = $request->input('products.*.name');
        dump($name);

        //通过动态属性获取输入数据 如果你应用程序表单中包含 name 字段，那么可以像这样访问提交的值
        //优先级是，先从请求的数据中查找，没有的话再到路由参数中找
        $name = $request->name;
        dump($name);

        //获取 JSON 输入信息
        //当你发送 JSON 请求到应用时，只要请求表头中设置了 Content-Type 为 application/json，
        //你就可以直接从 Input 方法中获取 JSON 数据。你也可以通过 「点」语法来读取 JSON 数组
        $name = $request->input('user.name');

        //获取部分输入数据
        //如果你需要获取输入数据的子集，则可以用 only 和 except 方法。
        //这两个方法都接收单个 数组 或动态列表作为参数：
        $input = $request->only(['username', 'password']);
        $input = $request->except('credit_card');

        //获取请求中实际存在的输入数据时，你可以使用 intersect 方法。
        $input = $request->intersect(['name', 'password']);
        dump($input);

        //确定是否有输入值
        //要判断请求是否存在该数据，可以使用 has 方法。当数据存在 并且 字符串不为空时，has 方法就会返回 true
        if ($request->has('name')) {
            dump($request->name);
        }
    }

    /**
     * Laravel 允许你将本次的输入数据保留到下一次请求发送前。这个特性在表单验证错误后重新填写表单相当有用。
     * 但是，如果你使用了 Laravel 的 验证特性，你就不需要在手动实现这些方法，
     * 因为 Laravel 内置的验证工具会自动调用他们。
     * @param Request $request
     */
    public function oldData(Request $request)
    {
        //将输入数据闪存至 Session
        //Illuminate\Http\Request 的 flash 方法会将当前输入的数据存进 session 中，
        //因此下次用户发送请求到应用程序时就可以使用它们：
        $request->flash();

        //可以使用 flashOnly 和 flashExcept 方法将当前请求数据的子集保存到 session
        $request->flashOnly(['name']);
        $request->flashExcept('password');

        //闪存输入数据到 Session 后重定向
        //把输入数据闪存到 session 并重定向到前一个页面，这时只需要在重定向方法后加上 withInput
        if(1)return redirect('form')->withInput();
        if(2)return redirect('form')->withInput(
            $request->except('password')
        );

        //获取旧输入数据
        //若要获取上一次请求后所闪存的输入数据，则可以使用 Request 实例中的 old 方法。
        //old 方法提供一个简便的方式从 Session 取出被闪存的输入数据：
        $username = $request->old('username');

        //可以使用更加方便的 old 辅助函数。如果旧数据不存在，则返回 null
        old('username');

        //将 Cookies 附加到响应
        //可以使用 cookie 方法附加一个 cookie 到 Illuminate\Http\Response 实例。
        //有效 cookie 应该传递字段名称，字段值和过期时间给这个方法
        if(1)return response('Hello World')->cookie(
            'name', 'value', $minutes = 3600
        );
        //cookie 方法还可以接受更多参数，只是使用频率较低。通常作用是传递参数给 PHP 原生 设置 cookie 方法：
        if(1)return response('Hello World')->cookie(
            'name', 'value', $minutes=3600, $path='', $domain='', $secure='', $httpOnly=[]
        );

        //生成 Cookie 实例
        //如果你想要在一段时间以后生成一个可以给定 Symfony\Component\HttpFoundation\Cookie 的响应实例，
        //你可以先生成 $cookie 实例，
        //然后再指定给 response 实例，否则这个 cookie 不会被发送回客户端：
        $cookie = cookie('name', 'value', $minutes=3600);
        return response('Hello World')->cookie($cookie);
    }

    /**
     * 文件资源
     * UploadedFile 的实例还有许多可用的方法，可以到该对象的 API 文档 了解这些方法的详细信息
     * @param Request $request
     */
    public function file(Request $request)
    {
        //获取上传文件
        //可以使用 Illuminate\Http\Request 实例中的 file 方法获取上传的文件。
        //file 方法返回的对象是 Symfony\Component\HttpFoundation\File\UploadedFile 类的实例，
        //该类继承了 PHP 的 SplFileInfo 类，并提供了许多和文件交互的方法
        $file = $request->file('photo');
        $file = $request->photo;

        //使用请求的 hasFile 方法确认上传的文件是否存在
        if ($request->hasFile('photo')) {
            //
        }

        //确认上传的文件是否有效
        if ($request->file('photo')->isValid()) {
            //
        }

        //文件路径 & 扩展
        //UploadedFile 这个类也包含了访问文件完整路径和扩展的方法。
        //extension 方法会尝试根据文件内容猜测文件的扩展名。猜测结果可能不同于客户端原始的扩展名
        $path = $request->photo->path();

        $extension = $request->photo->extension();

        //储存上传文件
        //在设置好 文件系统 的配置信息后，你可以使用 UploadedFile 的 store 方法把上传文件储存到本地磁盘

        //store 方法允许存储文件到相对于文件系统根目录配置的路径。
        //这个路径不能包含文件名，名称将使用 MD5 散列文件内容自动生成。
        $path = $request->photo->store('images');

        //store 方法还接受一个可选的第二个参数，用于文件存储到磁盘的名称。
        //这个方法会返回文件相对于磁盘根目录的路径
        $path = $request->photo->store('images', 's3');

        //如果你不想自动生成文件名，那么可以使用 storeAs 方法去设置路径，文件名和磁盘名作为方法参数
        $path = $request->photo->storeAs('images', 'filename.jpg');

        $path = $request->photo->storeAs('images', 'filename.jpg', 's3');
    }
}