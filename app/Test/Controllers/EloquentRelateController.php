<?php
namespace App\Test\Controllers;
use App\AdminRole;
use App\AdminUser;
use App\Comment;
use App\Phone;
use App\Post;
use App\User;

/**
 * 模型关联
 * Class EloquentRelateController
 * @package App\Test\Controllers
 */
class EloquentRelateController extends Controller
{
    /**
     * 一对一
     */
    public function one_to_one()
    {
        $user = User::find(386);
        dump($user);
        $phone = $user->phone;
        dump($phone);
    }

    /**
     * 反向一对一
     */
    public function reverse_one_to_one()
    {
        $phone = Phone::find(806);
        dump($phone);
        $user = $phone->user->name;
        $user = $phone->user();
        dump($user);
    }

    /**
     * 一对多
     */
    public function one_to_many()
    {
        $post = Post::find(1);
        dump($post);
        $many = $post->comments;
        dump($many);
    }

    /**
     * 反向一对多
     */
    public function reverse_one_to_many()
    {
        $comment = Comment::find(1);
        dump($comment);
        $many = $comment->posts;
        dump($many);
    }

    /**
     * 多对多
     */
    public function many_to_many()
    {
        $admin_user = AdminUser::find(1);
        //dump($admin_user);
        $many = $admin_user->role;
        //dump($many);
        /*****************/
        $admin_role = AdminRole::find(4);
        //dump($admin_role);
        $many = $admin_role->user;
        dump($many);
        /***********/
        //取出的每个 Role 模型对象，都会被自动赋予 pivot 属性。
        //此属性代表中间表的模型，它可以像其它的 Eloquent 模型一样被
        foreach ($many as $value){
            dump($value->pivot->created_at);
        }
    }

    public function away_many_to_many()
    {
        $user = User::find(382);
        $away = $user->away_many_to_many;
        dump($away);
    }
}
?>