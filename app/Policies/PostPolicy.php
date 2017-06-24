<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 更新权限
     * 把权限抽象为一个类
     * @param User $user
     * @param Post $post
     * @return  bool
     */
    public function update(User $user, Post $post)
    {
        //返回true 有权限
        return $user->id == $post->user_id;
    }

    /**
     * 编辑权限
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function edit(User $user, Post $post)
    {
        //返回true 有权限
        return $user->id == $post->user_id;
    }

    /**
     * 删除权限
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function delete(User $user, Post $post)
    {
        //删除权限
        return $user->id == $post->user_id;
    }
}
