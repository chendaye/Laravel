<?php
namespace App\Admin\Controllers;
use App\AdminPower;

/**
 * 管理员管理
 * Class RoleController
 * @package App\Admin\Controllers
 */
class PowerController extends Controller
{
    /**
     * 列表页逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $powers = AdminPower::orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.power.index', compact('powers'));
    }

    /**
     * 权限列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function create()
   {
        return view('admin.power.create');
   }

    /**
     * 创建权限
     */
   public function store()
   {
        //验证
       $this->validate(request(),[
           'name' => 'required|string',
           'description' => 'required|min:4|string'
       ]);
       //逻辑
       AdminPower::create(request(['name', 'description']));
       //渲染视图
       return redirect('admin/powers');
   }
}
?>