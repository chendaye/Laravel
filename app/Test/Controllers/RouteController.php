<?php
namespace App\Test\Controllers;
use Illuminate\Routing\Route;

/**
 * 首页
 * Class HomeController
 * @package App\Admin\Controllers
 */
class RouteController extends Controller
{
    /**
     * RouteController constructor.
     */
    public function __construct()
    {
        //给控制器指定中间件
        $this->middleware('auth');

        $this->middleware('log')->only('index');

        $this->middleware('subscribed')->except('store');
    }

    /**
     * 路由别名
     * @return \Illuminate\Http\RedirectResponse
     */
    public function named()
    {
        //为路由指定了名称后，我们可以使用全局辅助函数 route 来生成 URL 或者重定向到该条路由

        //生成路由
        $url = route('profile');
        //return $url;

        //重定向
        //return redirect()->route('profile');

        //可以传递参数
        return redirect()->route('profile_name', ['name' => '路由名重定向']);

    }

    /**
     * 指定路由组命名空间
     * 貌似只支持 App/Http/Controllers
     * @return string
     */
    public function name_space()
    {
        return 'namespace';
    }

    /**
     * 获取当前路由信息
     * @return array
     */
    public function current()
    {
        $route = Route::current();

        $name = Route::currentRouteName();

        $action = Route::currentRouteAction();

        return [
            $route,
            $name,
            $action
        ];
    }
}
?>