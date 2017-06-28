<?php

namespace App\Providers;

use App\Topic;
use \Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


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
        //todo：5.4 默认编码是 mbstring  4个字节对应一个字符 => 767/4 = 191  原先“max key length is 767 bytes”
        Schema::defaultStringlength(191);


        //todo:给每一个页面注入专题模块
        \View::composer('layout.sidebar', function ($view){
            $topics =  Topic::all();
            $view->with('topics', $topics);
        });
        //给头部注入 当前用户
        \View::composer('layout.nav', function ($view){
            $user =  Auth::user();
            $view->with('user', $user);
        });

        //todo:数据分享给所有视图
        \View::share('key', 'value');

        //todo:自定义验证规则
        /*自定义的验证闭包接收四个参数：要被验证的属性名称 $attribute，属性的值 $value，传入验证规则的参数数组 $parameters，及 Validator 实例。除了使用闭包，
        你也可以传入类和方法到 extend 方法中*/
        Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
            return $value == 'foo';
        });
        Validator::extend('foo', 'FooValidator@validate');


        //todo:自定义模板命令
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
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
