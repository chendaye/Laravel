<?php

namespace App\Providers;

use App\Topic;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 每一个请求都会经过这里
     * Bootstrap any application services.
     *启动的时候会执行
     * @return void
     */
    public function boot()
    {
        //5.4 默认编码是 mbstring  4个字节对应一个字符 => 767/4 = 191  原先“max key length is 767 bytes”
        Schema::defaultStringlength(191);
        //给每一个页面注入专题模块
        \View::composer('layout.sidebar', function ($view){
            $topics =  Topic::all();
            $view->with('topics', $topics);
        });
    }

    /**
     * Register any application services.
     *启动之后会执行
     * @return void
     */
    public function register()
    {
        //
    }
}
