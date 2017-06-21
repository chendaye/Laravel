<?php
namespace App\Admin\Controllers;
use App\AdminPower;
use App\AdminRole;

/**
 * 角色管理
 * Class RoleController
 * @package App\Admin\Controllers
 */
class RoleController extends Controller
{
    /**
     * 列表页逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //获取所有角色
        $roles = AdminRole::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.role.index', compact('roles'));
    }

    /**
     * 创建角色页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * 保存角色
     */
    public function store()
    {
        //验证
        $this->validate(request(), [
            'name' => 'required|min:4',
            'description' => 'required'
        ]);
        //逻辑
        $name = request('name');
        $description = request('description');
        AdminRole::firstOrCreate(compact('description', 'name'));
        //渲染
        return redirect('admin/roles');
    }

    /**
     * 权限列表
     * @param AdminRole $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function power(AdminRole $role)
    {
        //所有权限
        $powers = AdminPower::all();
        //当前角色权限
        $myPower = $role->powers;
        return view('admin.role.power', compact('powers', 'myPower', 'role'));
    }

    /**
     * 增加删除角色权限
     * @param AdminRole $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function powerStore(AdminRole $role)
    {
        //验证
        $this->validate(request(), [
            'powers' => 'required|array'
        ]);

        //逻辑
        //找出表单提交过来的所有权限
        $postPower = AdminPower::findMany(request('powers'));
        $myPower = $role->powers;
        //要增加的角色  提交过来的角色中  不属于我的角色  提交过来的都是要增加的（除去已有的）
        $addPower = $postPower->diff($myPower);
        foreach ($addPower as $power){
            //分配角色
            $role->assignPower($power);
        }
        //要删除的角色  将集合与其它集合或纯 PHP 数组 进行值的比较，返回第一个集合中存在而第二个集合中不存在
        //不在提交中的都要删除
        $delPower = $myPower->diff($postPower);
        foreach ($delPower as $val){
            //移除角色
            $role->removePower($val);
        }
        //渲染
        return back();

    }

}
?>