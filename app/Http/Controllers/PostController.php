<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * 文章列表
     */
    public function index()
    {
        //加载模板
//        return view('post/index', ['test' => 'chendaye6666', 'is_true' => true]);
        $test = '66666';
        $is_true = true;
        return view('post/index', compact('test', 'is_true'));
    }

    /**
     * 文章详情
     */
    public function show()
    {
        return view('post/show');
    }

    /**
     * 创建文章
     */
    public function create()
    {
        return view('post/create');
    }

    /**
     * 具体创建
     */
    public function store()
    {

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
}
