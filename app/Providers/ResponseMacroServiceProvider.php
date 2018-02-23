<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

/**
 * 如果你想要自定义可以在很多路由和控制器重复使用的响应，
 * 可以使用 Response Facade 实现的 macro 方法。
 * 举个例子，来自 服务提供者的 boot 方法：
 *
 * return response()->caps('foo');
 *
 * Class ResponseMacroServiceProvider
 * @package App\Providers
 */
class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * 注册应用的响应宏
     *macro 函数第一个参数为宏名称，第二个参数为闭包函数。
     * 宏的闭包函数会在 ResponseFactory 的实现或者辅助函数 response 调用宏名称的时候运行：
     * @return void
     */
    public function boot()
    {
        Response::macro('caps', function ($value) {
            return Response::make(strtoupper($value));
        });
    }
}
?>