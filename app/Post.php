<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Post extends Model
{
    //引入 Searchabel
    use Searchable;

    /**
     * 定义索引里面的类型
     * @return string
     */
    public function searchableAs()
    {
        return 'post';
    }

    /**
     * 定义要搜索的字段
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }

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
        return $this->hasMany(\App\Zan::class, 'post_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * 属于某个作者的文章
     * @param Builder $query
     * @param $user_id
     */
    public function scopeAuthor(Builder $query , $user_id)
    {
        $query->where('user_id', $user_id);
    }

    /**
     * 一个文章有多个主题
     */
    public function postTopic()
    {
        $this->hasMany(Topic::class, 'post_id', 'id');
    }

    /**
     * 文章不属于某一个主题
     * @param Builder $query
     * @param $topic_id
     */
    public function scopeTopicNot(Builder $query, $topic_id)
    {
        $query->doesntHave('postTopic', 'and', function ($que) use($topic_id){
            $que->where('topic_id', $topic_id);
        });
    }
}
