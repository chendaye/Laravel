<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 后置中间件  请求被应用处理 之后 执行它的任务
 * Class TestAfterMiddleware
 * @package App\Http\Middleware
 */
class AfterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
