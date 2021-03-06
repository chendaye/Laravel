<?php

namespace App;

use Illuminate\Foundation\Auth\User as AuthentiUsers;
use App\Phone;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * 继承自带的门脸类
 * Class User
 * @package App
 */
class User extends AuthentiUsers
{
    use LaratrustUserTrait;
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

    /**
     * 用户收到的通知
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function receivedNotices()
    {
        return $this->belongsToMany(Notice::class, 'user_notice', 'user_id', 'notice_id')->withPivot('user_id', 'notice_id');
    }

    /**
     * 用户是否收到某通知
     * Eloquent -> collect  集合和模型要分清楚
     * @param $notice
     * @return bool
     */
    public function hasReceived($notice)
    {
        return !!$this->belongsToMany(Notice::class, 'user_notice', 'user_id', 'notice_id')
            ->wherePivotIn('notice_id', [$notice->id])->get();
    }

    /**
     * 给用户增加通知
     * @param $notice
     */
    public function addNotice($notice)
    {
        //模型关联返回的还是模型   不是 集合   模型关联产生的动态属性才是集合
        $this->receivedNotices()->save($notice);
    }

    /**
     * 一对一关联
     * 获取用户电话
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phone()
    {
        //第一个参数：子表  第二个参数：子表关联ID  第三个参数：父表ID
        return $this->hasOne(Phone::class, 'user_id', 'id');
    }

    /**
     * 远程一对多
     * 一个用户多个文章，一个文章多个评论
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function away_many_to_many()
    {
        //hasManyThrough 方法的第一个参数为我们希望最终访问的模型名称，而第二个参数为中间模型的名称
        //第三个参数为中间模型的外键名称，而第四个参数为最终模型的外键名称，第五个参数则为本地键
        return $this->hasManyThrough('App\Comment', 'App\Post','user_id','post_id','id');
    }
}
