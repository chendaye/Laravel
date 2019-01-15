<?php

namespace App\Http\Middleware;

use Closure;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $param
     * @return mixed
     */
    public function handle($request, Closure $next, $param = null)
    {
        /**
         * 在这个中间件内，我们仅允许请求的 age 参数大于 200 时访问该路由，否则，会将用户请求重定向到 指定 URI  profile
         * 若请求参数 age 小于等于 200，中间件将返回给客户端 HTTP 重定向，反之应用程序才会继续处理该请求。
         * 若将请求继续传递到应用程序（即允许通过中间件验证），只需将 $request 作为参数调用 $next 回调函数
         */
        if ($request->age <= 200) {
            //todo:
            //return redirect('first');
            dump('middleware-test');
            dump($param);
        }
        return $next($request);
    }
}
