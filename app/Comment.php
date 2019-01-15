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
     * 当一个模型 belongsTo 或 belongsToMany 另一个模型时，像是一个 Comment 属于一个 Post。
     * 这对于子级模型被更新时，要更新父级的时间戳相当有帮助。举例来说，当一个 Comment 模型被更新时，
     * 你可能想要「连动」更新 Post 所属的 updated_at 时间戳。
     * Eloquent 使得此事相当容易。只要在关联的下层模型中增加一个包含名称的 touches 属性即可
     *
     * 所有的关联将会被连动。
     *联动父级事件戳
     *
     * 现在，当你更新一个 Comment 时，
     * 它所属的 Post 拥有的 updated_at 字段也会被同时更新，使其更方便的得知何时让一个 Post 模型的缓存失
     * @var array
     */
    protected $touches = ['post'];

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
