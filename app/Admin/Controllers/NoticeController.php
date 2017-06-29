<?php
namespace App\Admin\Controllers;
use App\Jobs\SendMessage;
use App\Notice;

/**
 * 通知
 * Class NoticeController
 * @package App\Admin\Controllers
 */
class NoticeController extends Controller
{
    /**
     * 通知列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $notices = Notice::all();
        return view('admin.notice.index', compact('notices'));
    }

    /**
     * 创建通知页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * 保存通知
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        //验证
        $this->validate(request(), [
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
        //逻辑
     /*   $notice = new Notice();
        $notice->title = request('title');
        $notice->content = request('content');
        $notice->save();*/
        /*******/
        $notice = Notice::create(request(['title', 'content']));

        //todo:队列任务分发
        dispatch(new SendMessage($notice));
        //渲染
        return redirect('admin/notices');
    }
}
?>