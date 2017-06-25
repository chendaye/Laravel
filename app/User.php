<?php

namespace App;

use Illuminate\Foundation\Auth\User as AuthentiUsers;

/**
 * 继承自带的门脸类
 * Class User
 * @package App
 */
class User extends AuthentiUsers
{
    //可注入的字段
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    //    protected $fillable = [];

    /**
     * 获取用户的所有粉丝
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fans()
    {
        return $this->hasMany(Fan::class, 'star_id', 'id');
    }

    /**
     * 获取用户所有关注的人
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stars()
    {
        return $this->hasMany(Fan::class, 'fan_id', 'id');
    }

    /**
     * 获取用户所有的文章  一个  user  多个 post
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    /**
     * 当前用户 是否 被 某一个  $uid 关注了
     * @param $uid
     * @return mixed
     */
    public function hasFan($uid)
    {
        return $this->fans()->where('fan_id', $uid)->count();
    }

    /**
     * 当前用户是否关注了某个 $uid
     * @param $uid
     * @return mixed
     */
    public function hasStar($uid)
    {
        return $this->fans()->where('star_id', $uid)->count();
    }

    /**
     * 关注某人
     * @param $uid
     * @return mixed
     */
    public function doFan($uid)
    {
        //实例一个Fun对象
        $fan = new Fan();
        $fan->star_id = $uid;
        //先获取所有关注的人
        return $this->stars()->save($fan);
    }

    /**
     * 取消关注某人
     * @param $uid
     * @return mixed
     */
    public function unFan($uid)
    {
        //实例一个Fun对象
        $fan = new Fan();
        $fan->star_id = $uid;
        //先获取所有关注的人
        return $this->stars()->delete($fan);
    }
}
