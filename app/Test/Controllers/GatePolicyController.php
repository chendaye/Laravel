<?php
namespace App\Test\Controllers;
use App\AdminUser;
use App\Post;
use App\ProductsInstockShipping;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * 用户授权系统
 * Class GatePolicyController
 * @package App\Test\Controllers
 */
class GatePolicyController extends Controller
{

    /*
     * 除了内置提供的 用户认证 服务外，
     * Laravel 还提供一种更简单的方式来处理用户授权动作。类似用户认证，
     * Laravel 有 2 种主要方式来实现用户授权：gates 和策略。*/

    /*
     * 可以把 gates 和策略类比于路由和控制器。
     * Gates 提供了一个简单、基于闭包的方式来授权认证。
     * 策略则和控制器类似，在特定的模型或者资源中通过分组来实现授权认证的逻辑。
     *
     * Gate控制一个页面（路由）
     * 策略 控制一个动作（控制器）
     *
     * Gates 大部分应用在模型和资源无关的地方，比如查看管理员的面板。
     * 策略应该用在特定的模型或者资源中*/

    /**
     * 编写 Gates
     */
    public function Gate()
    {
        /*
         * Gates 是用来决定用户是否授权访问给定的动作的闭包函数，
         * 并且典型的做法是在 App\Providers\AuthServiceProvider 类中使用 Gate facade 定义。
         * Gates 接受一个用户实例作为第一个参数，接受可选参数，比如相关的 Eloquent 模型*/
        if(1){
            /*public function boot()
            {
                $this->registerPolicies();

                Gate::define('update-post', function ($user, $post) {
                    return $user->id == $post->user_id;
                });
            }*/
        }

        /*
         * 使用 gates 授权动作
        使用 gates 来授权动作时，应当使用 allows 方法。
        注意你并不需要传递当前认证通过的用户给 allows 方法。
        Laravel 会自动处理好传入的用户，然后传递给 gate 闭包函数*/
        $post = Post::find(1);
        if (Gate::allows('update-post', $post)) {
            // 当前用户可以更新 post...
        }

        if (Gate::denies('update-post', $post)) {
            // 当前用户不能更新 post...
        }

        /*
         * 如果需要指定一个特定的用户可以访问某个动作，可以使用 Gate facade 中的 forUser 方法：*/
        $user = User::find(1);
        if (Gate::forUser($user)->allows('update-post', $post)) {
            // 指定用户可以更新 post...
        }

        if (Gate::forUser($user)->denies('update-post', $post)) {
            // 指定用户不能更新 post...
        }

    }

    /**
     * 创建策略
     */
    public function Policy()
    {
        /*
         * 策略是在特定模型或者资源中组织授权逻辑的类。
         * 例如，如果应用是一个博客，会有一个 Post 模型和一个相应的 PostPolicy 来授权用户动作，比如创建或者更新博客
         * 一个资源模型 定义一个对应得策略类 定义了操作模型的权限*/

        /*
         * 可以使用 make:policy artisan 命令 来生成策略。生成的策略将放置在 app/Policies 目录。
         * 如果在你的应用中不存在这个目录，那么 Laravel 会自动创建：
        php artisan make:policy PostPolicy*/

        /*
         * make:policy 会生成空的策略类。如果希望生成的类包含基本的「CRUD」策略方法， 可以在使用命令时指定 --model 选项：
        php artisan make:policy PostPolicy --model=Post*/

        /*
         * 注册策略
        一旦该授权策略存在，需要将它进行注册。AuthServiceProvider 包含了一个 policies 属性，
        可将各种模型对应至管理它们的授权策略。注册一个策略将引导 Laravel 在授权动作访问给定模型时使用何种策略：*/

        if(0){
            /**
             * 应用的策略映射。
             *
             * @var array
             */
//            protected $policies = [
//                Post::class => PostPolicy::class,
//            ];
        }

        /*
         * 策略方法
        一旦授权策略被生成且注册，我们就可以为每个权限的授权增加方法。
        例如，让我们在 PostPolicy 中定义一个 update 方法，
        它会判断指定的 User 是否可以「更新」一条 Post。
        update 方法接受 User 和 Post 实例作为参数，并且应当返回 true 或 false 来指明用户是否授权更新给定的 Post。
        因此，这个例子中，我们判断用户的 id 是否和 post 中的 user_id 匹配：*/

       /* public function update(User $user, Post $post)
        {
            return $user->id === $post->user_id;
        }*/


       /*
        * 不包含模型方法
       一些策略方法只接受当前认证通过的用户作为参数，而不用传入授权相关的模型实例。最普遍的应用场景就是授权 create 动作。
       例如，如果正在创建一篇博客，你可能希望检查一下当前用户是否授权创建博客。
       当定义一个不需要传入模型实例的策略方法时，比如 create 方法，你需要定义这个方法只接受已授权的用户作为参数*/

        /*public function create(User $user)
        {
            //
        }*/

        /*
         * 策略过滤器
        对特定用户，你可能希望通过指定的策略授权所有动作。
        要达到这个目的，可以在策略中定义一个 before 方法。
        before 方法会在策略中其他所有方法之前执行，这样提供了一种方式来授权动作而不是指定的策略方法来执行判断。
        这个功能最常见的场景是授权应用的管理员可以访问所有动作*/

        /*
         * 如果你想拒绝用户所有的授权，你应该在 before 方法中返回 false。
         * 如果返回的是 null，则通过其他的策略方法来决定授权与否*/
    }

