<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostTopic;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * 专题页要展示的数据
     * 模型里面是针对所有数据记录队形的关系   控制器里由具体的对象调用这些关系
     * 获取数据之后检查很必要
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Topic $topic)
    {
        //带文章的专题  withCount 只能在query  Builder 里用  找到这个专题的文章数量
        $topic = Topic::withCount('postNum')->find($topic->id); //给 $topic 带上一个数量
        //专题的文章列表 取前10个
        $posts = $topic->posts()->orderBy('created_at', 'desc')->take(10)->get();
        //属于当前用户的文章  但是不属于当前专题的文章 scope 方法
        $myPosts = Post::author(Auth::id())->topicNot($topic->id)->get();
        //渲染视图
        return view('topic.show', compact('topic', 'posts', 'myPosts'));
    }

    /**
     * 文章绑定专题
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Topic $topic)
    {
        //验证
        $this->validate(\request(), [
            'post_ids' => 'required|array'  //必须是数据
        ]);
        //逻辑
        $post_ids = \request('post_ids');
        $topic_id = $topic->id; //专题ID
        //没有就创建
        foreach ($post_ids as $post_id){
            PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
        }
        //渲染
        return back();
    }
}
