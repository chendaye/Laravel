<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /**
     * 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * 登录
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        //表单验证
        $this->validate(\request(), [
            'email' => 'required|email', //email代表验证邮箱
            'password' => 'required|min:6|max:10',  //confirmed 代表验证密码是否一致
            'is_remember' => 'integer',
        ]);
        //逻辑
        $user = request(['email', 'password']);
        $is_remember = boolval(\request('is_remember'));
        //渲染
        if(\Auth::attempt($user, $is_remember)) return redirect('/posts');    //登录成功跳转列表页
        return \Redirect::back()->withErrors('邮箱密码不匹配！');   //登录失败，跳转回来
    }

    /**
     * 登出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return \redirect('/login');
    }
}
