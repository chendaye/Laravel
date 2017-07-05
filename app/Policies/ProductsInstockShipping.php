<?php

namespace App\Policies;

use App\AdminUser;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductsInstockShipping
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 用户是否能更新
     * @param AdminUser $user
     * @param \App\ProductsInstockShipping $shipping
     * @return mixed
     */
    public function update(AdminUser $user, \App\ProductsInstockShipping $shipping)
    {
        return ($user->id <= $shipping->products_instock_id);
    }

    /**
     * 一些策略方法只接受当前认证通过的用户作为参数，而不用传入授权相关的模型实例
     * 当定义一个不需要传入模型实例的策略方法时，比如 create 方法，
     * 你需要定义这个方法只接受已授权的用户作为参数
     * @param AdminUser $user
     * @return mixed
     */
    public function create(AdminUser $user)
    {
        return ($user->id == 1);
    }

    /**
     * 策略过滤器
     * 对特定用户，你可能希望通过指定的策略授权所有动作。要达到这个目的，可以在策略中定义一个 before 方法。
     * before 方法会在策略中其他所有方法之前执行，这样提供了一种方式来授权动作而不是指定的策略方法来执行判断。
     * 这个功能最常见的场景是授权应用的管理员可以访问所有动作：
     *
     * 如果你想拒绝用户所有的授权，你应该在 before 方法中返回 false。
     * 如果返回的是 null，则通过其他的策略方法来决定授权与否
     * @param $user
     * @param $ability
     * @return bool
     */
  /*  public function before($user, $ability)
    {
        return true;
        //也是一个策略方法
        if ($user->isSuperAdmin()) {
            return true;
        }
    }*/
}
