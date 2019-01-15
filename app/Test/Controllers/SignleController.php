<?php
namespace App\Test\Controllers;
use App\User;

/**
 * 单一操作控制器
 * 如果想定义一个只处理单个操作的控制器，你可以在控制器中只设置一个 __invoke 方法：
 * Class HomeController
 * @package App\Admin\Controllers
 */
class SignleController extends Controller
{
    /**
     * 单一方法控制器
     */
    public function __invoke()
    {
        dd(User::find(1));
    }
}
?>