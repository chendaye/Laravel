<?php
namespace App\Admin\Controllers;
use App\AdminUser;
use App\Post;

/**
 * 文章管理
 * Class PostController
 * @package App\Admin\Controllers
 */
class PostController extends Controller
{
    /**
     * 列表页逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //取出所有的用户  不使用全局的 Scope -> avaiable  ;  Post后面调用一个静态方法返回一个  模型对象之后再对这个对象进行链式操作

        $posts = Post::withoutGlobalScope('avaiable')->where('status', 0)
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.post.index', compact('posts'));
    }

    /**
     * 文章审核
     * @param Post $post
     * @return array
     */
    public function status(Post $post)
    {
        //验证
        $this->validate(request(), [
            'status' => 'required|in:1,-1'
        ]);
        //逻辑
        $post->status = request('status');
        $post->content = 123;
        $post->save();
        //渲染
        return [
            'error' => 0,
            'msg'   => '成功！',
        ];
    }


}
?>