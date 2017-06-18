<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function set()
    {
        return view('user.set');
    }

    public function store()
    {

    }

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
}
