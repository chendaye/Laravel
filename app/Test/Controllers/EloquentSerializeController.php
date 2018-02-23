<?php
namespace App\Test\Controllers;
use App\ProductsInstockShipping;

/**
 * 模型序列化
 * 在创建 JSON API 的时候，经常会需要将模型和关联转换成数组或 JSON。
 * Eloquent 提供了一些便捷的方法来让我们可以完成这些转换，以及控制哪些属性需要被包括在序列化中
 *
 * Class EloquentSerializeController
 * @package App\Test\Controllers
 */
class EloquentSerializeController extends Controller
{
    /**
     * 序列化成数组
     * 如果要将模型还有其加载的 关联 转换成一个数组，则可以使用 toArray 方法。
     * 这个方法是递归的，因此，所有属性和关联（包含关联中的关联）都会被转换成数组
     */
    public function toArray()
    {
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        $shipping = $shipping->toArray();

        //可以将所有集合转化为数组
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->get();
        $shipping = $shipping->toArray();
        dd($shipping);
    }

    /**
     * 序列化成 JSON
     * 如果要将模型转换成 JSON，则可以使用 toJson 方法。
     * 如同 toArray 方法一样，toJson 方法也是递归的。因此，所有的属性以及关联都会被转换成 JSON：
     *
     * 当模型或集合被转型成字符串时，模型或集合便会被转换成 JSON 格式，
     * 因此你可以直接从应用程序的路由或者控制器中返回 Eloquent 对象
     */
    public function toJson()
    {
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        $shipping = $shipping->toJson();

        //可以强制把一个模型或集合转型成一个字符串，它将会自动调用 toJson 方法
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(361);
        $shipping = (string)$shipping;

        //可以将所有集合转化为json
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->get();
        $shipping = $shipping->toJson();

        //临时修改属性的可见度
        /*你可以在模型实例后使用 makeVisible 方法来显示通常隐藏的属性，且为了便于使用，makeVisible 方法会返回一个模型实例*/
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        //临时可见
        $shipping = $shipping->makeVisible('is_billing')->toJson();
        //临时不可见
        $shipping = $shipping->makeHidden('is_billing')->toJson();
        dd($shipping);
    }
}
?>