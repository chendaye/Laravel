<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 用户设置页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function set()
    {
        //当前登录用户
        $user = Auth::user();
        return view('user.set', compact('user'));
    }

    public function settingStore(Request $request, User $user)
    {
        $this->validate(request(),[
            'name' => 'min:3',
        ]);

        $name = request('name');
        if ($name != $user->name) {
            if(User::where('name', $name)->count() > 0) {
                return back()->withErrors(array('message' => '用户名称已经被注册'));
            }
            $user->name = request('name');
        }
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->storePublicly(md5(\Auth::id() . time()));
            $user->avatar = "storage/app/public/". $path;
        }
        $user->save();
        return back();
    }


    /**
     * 用户个人中心
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        //当前用户的信息 包含关注、粉丝、文章数
        //find 找出来的user 和传进来的是同一个 对象  通过find这种方式开始使用 withCount
        $user = User::withCount(['stars', 'fans', 'posts'])->find($user->id);
        //当前用户的文章列表 最新10条  要链式调用就要加括号
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();
        //当前用户关注的用户  包含关注用户的  关注、粉丝、文章数
        $stars = $user->stars;  //当前用户关注的人
        $starers = User::whereIn('id', $stars->pluck('star_id'))->withCount(['stars', 'fans', 'posts'])->get();
        //当前用户的粉丝   包含粉丝用户的  关注、粉丝、文章数
        $fans = $user->fans;  //当前用户的粉丝
        $faners = User::whereIn('id', $fans->pluck('fun_id'))->withCount(['stars', 'fans', 'posts'])->get();
        return view('user.show', compact('user', 'posts', 'stars', 'starers', 'fans', 'faners'));
    }

    /**
     * 关注用户
     * @param User $user
     * @return array
     */
    public function fan(User $user)
    {
        //当前登录用户
        $me = Auth::user();
        $me->doFan($user->id);
        return [
            'error' => 0,
            'msg'   => '关注成功',
        ];
    }

    /**
     * 取消关注
     * @param User $user
     * @return array
     */
    public function unFan(User $user)
    {
        //当前登录用户
        $me = Auth::user();
        $me->unFan($user->id);
        return [
            'error' => 0,
            'msg'   => '取消关注成功',
        ];
    }
}
