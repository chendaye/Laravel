<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Zan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    /**
     * 列表获取是实际上是记录对象 对象又可以进行很多操作
     * 事实上 PDO 的结果集本身就是对象
     * 文章列表
     */
    public function index()
    {
        $user = \Auth::user();
        //从容器中获取实例
        $app = app();
        $log = $app->make('log');  //通过字符串 log 在容器中获取 log 类的实例
        $log->info('post_index', ['data' => 'post']);   //调用类方法
        //获取数据包括分页
        $post = Post::orderBy('created_at', 'desc')->withCount(['comment', 'allZan'])->paginate(10);
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
        //todo:预加载评论类
        $post->load('comment');
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

        //权限验证
        $user_id = Auth::id();
        $param = array_merge(\request(['title', 'content']), compact('user_id'));

        //逻辑
        //法一
//        $post = new Post();
//        $post->title = \request('title');
//        $post->content = \request('content');
//        $post->user_id = $user_id;
//        $post->save();
        //法二
        //Post::create($param);
        //法三
        $con=mysqli_connect("67.218.128.128","root","root","chendaye666");
// 检查连接
        if (!$con)
        {
            die("连接错误: " . mysqli_connect_error());
        }
        $title = \request('title');
        $content = \request('content');
        $ret = mysqli_query($con,"INSERT INTO posts (title, content, user_id,created_at, updated_at) VALUES 
('{$title}','{$content}',$user_id,'2018-02-24 16:43:21','2018-02-24 16:43:21')");


        //渲染
        return redirect('/posts');  //跳转
       /* dd(\Request::all());
        dd(request());*/
    }

    /**
     * 编辑文章
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Post $post)
    {
        //权限验证 给模型post定义了一个策略   此处用策略方法 进行权限判断
        $this->authorize('edit', $post);
        return view('post/edit',compact('post'));
    }

    /**
     * 更新文章
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Post $post)
    {
        //验证
        $this->validate(\request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ], [
            'title.min' => '文章标题过短！',   //自定义提示信息
        ]);

        //权限验证
        //  可传递一个类名给 authorize 方法。当授权动作时，这个类名将被用来判断使用哪个策略：
        $this->authorize('update', $post);
        //逻辑
        $post->title = \request('title');
        $post->content = \request('content');
        $post->save();
        //渲染
        return redirect("/posts/{$post->id}");
    }

    /**
     * 删除文章
     */
    public function delete(Post $post)
    {
        //权限判断
        //使用  Post模型的策略类 PostPolicy  里定义的delete 策略方法来判断
        //策略方法  需要传入当前用户  和策略拥有者的实例
        $this->authorize('delete', $post);
        //todo:用户权限验证
        $post->delete();
        return redirect("/posts");
    }

    /**
     * 上传图片
     * @param Request $request
     * @return string
     */
    public function imgUpload(Request $request)
    {
        //获取文件并保存
        $path = $request->file('wangEditorH5File')->storePublicly(md5(time()));
        //生成一个指向此文件的路径
//        return asset('storage/app/public/'.$path);  //使用原本路径
        return asset('/public/storage/'.$path);     //使用软连接  注意修改软连接 所属组 所有者
    }

    /**
     * 评论模块
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(Post $post)
    {
        //验证
        $this->validate(\request(), [
            'post_id' => 'integer',
            'content' => 'required|string|min:3',   //规则不要拼写错
        ]);
        //逻辑
        $comment = new Comment();   //Comment 模型
        $comment->user_id = Auth::id();
        $comment->content = \request('content');
        $post->comment()->save($comment);   //将comment 模型实例作为参数传入
        //渲染
        return back();  //直接回到详情页面
    }

    /**
     * 点赞
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function zan(Post $post)
    {
        $param = [
            'user_id' => Auth::id(),    //当前登录的用户
            'post_id' => $post->id,
        ];
        //在数据库中查找 有就取出来 没有就创建
        Zan::firstOrCreate($param);

        return back();
    }

    /**
     * Laravel模型中对记录的操作是一对象的形式来操作的
     * 一个记录一个对象  一个记录关联多条记录也就是多个对象
     * 都可以通过在模型找到其关联模型
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelZan(Post $post)
    {
        //取出当前用户的赞  实际上是 取出当前post关联的一个  zan模型（记录）实例 进行操作
        $post->zan(Auth::id())->delete();
        return back();
    }

    /**
     * 文章搜索
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        //验证
        $this->validate(\request(),[
            'query' => 'required',
        ]);
        //逻辑
        $search = \request('query');
        $content = Post::search($search)->paginate(10);
        //渲染
        return view('post.search', compact('content', 'search'));
    }

    public function test()
    {
        //只是一个静态页面表单
        return view('post/test');
    }
}
