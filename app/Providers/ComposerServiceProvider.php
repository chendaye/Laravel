<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * 视图合成器是在视图渲染时调用的一些回调或者类方法。如果你需要在某些视图渲染时绑定一些数据上去，
 * 那么视图合成器就是你的的不二之选，另外他还可以帮你将这些绑定逻辑整理到特定的位置。
 * 下面例子中，我们会在一个 服务提供者 中注册一些视图合成器。
 * 同时使用 View Facade 来访问 Illuminate\Contracts\View\Factory contract 的底层实现。
 * 注意：Laravel 没有存放视图合成器的默认目录，但你可以根据自己的喜好来重新组织，例如：App\Http\ViewComposers
 *
 * Class ComposerServiceProvider
 * @package App\Providers
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        //效果是每次 profile 视图渲染时，都会执行 ProfileComposer@compose 方法
        View::composer(
            'profile', 'App\Http\ViewComposers\ProfileComposer'
        );
        //可以附加到多个视图
        View::composer(
            ['profile', 'dashboard'],
            'App\Http\ViewComposers\MyViewComposer'
        );

        //composer 方法同时也接受通配符 * ，可以让你将一个视图合成器一次性绑定到所有的视图：
        View::composer('*', function ($view) {
            //
        });

        // Using Closure based composers...
        View::composer('dashboard', function ($view) {
            $u =  User::find(1);
            $view->with('u', $u);
        });

        //视图构造器
        // 视图 构造器 和视图合成器非常相似。
        //不同之处在于：视图构造器在视图实例化时执行，而视图合成器在视图渲染时执行。如下，可以使用 creator 方法来注册一个视图塑造器：
        View::creator('profile', 'App\Http\ViewCreators\ProfileCreator');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
?>