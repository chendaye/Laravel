<?php
namespace App\Test\Controllers;
use App\Policies\ProductsInstockShipping;
use App\User;
use Illuminate\Support\Facades\Gate;



/**
 * Gate Policy 权限测试
 * Class ProductsInstockShippingController
 * @package App\Test\Controllers
 */
class ProductsInstockShippingController extends Controller
{

    public function update()
    {
        return 'update';
    }

    /**
     * 用中间件 使用 Gate授权
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
}
?>