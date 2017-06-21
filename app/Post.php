<?php

namespace App;

class Post extends Model
{
    /**
     * 文章关联创作者  一对一
     * 文章从属于创作者  所以 用 belongsTo()
     * Post关联User后  类似于Post拥有User的全部属性（字段）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //一对一 关联 $this->belongsTo('App\User', 'foreign_key', 'other_key');
        return $this->belongsTo('App\User', 'user_id', 'id');  // post 模型 是 user 的从属关系 对应的 hasOne()
    }

    /**
     * 文章对评论是一对多关系
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany('App\Comment', 'post_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * 一个人对一个文章只能点赞一次
     * @param $user_id
     * @return mixed
     */
    public function zan($user_id)
    {
        return $this->hasOne(\App\Zan::class)->where('user_id', $user_id);
    }

    /**
     * 获取一篇文章所有的赞
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allZan()
    {
        return $this->hasMany(\App\Zan::class, 'post_id', 'id');
    }
}
