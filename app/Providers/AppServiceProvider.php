<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *启动的时候会执行
     * @return void
     */
    public function boot()
    {
        //5.4 默认编码是 mbstring  4个字节对应一个字符 => 767/4 = 191  原先“max key length is 767 bytes”
        Schema::defaultStringlength(191);
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
