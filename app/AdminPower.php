<?php

namespace App;

/**
 * 权限模型
 * Class AdminPower
 * @package App
 */
class AdminPower extends Model
{
    protected $table = 'admin_powers';

    /**
     * 一个权限拥有的角色
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_power_role', 'power_id', 'role_id')
            ->withPivot('power_id', 'role_id');
    }
}