    /**
     * 策略类授权
     * 如果给定模型的 策略已被注册，can 方法会自动调用核实的策略方法并且返回 boolean 值。
     * 如果没有策略注册到这个模型，can 和动方法会尝试调用作名相匹配的基于闭包的 Gate。
     */
    public function policy_auth()
    {
        $user = Auth::user();
        $shipping = ProductsInstockShipping::find(1);
        //
        if ($user->can('update', $shipping)) {
            //
        }



        /*
         * 不需要指定模型的动作
        一些动作，比如 create，并不需要指定模型实例。在这种情况下，可传递一个类名给 can 方法。
        当授权动作时，这个类名将被用来判断使用哪个策略*/
        if ($user->can('create', ProductsInstockShipping::class)) {
            //类名用来判断使用哪一个策略类  注意和模型实例区别
            // 执行相关策略中的「create」方法...
        }

        /*
         * 通过中间件
        Laravel 包含一个可以在请求到达路由或控制器之前就进行动作授权的中间件。
        默认，Illuminate\Auth\Middleware\Authorize 中间件被指定到 App\Http\Kernel 类中 can 键上。
        我们用一个授权用户更新博客的例子来讲解 can 中间件的使用：*/

        /*
         * 传递给 can 中间件 2 个参数。
         * 第一个是需要授权的动作的名称，
         * 第二个是我们希望传递给策略方法的路由参数。
         * 这里因为使用了 隐式模型绑定，一个 Post 会被传递给策略方法。
         * 如果用户不被授权访问指定的动作，这个中间件会生成带有 403 状态码的 HTTP 响应。*/
        Route::put('/post/{post}', function (Post $post) {
            // 当前用户可以更新博客...
        })->middleware('can:update,post');

        /*
         * 不需要指定模型的动作
        同样的，一些动作，比如 create，并不需要指定模型实例。
        在这种情况下，可传递一个类名给中间件。
        当授权动作时，这个类名将被用来判断使用哪个策略*/
        Route::post('/post', function () {
            // 当前用户可以创建博客...
        })->middleware('can:create,App\Post');


        /*
         * 通过控制器辅助函数
        除了在 User 模型中提供辅助方法外，Laravel 也为所有继承了 App\Http\Controllers\Controller
        基类的控制器提供了一个有用的 authorize 方法。和 can 方法类似，
        这个方法接收需要授权的动作和相关的模型作为参数。
        如果动作不被授权，authorize 方法会抛出 Illuminate\Auth\Access\AuthorizationException 异常，
        然后被 Laravel 默认的异常处理器转化为带有 403 状态码的 HTTP 响应：*/
        $this->authorize('update', $shipping);
        /*
         * 不需要指定模型的动作
        和之前讨论的一样，一些动作，比如 create，并不需要指定模型实例。
        在这种情况下，可传递一个类名给 authorize 方法。
        当授权动作时，这个类名将被用来判断使用哪个策略*/
        $this->authorize('create', ProductsInstockShipping::class);
    }
}
?>