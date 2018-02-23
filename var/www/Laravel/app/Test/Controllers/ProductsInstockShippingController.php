<?php
namespace App\Test\Controllers;
use App\AdminUser;
use App\Policies\ProductsInstockShipping;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



/**
 * Gate Policy 权限测试
 * Class ProductsInstockShippingController
 * @package App\Test\Controllers
 */
class ProductsInstockShippingController extends Controller
{

    /**
     * 通过中间件 使用 Gate授权
     * @return string
     */
    public function update_gate()
    {
        return 'update_gate';
    }


    public function update_gate_allow()
    {
        $shipping = \App\ProductsInstockShipping::all()->first();
        if (Gate::allows('update-ship', $shipping)) {
            // 当前用户可以更新 post...
            return 'update_gate_allow';
        }
        if (Gate::denies('update-ship', $shipping)) {
            // 当前用户不能更新 post...
            return 'denies';
        }
    }

    /**
     * 如果需要指定一个特定的用户可以访问某个动作，可以使用 Gate facade 中的 forUser 方法
     */
    public function appoint_gate()
    {
        /*
        * 如果需要指定一个特定的用户可以访问某个动作，可以使用 Gate facade 中的 forUser 方法：
         *
         * 如果你想要使用 Gate 来检查非当前登录用户是否具备相应的能力，你可以使用 forUser 方法
         *
         * 默认情况下 Gate  是检查  当前登录用户的权限   Gate::forUser($user)  用来检查指定用户的权限
         * */
        $user = AdminUser::find(3);  //longco 123456
        $shipping = \App\ProductsInstockShipping::all()->first();
        if (Gate::forUser($user)->allows('update-ship', $shipping)) {
            // 指定用户可以更新 ship...
            return 'allow';
        }

        if (Gate::forUser($user)->denies('update-ship', $shipping)) {
            // 指定用户不能更新 ship...
            return 'denies';
        }

    }

    /**
     * 使用模型策略授权
     * @return string
     */
    public function update_policy()
    {
        $user = Auth::user();
        $shipping = \App\ProductsInstockShipping::find(255);

        //授权
        if ($user->can('update', $shipping)) {
            return 'allow';
        }else{
            return 'denise';
        }
    }

    /**
     * 不需要使用模型的策略方法
     * @return string
     */
    public function create_policy()
    {
        $user = Auth::user();
        //不需要传入模型的授权
        if ($user->can('create', \App\ProductsInstockShipping::class)) {
            //类名用来判断使用哪一个策略类  注意和模型实例区别
            // 执行相关策略中的「create」方法...
            return 'allow';
        }else{
            return 'denise';
        }
    }

    /**
     * 使用辅助函数 进行策略权限验证
     */
    public function authorize_policy()
    {
        $shipping = \App\ProductsInstockShipping::find(255);
        $this->authorize('update', $shipping);
        return 'allow';
    }

    /**
     * 使用辅助函数 进行 不需要模型的策略权限验证
     */
    public function authorize_policy_none_model()
    {
        $this->authorize('create', \App\ProductsInstockShipping::class);
        return 'allow';
    }

    /**
     * 使用中间件 进行策略权限验证
     * @param \App\ProductsInstockShipping $shipping
     * @return string
     */
    public function middleware_policy(\App\ProductsInstockShipping $productsInstockShipping)
    {
       // dd(\App\ProductsInstockShipping::all()->first());
        dd($productsInstockShipping);
    }

    /**
     * 使用中间件 进行不需要模型的策略权限验证
     * @return string
     */
    public function middleware_policy_none()
    {
        return 'allow';
    }
}
?>