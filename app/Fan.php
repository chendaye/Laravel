<?php

namespace App;

use App\Model;

class Fan extends Model
{
    protected $table = 'fans';  //模型的表名

    /**
     * 通过star_id 获取 其对应的 user 用户信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function faner()
    {
        return $this->hasOne(User::class, 'id', 'star_id');
    }

    /**
     * fan_id 获取其对应的 user 用户信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function starer()
    {
        return $this->hasOne(User::class, 'id', 'fan_id');
    }
}
