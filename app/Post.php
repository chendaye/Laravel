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
     * @return mixed
     */
    public function scopeAuthor(Builder $query , $user_id)
    {
        return $query->where('user_id', $user_id);
    }


    /**
     * 一个文章有多个主题
     */
    public function postTopics()
    {
        return $this->hasMany(PostTopic::class, 'post_id', 'id');
    }


    /**
     * 文章不属于某一个主题
     * @param Builder $query
     * @param $topic_id
     * @return mixed
     */
    public function scopeTopicNot(Builder $query, $topic_id)
    {
        //第一个参数是关系模型  第二个参数是  and or  描述关系的   第三个匿名函数是要and  和  or 的关系
        //通过第一个参数(模型关系)获取专题数  再and 一个条件
        return $query->doesntHave('postTopics', 'and', function($q) use ($topic_id) {
             $q->where("topic_id", $topic_id);
        });
    }

    /**
     * 定义全局范围
     * 此方法会自动执行
     */
    public static function boot()
    {
        //首先调用父类的boot方法
        parent::boot(); // TODO: Change the autogenerated stub
        //定义全局范围
        static::addGlobalScope('avaiable', function (Builder $builder){
            //只要未删除的文章
            $builder->whereIn('status', [0,1]);
        });
    }

    /**
     * 一对多
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        //第一个参数：子表 第二个参数：子表关联ID 第三个参数：附表ID
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
