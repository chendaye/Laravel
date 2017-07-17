<?php

namespace App;


class Video extends Model
{
    /**
     * 多态关联
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        //第一个参数 所属类的类名 第二个参数 commentable_id  commentable_type  前面部分
        return $this->morphMany('App\Comment', 'commentable');
    }

    /**
     * 多态多对多
     * 首先是多对多
     * 其次是多态
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable','taggables','taggable_id','tag_id');
        //return $this->morphToMany('App\Tag', 'taggable');
    }
}
