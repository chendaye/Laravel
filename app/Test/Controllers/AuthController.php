<?php
namespace App\Test\Controllers;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * 手动用户认证
 * Class AuthController
 * @package App\Test\Controllers
 */
class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate()
    {
        /**
         * attempt 方法会接受一个数组来作为第一个参数，这个数组的值可用来寻找数据库里的用户数据，
         * 所以在上面的例子中，用户通过 email 字段被取出，如果用户被找到了，数据库里经过哈希的密码将会与数组中哈希的 password 值比对，如果两个值一样的话就会开启一个通过认证的 session 给用户。
         * 如果认证成功，attempt 方法将会返回 true，反之则为 false。
         * 重定向器上的 intended 方法将会重定向用户回原本想要进入的页面，也可以传入一个回退 URI 至这个方法，以避免要转回的页面不可使用
         */
        if (Auth::attempt(['email' => '', 'password' => ''])) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }

        /*
         * 指定额外条件
        可以加入除用户的邮箱及密码外的额外条件进行认证查找。例如，我们要确认用户是否被标示为 active*/
        if (Auth::attempt(['email' => '', 'password' => '', 'active' => 1])) {
            // The user is active, not suspended, and exists.
        }

        /*
         * 访问指定 Guard 实例
        可以通过 Auth facade 的 guard 方法来指定使用特定的 guard 实例。这样可以实现在应用不同部分管理用户认证时使用完全不同的认证模型或者用户表。
        传递给 guard 方法 guard 名称必须是 auth.php 配置文件中 guards 的值之一*/
        if (Auth::guard('admin')->attempt([])) {
            //
        }

        /*
         * 注销用户
        要想让用户注销，你可以使用 Auth facade 的 logout 方法。这个方法会清除所有认证后加入到用户 session 的数据*/
        Auth::logout();

        /*
         * 记住用户
        如果你想要提供「记住我」的功能，你需要传入一个布尔值到 attempt 方法的第二个参数，在用户注销前 session 值都会被一直保存。
        users 数据表一定要包含一个 remember_token 字段，这是用来保存「记住我」令牌的*/
        if (Auth::attempt(['email' => '', 'password' => ''], $remember = true)) {
            // 这个用户被记住了...
        }
        //可以使用 viaRemember 方法来检查这个用户是否使用「记住我」 cookie 来做认证：
        if (Auth::viaRemember()) {
            //
        }
    }

    /**
     * 其它认证方法
     */
    public function other_auth()
    {
        /*
         * 用「用户实例」做认证
        如果你需要使用存在的用户实例来登录，你需要调用 login 方法，并传入使用实例，
        这个对象必须是由 Illuminate\Contracts\Auth\Authenticatable contract 所实现。当然，App/User 模型已经实现了这个接口*/
        $user = User::find(1);
        Auth::login($user);
        // 登录并且「记住」用户
        Auth::login($user, true);

        //你也可以指定 guard 实例：
        Auth::guard('admin')->login($user);

        /*
         * 通过用户 ID 做认证
        使用 loginUsingId 方法来登录指定 ID 用户，这个方法接受要登录用户的主键*/
        Auth::loginUsingId(1);
        // 登录并且「记住」用户
        Auth::loginUsingId(1, true);

        /*
         * 仅在本次认证用户
        可以使用 once 方法来针对一次性认证用户，没有任何的 session 或 cookie 会被使用，
        这个对于构建无状态的 API 非常的有用，once 方法跟 attempt 方法拥有同样的传入参数*/
        if (Auth::once([])) {
            //
        }
    }
}
?>