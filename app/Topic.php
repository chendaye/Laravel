<?php

namespace App;

/**
 * 专题表模型
 * Class Topic
 * @package App
 */
class Topic extends Model
{
    /**
     * 一个专题有多少个文章
     */
    public function posts()
    {
        //第三个参数是你定义在关联中的模型外键名称，而第四个参数则是你要合并的模型外键名称
        //topic_id  post_id 都是中间表的   要找到某一个  topic  的多个  post_id
        return $this->belongsToMany(Post::class, 'post_topics', 'topic_id', 'post_id');
    }

    /**
     * 专题的文章数
     */
    public function postNum()
    {
        //第二个参数是  要关联表的字段  第三个是当前表的字段
        return $this->hasMany(PostTopic::class, 'topic_id', 'id');
    }

}
