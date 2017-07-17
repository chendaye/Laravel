<?php

namespace App;

use App\Model;

/**
 * 可定义方法描述当前模型与其他模型的关系
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    /**
     * 评论对文章是反向一对多
     * 一个评论属于一个文章
     */
    public function post()
    {
        return $this->belongsTo('App\Post', 'id', 'post_id');
    }

    /**
     * 一个评论属于一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }


    /**
     * 反向一对多
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
        //第一个参数：子表  第二个参数：子表关联ID 第三个参数：父表ID
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }


    /**
     * 多态关联
     * 方法名 commentable_id  commentable_type  前面部分
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
