<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 前置中间件  请求被应用处理 之前 执行一些任务
 * Class TestBeforeMiddleware
 * @package App\Http\Middleware
 */
class BeforeMiddleware
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
