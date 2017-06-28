<?php

namespace App;

use Illuminate\Foundation\Auth\User as AuthentiUsers;

class AdminUser extends AuthentiUsers
{
    //把这个字段设置空  不进行TOKEN验证
    protected $rememberTokenName = '';

    //模型不可以注入的字段 空代表所有字段都可以注入
    protected $guarded = [];

    /**
     * 用户所有角色
     * 一个用户对应多个角色
     * 一个角色对应多个用户
     * @return mixed
     */
    public function roles()
    {
        //当前表 多对多  AdminRole::class 表  ； 中间表是 admin_role_user ；  当前表 要关联的中间字段是 role_id  多对多表要关联的中间字段是 user_id
        return $this->belongsToMany(AdminRole::class, 'admin_role_user', 'user_id', 'role_id')
            ->withPivot(['user_id', 'role_id']);
    }

    /**
     * 用户是否有某一个角色
     * @param AdminRole $role
     * @return bool
     */
    public function hasRole(AdminRole $role)
    {
        //intersect() 查找collect 交集  “！！” 把结果转换未bool  移除任何指定 数组 或集合内所没有的数值。最终集合保存着原集合的键：
        return !!$role->intersect($this->roles)->count();
    }

    /**
     * 给用户分配角色
     * @param AdminRole $role
     * @return mixed
     */
    public function assignRole(AdminRole $role)
    {
        //自动根据多对多的关系创建记录
        return $this->roles()->save($role);
    }

    /**
     * 取消用户角色
     * @param AdminRole $role
     * @return mixed
     */
    public function removeRole(AdminRole $role)
    {
        //detach  只会解除关系不会删除关联记录
        return $this->roles()->detach($role);
    }

    /**
     * 用户是否有某权限
     * @param AdminPower $power
     * @return bool
     */
    public function hasPower(AdminPower $power)
    {
        //权限拥有的角色
        $power_roles = $power->roles;
        //用户拥有的角色
        $user_roles = $this->roles;
        //求交集 如果  交集非空  则有权限
        return !!$user_roles->intersect($power_roles)->count();
        //return $this->hasRole($power->roles);
    }

}
