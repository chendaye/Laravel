<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //获取当前用户的通知
        $user = Auth::user();
        $notices = $user->receivedNotices;
        return view('notice.index', compact('notices'), compact('notices'));
    }
}
