<?php

namespace App;


class Phone extends Model
{
    /**
     * 一对一反向关联
     * 获取电话的主人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //第一个参数：子表  第二个参数：子表关联ID  第三个参数：父表ID
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
