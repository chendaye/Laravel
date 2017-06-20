<?php

namespace App;

/**
 * 角色模型
 * Class AdminRole
 * @package App
 */
class AdminRole extends Model
{
    protected $table = 'admin_roles';

    /**
     * 一个角色拥有的权限
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function powers()
    {
        //role表用ID在  中间表中找 power_id     AdminPower::class 表在中间表中 用ID 找 role_id
        return $this->belongsToMany(AdminPower::class, 'admin_power_role', 'role_id', 'power_id')
            ->withPivot('role_id', 'power_id');
    }

    /**
     * 给角色分配权限
     * @param AdminPower $power
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function assignPower(AdminPower $power)
    {
        return $this->powers()->save($power);
    }

    /**
     * 取消角色的权限
     * @param AdminPower $power
     * @return int
     */
    public function removePower(AdminPower $power)
    {
        return $this->powers()->detach($power);
    }

    /**
     * 角色是否拥有某权限
     * @param AdminPower $power
     * @return mixed
     */
    public function hasPower(AdminPower $power)
    {
        return $this->powers()->contains($power);
    }
}
