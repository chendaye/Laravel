<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * 注册页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //展示注册页面
        return view('register.index');
    }

    public function register()
    {
        //表单验证
        $this->validate([]);
        //逻辑
        //渲染
    }
}
