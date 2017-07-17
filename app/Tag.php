<?php

namespace App;


class Tag extends Model
{
    /**
     * 多态多对多
     * tag 可以对多个 book 也可以对多个 video
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function books()
    {
        return $this->morphedByMany(Book::class, 'taggable','taggables','tag_id','taggable_id');
    }

    /**
     * 多态多对多
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function videos()
    {
        //return $this->morphedByMany(Video::class, 'taggable','taggables','tag_id','taggable_id');
        return $this->morphedByMany(Video::class, 'taggable');
    }
}
