<?php

namespace App\Providers;

use App\AdminPower;
use App\ProductsInstockShipping;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
        //注册 post模型  策略类
        'App\Post' => 'App\Policies\PostPolicy',
        //注册 ProductsInstockShipping  策略类
        'App\ProductsInstockShipping' => 'App\Policies\ProductsInstockShipping',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //所有权限
        /*
         * Gates 提供了一个简单、基于闭包的方式来授权认证。策略则和控制器类似，在特定的模型或者资源中通过分组来实现授权认证的逻辑
         * 在你的应用中，不要将 gates 和策略当作相互排斥的方式。大部分应用很可能同时包含 gates 和策略，并且能很好的工作。
         * Gates 大部分应用在模型和资源无关的地方，
         * 比如查看管理员的面板。与此相比，策略应该用在特定的模型或者资源中。
         * */
        $powers = AdminPower::all();
        foreach ($powers as $power){
            /*
             * 详情参见文档用户授权
             *
             * Gates 接受一个用户实例作为第一个参数，接受可选参数，比如相关的 Eloquent 模型：
             *
             * 给每一个 权限定义一个 Gate  其名称就是权限名
             * 如果给定模型的 策略已被注册，can 方法会自动调用核实的策略方法并且返回 boolean 值。
             * 如果没有策略注册到这个模型，can 方法会尝试调用和动作名相匹配的基于闭包的 Gate。
             *
             * */
            Gate::define($power->name, function ($user) use($power){
                return $user->hasPower($power);
            });
        }

        //定义一个Gate
        $shipping = ProductsInstockShipping::find(6);
        Gate::define('update-shipping', function ($user)use($shipping) {
            return $user->id <= $shipping->products_instock_id;
        });

        Gate::define('update-ship', function ($user, $ship){
            return ($user->id != $ship->products_instock_id);
        });
    }
}
