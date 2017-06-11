<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * 文章列表
     */
    public function index()
    {
        //获取数据包括分页
        $post = Post::orderBy('created_at', 'desc')->paginate(10);;
        //加载模板
        return view('post/index', compact('post'));
    }

    /**
     * 文章详情
     * @param Post $post  定义路由的时候使用了模型绑定  所有方法 要传入一个模型
     * Route::get('posts/{post}', '\App\Http\Controllers\PostController@show');
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Post $post)
    {
        //直接把对象传递给页面
        return view('post/show',compact('post'));
    }

    /**
     * 创建文章
     */
    public function create()
    {
        //只是一个静态页面表单
        return view('post/create');
    }

    /**
     * 所有的表单提交都要经过三步
     * 验证  逻辑  渲染
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        //验证   表单提交的数据
        $this->validate(\request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ], [
            'title.min' => '文章标题过短！',   //自定义提示信息
        ]);
        //逻辑
        //法一
      /*  $post = new Post();
        $post->title = \request('title');
        $post->content = \request('content');
        $post->save();*/
        //法二
        $post = Post::create(\request(['title', 'content']));

        //渲染
        return redirect('/posts');  //跳转
       /* dd(\Request::all());
        dd(request());*/
    }

    /**
     * 编辑文章
     */
    public function edit()
    {
        return view('post/edit');
    }

    /**
     * 更新文章
     */
    public function update()
    {

    }

    /**
     * 删除文章
     */
    public function delete()
    {

    }

    public function imgUpload(Request $request)
    {
        //获取文件并保存
        $path = $request->file('wangEditorH5File')->storePublicly(md5(time()));
        //生成一个指向此文件的路径
        return asset('storage/app/public/'.$path);
    }
}
