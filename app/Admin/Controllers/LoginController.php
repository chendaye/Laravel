<?php
namespace App\Admin\Controllers;

/**
 * 登录
 * Class LoginController
 * @package App\Admin\Controllers
 */
class LoginController extends Controller
{
    /**
     * 登录页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.login.index');
    }

    /**
     * 登录逻辑
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        //表单验证
        $this->validate(\request(), [
            'name' => 'required|min:3', //email代表验证邮箱
            'password' => 'required|min:6|max:10',  //confirmed 代表验证密码是否一致
        ]);
        //逻辑
        $user = request(['name', 'password']);
        //渲染  指定guard
        if(\Auth::guard('admin')->attempt($user)) return redirect('admin/home');    //登录成功跳转列表页
        return \Redirect::back()->withErrors('用户名密码不匹配！');   //登录失败，跳转回来
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        \Auth::guard('admin')->logout();
        return \redirect('/admin/login');
    }
}
?>