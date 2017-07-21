<?php
namespace App\Test\Controllers;
use App\ProductsInstockShipping;
use App\User;

/**
 * 响应
 * Class ResponseController
 * @package App\Test\Controllers
 */
class ResponseController extends Controller
{
    /**
     * 框架会自动把集合转化为json响应
     * @return mixed
     */
    public function eloquent()
    {
        return User::find(1);
    }

    /**
     * 响应对象
     * 一般来说，你不需要从路由方法返回简单的字符串或数组。而是需要返回整个 Illuminate\Http\Response 实例或 视图。
     * 当返回整个 Response 实例时，Laravel 允许自定义响应的 HTTP 状态码和响应头信息。
     * Response 实例继承自 Symfony\Component\HttpFoundation\Response 类，该类提供了丰富的构建 HTTP 响应的方法：
     */
    public function obj()
    {
        return response('Hello World', 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * 附加头信息至响应
     * 大部分的响应方法都是可链式调用的，以使你更酣畅淋漓的创建响应实例。
     * 例如，你可以在响应返回给用户前使用 header 方法向响应实例中附加一系列的头信息
     */
    public function add_header_msg()
    {
        if(1)return response('Hello World')
            ->header('Content-Type', 'text/plain')
            ->header('X-Header-One', 'Header Value')
            ->header('X-Header-Two', 'Header Value');
        //或者
        if(0)return response('Hello World')
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
    }

    /**
     * 一个使用了 Eloquent 模型并需要传递 ID 参数的路由
     * 用来测试 重定向是传递 Eloquent
     * @param ProductsInstockShipping $productsInstockShipping
     */
    public function redirect_eloquent(ProductsInstockShipping $productsInstockShipping)
    {
        dd($productsInstockShipping);
    }

    /**
     * 重定向至控制器行为
     * @return string
     */
    public function index()
    {
        return '重定向至控制器行为';
    }
}
?>