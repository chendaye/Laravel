<?php
namespace App\Test\Controllers;
use Illuminate\Http\Request;

/**
 * 由于 HTTP 是无状态的，Session 提供了一种在多个请求之间存储有关用户信息的方法。
 * Laravel 附带支持了多种 Session 后端驱动，它们都可以通过语义化统一的 API 访问。
 * Laravel 本身支持比较热门的 Session 后端驱动，如 Memcached、Redis 和数据库。
 *
 *Session 相关的配置文件存储在 config/session.php。请务必查看此文件中对于你可用的选项。
 * 默认设置下，Laravel 的配置是使用文件作为 Session 驱动，大多数情况下能够运行良好。
 * 在生产环境下，你可以考虑使用 memcached 或 redis 驱动来达到更出色的性能表现。
 *
 * Session 配置的 driver 的选项定义了每次请求的 Session 数据的存储位置。Laravel 附带了几个不错且可开箱即用的驱动：
 * file - 将 Session 保存在 storage/framework/sessions。
 * cookie - Session 保存在安全加密的 Cookie 中。
 * database - Session 保存在关系型数据库。
 * memcached / redis - 将 Sessions 保存在其中一个快速且基于缓存的存储系统中。
 * array - 将 Sessions 保存在简单的 PHP 数组中，并只存在于本次请求.
 *
 * 数据库驱动要建表
 * php artisan session:table
 * php artisan migrate
 *
 * Class SessionController
 * @package App\Test\Controllers
 */
class SessionController extends Controller
{
    /**
     * 获取 Session 数据
     * Laravel 中有两种主要的方式使用 Session 数据的方式：一种是全局的辅助函数 session，另一种是通过 HTTP 请求实例。
     */
    public function get_session(Request $request)
    {
        //todo:通过具有控制器方法类型提示的 HTTP 请求实例来访问 Session
        $value = $request->session()->get('key');
        //当从 Session 获取值时，你也可以传递一个默认值作为 get 方法的第二个参数。
        //如果 Session 中并不存在指定的键值便会返回传入的默认值。
        //若传递一个闭包作为 get 方法的默认值且请求的键值并不存在时，此时 get 方法会返回这个闭包函数运行后的返回值：
        $value = $request->session()->get('key', 'default');

        $value = $request->session()->get('key', function () {
            return 'default';
        });

        //todo：全局 Session 辅助函数
        //你也可以使用全局的 PHP 函数 session 来获取和存储 Session 数据。
        // 使用单个字符串类型的值作为参数调用 session 函数时，
        //它将返回字该符串参数对应的 Session 键值。
        //当使用一个 key / value 键值对数组作为参数调用 session 函数时，传入的键值将会存入 Session

        // 获取 Session 中的一条数据...
        $value = session('key');
        // 指定一个默认值...
        $value = session('key', 'default');
        // 存储一条数据至 Session 中...
        session(['key' => 'value']);

        //todo:获取所有 Session 数据
        $data = $request->session()->all();

        //todo:判断某个 Session 值是否存在
        if ($request->session()->has('users')) {
            //使用 has 方法检查某个值是否存在于 Session 内，如果该值存在并且不为 null，那么则返回 true
        }
        if ($request->session()->exists('users')) {
            //在判断值是否在 Session 中是否存时，如果该值可能为 null，你需要使用 exists 方法，如果该值存在，那么则返回 true
        }

    }

    /**
     * 保存session数据
     * @param Request $request
     */
    public function save_session(Request $request)
    {
        //todo:存储数据到 Session，你可用使用 put 方法，或者 session 辅助函数。
        // 通过 HTTP 请求实例...
        $request->session()->put('key', 'value');
        // 通过全局辅助函数
        session(['key' => 'value']);

        //todo:保存数据进 Session 数组值中
        //push 方法可以将一个新的值加入至一个 Session 数组内
        $request->session()->push('user.teams', 'developers');

        //todo:从 Session 中取出并删除数据
        $value = $request->session()->pull('key', 'default');

        //todo:闪存数据到 Session
        //有时候你想存入一条缓存的数据，让它只在下一次的请求内有效，则可以使用 flash 方法。使用这个方法保存 session，
        //只能将数据保留到下个 HTTP 请求，然后就会被自动删除
        $request->session()->flash('status', 'Task was successful!');

        //如果需要保留闪存数据给更多请求，可以使用 reflash 方法，这将会将所有的闪存数据保留给额外的请求
        $request->session()->reflash();

        //如果想保留特定的闪存数据，则可以使用 keep 方法
        $request->session()->keep(['username', 'email']);

    }

    /**
     * 删除session
     * @param Request $request
     */
    public function remove_session(Request $request)
    {
        //forget 方法可以从 Session 内删除一条数据
        $request->session()->forget('key');

        //如果你想删除 Session 内所有数据，则可以使用 flush 方法
        $request->session()->flush();
    }

    /**
     * 重新生成 Session ID，通常时为了防止恶意用户利用 session fixation 对应用进行攻击。
     * @param Request $request
     */
    public function session_id(Request $request)
    {
        //如果你使用了内置函数 LoginController，那么 Laravel 会自动重新生成 Session ID，
        //否则，你需要手动使用 regenerate 方法重新生成 Session ID
        $request->session()->regenerate();
    }
}
?>