<?php

namespace App\Http\Controllers;

use App\User;
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

    /**
     * 注册
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register()
    {
        //表单验证
        $this->validate(\request(), [
            'name' => 'required|max:10|min:3|unique:users,name',    //必须/最长10/最短3/在表users中必须唯一
            'email' => 'required|unique:users,email|email', //email代表验证邮箱
            'password' => 'required|min:6|max:10|confirmed'  //confirmed 代表验证密码是否一致
        ]);
        //逻辑
        $name = \request('name');
        $email = \request('email');
        $password = bcrypt(\request('password'));   //密码要加密
        User::create(compact('name', 'email', 'password'));
        //渲染
        return redirect('/login');
    }
}
