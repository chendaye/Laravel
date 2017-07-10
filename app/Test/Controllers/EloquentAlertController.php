<?php
namespace App\Test\Controllers;
use App\ProductsInstockShipping;
use Carbon\Carbon;


/**
 * 访问器和修改器可以让你修改 Eloquent 模型中的属性或者设置它们的值，
 * 例如，你可能想要使用 Laravel 加密器 来加密一个被保存在数据库中的值，当你从 Eloquent 模型访问该属性时该值将被自动解密。
 * 除了自定义访问器和修改器之外，
 * Eloquent 也会自动将日期字段类型转换成 Carbon 实例或将 文本字段类型转换成 JSON
 *
 * 模型修改器
 * Class EloquentAlertController
 * @package App\Test\Controllers
 */
class EloquentAlertController extends Controller
{

    /**
     * 定义一个访问器
     * 若要定义一个访问器，则须在你的模型上创建一个 getFooAttribute 方法。
     * 要访问的 Foo 字段需使用「驼峰式」来命名。
     * 在这个例子中，我们将为 first_name 属性定义一个访问器。
     * 当 Eloquent 尝试获取 first_name 的值时，将会自动调用此访问器*/
    public function visit()
    {
        $shipping = ProductsInstockShipping::withoutGlobalScopes()->find(360);
        dump($shipping->orders_id);
    }

    /**
     * 定义一个修改器
     * 若要定义一个修改器，则须在模型上定义一个 setFooAttribute 方法。
     * 要访问的 Foo 字段需使用「驼峰式」来命名。
     * 让我们再来定义 first_name 属性的修改器。
     * 当我们尝试在模型上设置 first_name 的值时，将会自动调用此修改器
     */
    public function alert()
    {
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        $shipping->orders_id = 66666;
        $shipping->save();
    }

    /**
     * 日期转换器
     */
    public function date()
    {
        /*
         * 日期转换器
         * 默认情况下，Eloquent 将会把 created_at 和 updated_at 字段转换成 Carbon 实例，它提供了各种各样的方法，并继承了 PHP 原生的 DateTime 类。
         * 你可以在模型中自定义哪些字段需要被自动修改，或完全禁止修改，可通过重写模型的 $dates 属性来实现：*/

        /*
         * 当某个字段被认为是日期时，你或许想将其数值设置成一个 UNIX 时间戳、日期字符串（Y-m-d）、日期时间（ date-time ）字符串，
         * 当然还有 DateTime 或 Carbon 实例，然后日期数值将会被自动保存到数据库中*/
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        $shipping->sales_add_time = Carbon::now();
        $shipping->save();

        /*
         * 在 $dates 属性中列出的所有属性被获取到时，都将会自动转换成 Carbon 实例，让你可在属性上使用任何 Carbon 方法：*/
        $shipping =  ProductsInstockShipping::withoutGlobalScopes()->find(360);
        $time = $shipping->sales_add_time->getTimestamp();
        dd($time);
    }
}
?>