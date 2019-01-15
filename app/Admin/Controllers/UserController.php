<?php
namespace App\Admin\Controllers;
use App\AdminRole;
use App\AdminUser;
use GuzzleHttp\Psr7\Request;

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

    /**
     * 以后角色列表
     * @param AdminUser $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function role(AdminUser $user)
    {
        //获取所有的角色
        $roles = AdminRole::all();
        //当前用户拥有的角色  $user->roles() 返回一个多对多对象
        $myRole = $user->roles;
        //渲染
        return view('admin.user.role', compact('roles', 'myRole', 'user'));
    }

    /**
     * 管理角色
     * @param AdminUser $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function roleStore(AdminUser $user)
    {
        //验证
        $this->validate(request(), [
            'roles' => 'required|array'
        ]);

        //逻辑
        //找出表单提交过来的所有角色
        $postRole = AdminRole::findMany(request('roles'));
        $myRole = $user->roles;
        //要增加的角色  提交过来的角色中  不属于我的角色  提交过来的都是要增加的（除去已有的）
        $addRole = $postRole->diff($myRole);
        foreach ($addRole as $role){
            //分配角色
            $user->assignRole($role);
        }
        //要删除的角色  将集合与其它集合或纯 PHP 数组 进行值的比较，返回第一个集合中存在而第二个集合中不存在
        //不在提交中的都要删除
        $delRole = $myRole->diff($postRole);
        foreach ($delRole as $val){
            //移除角色
            $user->removeRole($val);
        }
        //渲染
        return back();
    }

    /**
     * 后台用户设置中心
     * @param AdminUser $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function set(AdminUser $user)
    {
       // $user = AdminUser::find($user->id);
        return view('admin.user.set', compact('user'));
    }

    public function setStore(AdminUser $user, \Illuminate\Http\Request $request)
    {
        //验证
        $this->validate(request(), [
            'name' => 'required|string',
        ]);
        //逻辑
        $name = request('name');
        //修改的名称不能相同
        if($name != $user->name){
            $exist = AdminUser::where('name', $name)->count();
            if(!$exist){
                $user->name = request('name');
            }else{
                return back()->withErrors([
                    'message' => '用户名已经被注册！',
                ]);
            }
            if($request->file('avatar')){
                $path = $request->file('avatar')->storePublicly(md5($user->id).time());
                //http://www.dragon-god.com/Laravel/public/storage/c4ca4238a0b923820dcc509a6f75849b1498907864/Lq2w1lrTDdKhFiLpclLjzLfb5IZX9dRpGfvl74bu.jpeg
                $path = asset('/public/storage/'.$path);
                $user->avatar = $path;
            }
            $user->save();
        }
        //渲染
        return redirect('admin/home');
    }

}
?>