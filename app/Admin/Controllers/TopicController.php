<?php
namespace App\Admin\Controllers;
use App\Topic;


/**
 * 管理员管理
 * Class TopicController
 * @package App\Admin\Controllers
 */
class TopicController extends Controller
{
    /**
     * 专题列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //所有专题
        $topics = Topic::all();
        return view('admin.topic.index', compact('topics'));
    }

    /**
     * 专题创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.topic.create');
    }


    /**
     * 常见专题
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        //验证
        $this->validate(request(),[
            'name' => 'required|min:3'
        ]);
        //逻辑
        $name = request('name');
        Topic::create(compact('name')); //创建专题
        //渲染
        return redirect('admin/topics');
    }

    public function destroy(Topic $topic)
    {
        //删除
        $topic->delete();
        //返回
        return [
            'error' => 0,
            'msg' => '',
        ];

    }
}
?>