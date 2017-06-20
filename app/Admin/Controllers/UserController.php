<?php
namespace App\Admin\Controllers;
use App\AdminUser;

/**
 * 管理员管理
 * Class UserController
 * @package App\Admin\Controllers
 */
class UserController extends Controller
{
    /**
     * 列表页逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //取出所有的用户
        $users = AdminUser::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    /**
     * 创建管理员
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * 保存管理员
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        //验证
        $this->validate(request(), [
            'name' => 'required|string|min:3|max:10',
            'password' => 'required|string|min:3'
        ]);
        //逻辑
        $name = request('name');
        $password = bcrypt(request('password'));    //密码要加密
        AdminUser::create(compact('name', 'password'));
        //旧方法
       /* $user = new AdminUser();
        $user->name = $name;
        $user->password = $password;
        $user->save();*/
        //渲染
        return redirect("/admin/users");
    }
}
?>