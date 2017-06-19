<?php
namespace App\Admin\Controllers;

/**
 * 首页
 * Class HomeController
 * @package App\Admin\Controllers
 */
class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }
}
?>