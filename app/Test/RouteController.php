<?php
namespace App\Test\Controllers;

/**
 * 首页
 * Class HomeController
 * @package App\Admin\Controllers
 */
class RouteController extends Controller
{
    public function index()
    {
        return view('test.home.index');
    }
}
?>