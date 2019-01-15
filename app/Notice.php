<?php

namespace App;

/**
 * 通知模型
 * 通知与用户是对对多的关系
 * Class AdminNotice
 * @package App
 */
class Notice extends Model
{
    /**
     * 通知已经发送给哪些用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sendToUsers()
    {
        return $this->belongsToMany(Notice::class, 'user_notice', 'notice_id', 'user_id');
    }

    /**
     * 消息是否已经发送给某个用户
     * @param $user
     * @return mixed
     */
    public function hasSend($user)
    {
        return !!$this->belongsToMany(Notice::class, 'user_notice', 'notice_id', 'user_id')
            ->wherePivot('user_id', $user->id)->get();
    }
}
