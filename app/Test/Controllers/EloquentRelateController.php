<?php
namespace App\Test\Controllers;
use App\AdminRole;
use App\AdminUser;
use App\Book;
use App\Comment;
use App\Phone;
use App\Post;
use App\Providers\ComposerServiceProvider;
use App\Tag;
use App\User;
use App\Video;

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

    /**
     * 远程多对多
     */
    public function away_many_to_many()
    {
        $user = User::find(382);
        $away = $user->away_many_to_many;
        dump($away);
    }

    /**
     * 多态关联
     */
    public function status_many()
    {
        //获取拥有的
        $book = Book::find(1);
        $book->comments;
        //dump($book);
        $video = Video::find(1);
        $video = $video->comments;
        //dump($video);

        //获取所有者
        $comment = Comment::find(6);
        $comment = $comment->commentable;
        dump($comment);
    }

    /**
     * 多态多对多
     * 有问题
     */
    public function status_many_to_many()
    {
        $video = Video::find(2);
       // dump($video);
        $video = $video->tags();
        dump($video);

        $book = Book::find(1);
        $book = $book->tags;
        //dump($book);

        $tag = Tag::find(1);
        $tag = $tag->books;
        //dump($tag);
    }

    /**
     * 查找关联
     * 以在关联中使用 查询语句构造器 中的任意方法
     */
    public function eloquent_where()
    {
        $user = User::find(382);
        $u = $user->posts()->where('id', 1)->get();
        dump($u);
    }

    /**
     * 关联方法
     * 动态属性
     */
    public function auto_attribute()
    {
        //如果你不需要增加额外的条件至 Eloquent 的关联查找，则可以简单的像访问属性一样来访问关联
        /*动态属性是「延迟加载」的，意味着它们只会在被访问的时候才加载关联数据。
        正因为如此，开发者通常需要使用 预加载 来预先加载关联数据，
        关联数据将会在模型加载后被访问。预加载能有效减少你的 SQL 查询语句*/
        $user = User::find(382);
        $post = $user->posts;
        dump($post);
    }

    /**
     * 查找关联是否存在
     */
    public function eloquent_exist()
    {
        /*当访问模型的纪录时，你可能希望根据关联的存在来对结果进行限制。
        比方说你想获取博客中那些至少拥有一条评论的文章。则可以通过传递名称至关联的 has 方法来实现：*/
        //// 获取那些至少拥有一条评论的文章...
        $posts = Post::has('comments')->get();


        // 获取所有至少有三条评论的文章...
        $posts = Post::has('comments', '>=', 3)->get();


        //也可以使用「点」符号来构造嵌套的 has 语句。例如，你可能想获取那些至少有一条评论被投票的文章
        // 获取所有至少有一条评论被评分的文章...
        //点号后面的是 comments 模型的方法
        $posts = Post::has('comments.commentable')->get();


        /*
         * 如果你想要更高级的用法，则可以使用 whereHas 和 orWhereHas 方法，在 has 查找里设置「where」条件。
         * \此方法可以让你增加自定义条件至关联条件中，例如对评论内容进行检查*/

        //  获取那些至少有一条评论包含 foo 的文章
        $posts = Post::whereHas('comments', function ($query) {
            $query->where('content', 'like', '水电费水电费%');
        })->get();

        dump($posts);

    }

    /**
     * 关联数据计数
     * 想对关联数据进行计数但又不想再发起单独的 SQL 请求，你可以使用 withCount 方法，
     * 此方法会在你的结果集中增加一个 {relation}_count 字段
     */
    public function relate_count()
    {
        $posts = Post::withCount('comments')->get();

        foreach ($posts as $post) {
            //dump($post->comments_count) ;
        }

        //还可以像在查询语句中添加约束一样，获取多重关联的「计数」
        $posts = Post::withCount(['postTopics', 'comments' => function ($query) {
            $query->where('content', 'like', 'foo%');
        }])->get();

        echo $posts[0]->post_topics_count;
        echo $posts[0]->comments_count;
    }

    /**
     * 预加载
     * 当通过属性访问 Eloquent 关联时，该关联数据会被「延迟加载」。
     * 意味着该关联数据只有在你使用属性访问它时才会被加载。
     * 不过，Eloquent 可以在你查找上层模型时「预加载」关联数据。预加载避免了 N + 1 查找的问题
     */
    public function preload()
    {
        $commemt = Comment::limit(100)->get();
        /*
         * 这个循环会取出所有的100条评论
         * 每一条评论又会执行一次查找所属文章的标题的操作
         * 会执行101此查找
         * 第一次是找出100个文章
         * 其他的是找出每一个文章的标题*/
        foreach ($commemt as $val){
            //dump($val->posts->title);
        }

        //可以使用预加载来将查找的操作减少至 2 次。可在查找时使用 with 方法来指定想要预加载的关联数据
        $commemt = Comment::limit(100)->with('posts')->get();

        /*
         * 对于该操作则只会执行两条 SQL 语句：
         * select * from posts
         * select * from posts where id in (1, 2, 3, 4, 5, ...*/
        foreach ($commemt as $val){
            //dump($val->posts->title);
        }


        //预加载多种关联
        //有时你可能想要在单次操作中预加载多种不同的关联。要这么做，只需传递额外的参数至 with 方法即可
        $commemt = Comment::limit(100)->with('posts','user')->get();
       // dump($commemt);

        //嵌套预加载
        //若要预加载嵌套关联，则可以使用「点」语法。
        //例如，让我们在一个 Eloquent 语法中，预加载所有评论的文章，及所有文章的主题
        $commemt = Comment::limit(100)->with('posts.postTopics')->get();
        //dump($commemt);

        //预加载条件限制
        //有时你可能想要预加载关联，并且指定预加载查询的额外条件
        $users = User::with(['posts' => function ($query) {
            $query->where('title', 'like', '%长河落日圆%');
        }])->get();

        //也可以调用其它的 查询语句构造器 来进一步自定义预加载的操作
        $users = User::with(['posts' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();


        //延迟预加载
        //有时你可能需要在上层模型被获取后才预加载关联。当你需要来动态决定是否加载关联模型时会很有帮助
        //动态预加载
        $users = User::all();

        if (1 == 2) {
            //不会预加载
            $users->load('posts', 'stars');
        }

        //如果你想设置预加载查询的额外条件，
        //则可以传递一个键值为你想要的关联的数组至 load 方法。这个数组的值应是用于接收查询实例的 闭包 实例
        $users->load(['posts' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);
        dump($users);
    }


    public function write()
    {
        //save saveMany 方法
        /*
         * Eloquent 提供了便捷的方法来将新的模型增加至关联中。
         * 例如，将新的 Comment 写入至 Post 模型。除了手动设置 Comment 的 post_id 属性外，
         * 你也可以直接使用关联的 save 方法来写入 Comment*/
        $comment = new Comment(['content' => 'A new comment.']);
        $post = Post::find(1);
        $post->comments()->save($comment);
        $post = Post::find(1);
        $com = $post->comments;
       // dump($com);

        //如果你需要保存多个关联模型，则可以使用 saveMany 方法
        $post = Post::find(2);

        $post->comments()->saveMany([
            new Comment(['content' => 'A new comment.']),
            new Comment(['content' => 'Another comment.']),
        ]);
        $post = Post::find(2);
        $com = $post->comments;
        //dump($com);

        //Create 方法
        /*
         * 除了 save 与 saveMany 方法外，你也可以使用 create 方法，该方法允许传入属性的数组来建立模型并写入数据库。
         * save 与 create 的不同之处在于，save 允许传入一个完整的 Eloquent 模型实例，但 create 只允许传入原始的 PHP 数组*/
        $post = Post::find(2);

        $comment = $post->comments()->create([
            'content' => 'qqqqqqqqqqqqqqqqqqqqq',
        ]);
        $post = Post::find(2);
        $com = $post->comments;


        //更新「从属」关联
        /*当更新一个 belongsTo 关联时，可以使用 associate 方法。此方法会将外键设置到下层模型*/

        $comment = Comment::find(30);

        $post = Post::find(2);

       // $post->comments()->associate($comment);
        $post->save();
       // dd($post);

        //当删除一个 belongsTo 关联时，你可以使用 dissociate 方法。此方法会置该关联的外键为空
       // $post->account()->dissociate();

        $post->save();


        //多对多关联
        /*
         * 附加与卸除
         * 当使用多对多关联时，Eloquent 提供了一些额外的辅助函数让操作关联模型更加方便。
         * 例如，让我们假设一个用户可以拥有多个身份，且每个身份都可以被多个用户拥有。
         * 要附加一个规则至一个用户，并连接模型以及将记录写入至中间表，则可以使用 attach 方法*/
        $user = AdminUser::find(1);
        $roleId = 55;
        $user->roles()->attach($roleId);
        //当附加一个关联至模型时，你也可以传递一个需被写入至中间表的额外数据数组
        $user->roles()->attach(77, ['created_at' => '2017-07-19']);

        //可使用 detach 方法。detach 方法会从中间表中移除正确的纪录；当然，这两个模型依然会存在于数据库中
        // 移除用户身上某一身份...
        $roleId = 77;
        $user->roles()->detach($roleId);

        // 移除用户身上所有身份...
        //$user->roles()->detach();

        //attach 与 detach 都允许传入 ID 数组：
        $user = AdminUser::find(1);



        $user->roles()->attach([88 => ['created_at' => '2017-07-19'], 99, 100]);
        $user->roles()->detach([88, 99, 100]);
    }

    /**
     * 连动父级时间戳
     * 当一个模型 belongsTo 或 belongsToMany 另一个模型时，像是一个 Comment 属于一个 Post。
     * 这对于子级模型被更新时，要更新父级的时间戳相当有帮助。
     * 举例来说，当一个 Comment 模型被更新时，你可能想要「连动」更新 Post 所属的 updated_at 时间戳。
     * Eloquent 使得此事相当容易。只要在关联的下层模型中增加一个包含名称的 touches 属性即可
     */



}
?>