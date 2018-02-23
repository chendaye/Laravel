<?php
namespace App\Test\Controllers;
use App\Http\Requests\Request;
use App\Jobs\QueueTest;
use App\User;
use Carbon\Carbon;

/**
 * Laravel 队列为不同的后台队列服务提供统一的 API ， 例如 Beanstalk，Amazon SQS， Redis，
 * 甚至其他基于关系型数据库的队列。
 *
 * 队列的目的是将耗时的任务延时处理，
 *
 * 比如发送邮件，从而大幅度缩短Web请求和相应的时间。
 * 队列配置文件存放在 config/queue.php。 每一种队列驱动的配置都可以在该文件中找到，
 * 包括数据库， Beanstalkd， Amazon SQS， Redis， 以及同步（本地使用）驱动。
 * 其中还包含了一个null队列驱动用于那些放弃队列的任务
 *
 *
 * 在开始使用 Laravel 队列前，弄明白 「连接」 和 「队列」 的区别是很重要的。
 * 在你的 config/queue.php 配置文件里， 有一个 connections 配置选项。
 * 这个选项给 Amazon SQS， Beanstalk ，或者 Redis 这样的后端服务定义了一个特有的连接。
 * 不管是哪一种，一个给定的连接可能会有多个「队列」，而 「队列」可以被认为是不同的栈或者大量的队列任务。
 * 要注意的是，
 * queue 配置文件中每个连接的配置示例中都包含一个 queue 属性。
 * 这是默认队列，任务被发给指定连接的时候会被分发到这个队列中。换句话说，
 *
 *
 * 如果你分发任务的时候没有显式定义队列，那么它就会被放到连接配置中 queue 属性所定义的队列中
 *
 * Class QueueController
 * @package App\Test\Controllers
 */
class QueueController extends Controller
{
    // 这个任务将被分发到默认队列...
    //dispatch(new Job);

    // 这个任务将被发送到「emails」队列...
    //dispatch((new Job)->onQueue('emails'));


    /*
     * 有些应用可能不需要把任务发到不同的队列，而只发到一个简单的队列中就行了。
     * 但是把任务推到不同的队列仍然是非常有用的，因为 Laravel 队列处理器允许你定义队列的优先级，
     * 所以你能给不同的队列划分不同的优先级或者区分不同任务的不同处理方式了。
     * 比如说，如果你把任务推到 high 队列中，你就能让队列处理器优先处理这些任务了*/

    //php artisan queue:work --queue=high,default

    /*驱动的必要设置*/
    /*
     * 数据库
     * 要使用 database 这个队列驱动的话， 你需要创建一个数据表来存储任务，
     * 你可以用 queue:table 这个 Artisan 命令来创建这个数据表的迁移。
     * 当迁移创建好以后，就可以用 migrate 这条命令来创建数据表*/
    //php artisan queue:table
    //php artisan migrate

    /*
     * 如果你的 Redis 队列连接使用的是 Redis 集群， 你的队列名称必须包含 key hash tag 。
     * 这是为了确保所有的 redis 键对于一个给定的队列都置于同一哈希中：
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => '{default}',
        'retry_after' => 90,
    ],*/

    /*
     * 其它队列驱动的依赖扩展包
     * 在使用列表里的队列服务前，必须安装以下依赖扩展包：
    Amazon SQS: aws/aws-sdk-php ~3.0
    Beanstalkd: pda/pheanstalk ~3.0
    Redis: predis/predis ~1.0*/


    /*
     * 生成任务类在你的应用程序中，队列的任务类都默认放在 app/Jobs 目录下，
     * 如果这个目录不存在，那当你运行 make:job artisan 命令时目录就会被自动创建。
     *  你可以用以下的 Artisan 命令来生成一个新的队列任务*/
    //php artisan make:job SendReminderEmail

    /*
     * 生成的类实现了 Illuminate\Contracts\Queue\ShouldQueue 接口，
     * 这意味着这个任务将会被推送到队列中，而不是同步执行*/


    /*
     * 任务类结构
     * 任务类的结构很简单，一般来说只会包含一个让队列用来调用此任务的 handle 方法。
     * 我们来看一个示例的任务类，这个示例里，
     * 假设我们管理着一个播客发布服务，在发布之前需要处理上传播客文件*/

    /*
     * 分发任务
     * 你写好任务类后，就能通过 dispatch 辅助函数来分发它了。
     * 唯一需要传递给 dispatch 的参数是这个任务类的实例*/

    public function dispatch(Request $request)
    {
        $user = new User();
        //分发任务
        dispatch(new QueueTest($user));
    }

    /*
     * 延迟分发
     * 如果你想延迟执行一个队列中的任务，你可以用任务实例的 delay 方法。
     *  这个方法是 Illuminate\Bus\Queueable trait 提供的，
     * 而这个 trait 在所有自动生成的任务类中都是默认加载了的。
     * 对于延迟任务我们可以举个例子，比如指定一个被分发10分钟后才执行的任务*/
    public function dispatch_delay(Request $request)
    {
        $user = new User();
        $job = (new QueueTest($user))->delay(Carbon::now()->addMinutes(10));
        dispatch($job);
    }

    /*自定义队列 & 连接*/
    /*
     * 分发任务到指定队列
     * 通过推送任务到不同的队列，你可以给队列任务分类，甚至可以控制给不同的队列分配多少任务。
     * 记住，这个并不是要推送任务到队列配置文件中不同的 「connections」 里，
     * 而是推送到一个连接中不同的队列里。要指定队列的话，就调用任务实例的 onQueue 方法*/

    public function onQueue(Request $request)
    {
        $user = new User();
        $job = (new QueueTest($user))->onQueue('processing');
        dispatch($job);
    }

    /*
     * 分发任务到指定连接
     * 如果你使用了多个队列连接，你可以把任务推到指定连接。
     * 要指定连接的话，你可以调用任务实例的 onConnection 方法*/
    public function onConnection(Request $request)
    {
        $user = new User();
        $job = (new QueueTest($user))->onConnection('sqs');
        dispatch($job);

        //可以链式调用 onConnection 和 onQueue 来同时指定任务的连接和队列
        $job = (new QueueTest($user))
            ->onConnection('sqs')
            ->onQueue('processing');
    }

    /*
     * 指定任务最大尝试次数 / 超时值最大尝试次数
     * 在一项任务中指定最大的尝试次数可以尝试通过 Artisan 命令行 --tries 来设置：
     * php artisan queue:work --tries=3
     * 但是，你可以采取更为精致的方法来完成这项工作比如说在任务类中定义最大尝试次数。
     * 如果在类和命令行中都定义了最大尝试次数， Laravel 会优先执行任务类中的值*/

    /*
     * 超时
     * 同样的，任务可以运行的最大秒数可以使用 Artisan 命令行上的 --timeout 开关指定：
     * php artisan queue:work --timeout=30
     * 然而，你也可以在任务类中定义一个变量来设置可运行的最大描述，
     * 如果在类和命令行中都定义了最大尝试次数， Laravel 会优先执行任务类中的值*/

    /*
     * 错误处理
     * 如果任务运行的时候抛出异常，这个任务就自动被释放回队列，这样它就能被再重新运行了。
     * 如果继续抛出异常，这个任务会继续被释放回队列，
     * 直到重试次数达到你应用允许的最多次数。
     * 这个最多次数是在调用 queue:work Artisan 命令的时候通过 --tries 参数来定义的。
     * 更多队列处理器的信息可以 在下面看到*/

    /*
     * Laravel 包含一个队列处理器，当新任务被推到队列中时它能处理这些任务。
     * 你可以通过 queue:work Artisan 命令来运行处理器。要注意，
     * 一旦 queue:work 命令开始，它将一直运行，直到你手动停止或者你关闭控制台
     * php artisan queue:work
     *
     * 一定要记得，队列处理器是长时间运行的进程，并在内存里保存着已经启动的应用状态。
     * 这样的结果就是，处理器运行后如果你修改代码那这些改变是不会应用到处理器中的。
     * 所以在你重新部署过程中，一定要 重启队列处理器
     *
     * 指定连接 & 队列
     * 你可以指定队列处理器所使用的连接。你在 config/queue.php 配置文件里定义了多个连接，
     * 而你传递给 work 命令的连接名字要至少跟它们其中一个是一致的：
     * php artisan queue:work redis
     * 你可以自定义队列处理器，方式是处理给定连接的特定队列。
     * 举例来说，如果你所有的邮件都是在 redis 连接中的 emails 队列中处理的，
     * 你就能通过以下命令启动一个只处理那个特定队列的队列处理器了：
     * php artisan queue:work redis --queue=emails
     *
     * 资源注意事项
     * 守护程序队列不会在处理每个作业之前 「重新启动」 框架。
     * 因此，在每个任务完成后，您应该释放任何占用过大的资源。
     * 例如，如果你使用GD库进行图像处理，你应该在完成后用 imagedestroy 释放内存*/

    /*
     * 队列优先级
     * 有时候你希望设置处理队列的优先级。
     * 比如在 config/queue.php 里你可能设置了 redis 连接中的默认队列优先级为 low，
     * 但是你可能偶尔希望把一个任务推到 high 优先级的队列中，像这样：
     * dispatch((new Job)->onQueue('high'));*/

    public function height()
    {
        $user = new User();
        $job = (new QueueTest($user));
        dispatch(($job)->onQueue('high'));

        /*
         * 要验证 high 队列中的任务都是在 low 队列中的任务之前处理的，
         * 你要启动一个队列处理器，传递给它队列名字的列表并以英文逗号，间隔：
         * php artisan queue:work --queue=high,low*/
    }


    /*
     * 队列处理器 & 部署
     * 因为队列处理器都是 long-lived 进程，如果代码改变而队列处理器没有重启，
     * 他们是不能应用新代码的。所以最简单的方式就是重新部署过程中要重启队列处理器。
     * 你可以很优雅地只输入 queue:restart 来重启所有队列处理器。
     * php artisan queue:restart
     * 这个命令将会告诉所有队列处理器在执行完当前任务后结束进程，这样才不会有任务丢失。
     * 因为队列处理器在执行 queue:restart 命令时对结束进程，你应该运行一个进程管理器，
     * 比如 Supervisor 来自动重新启动队列处理器。*/

    /*
     * 任务过期 & 超时
     * 任务过期
     * config/queue.php 配置文件里，每一个队列连接都定义了一个 retry_after 选项。
     * 这个选项指定了任务最多处理多少秒后就被当做失败重试了。
     * 比如说，如果这个选项设置为 90，那么当这个任务持续执行了 90 秒而没有被删除，
     * 那么它将被释放回队列。通常情况下，你应该把 retry_after 设置为最长耗时的任务所对应的时间。
     *
     * 队列处理器超时
     * queue:work Artisan 命令对外有一个 --timeout 选项。
     * 这个选项指定了 Laravel 队列处理器最多执行多长时间后就应该被关闭掉。
     * 有时候一个队列的子进程会因为很多原因僵死，
     * 比如一个外部的 HTTP 请求没有响应。这个 --timeout 选项会移除超出指定事件限制的僵死进程。
     * php artisan queue:work --timeout=60
     * retry_after 配置选项和 --timeout 命令行选项是不一样的，
     * 但是可以同时工作来保证任务不会丢失并且不会重复执行。
     *
     * 队列进程睡眠时间
     * 当队列需要处理任务时，进程将继续处理任务，它们之间没有延迟。
     *  但是，如果没有新的工作可用，sleep 参数决定了工作进程将「睡眠」多长时间：
     * php artisan queue:work --sleep=3*/

    /*
     * Supervisor 配置
     * 安装 Supervisor
     * Supervisor 是一个 Linux 操作系统上的进程监控软件，
     * 它会在 queue:listen 或 queue:work 命令发生失败后自动重启它们*/

    /*
     * 处理失败的任务
     * 有时候你队列中的任务会失败。不要担心，本来事情就不会一帆风顺。
     * Laravel 内置了一个方便的方式来指定任务重试的最大次数。
     * 当任务超出这个重试次数后，它就会被插入到 failed_jobs 数据表里面。
     * 要创建 failed_jobs 表的话，你可以用 queue:failed-table 命令：
     * php artisan queue:failed-table
     * php artisan migrate
     * 然后运行队列处理器，在调用 queue:work 命令时你应该通过 --tries 参数指定任务的最大重试次数。
     * 如果不指定，任务就会永久重试：
     * php artisan queue:work redis --tries=3*/

    /*
     * 清除失败任务
     * 你可以在任务类里直接定义 failed 方法，它能在任务失败时运行任务的清除逻辑。
     * 这个地方用来发一条警告给用户或者重置任务执行的操作等再好不过了。
     * 导致任务失败的异常信息会被传递到 failed 方法*/

    /*
     * 任务失败事件
     * 如果你想注册一个当队列任务失败时会被调用的事件，则可以用 Queue::failing 方法。
     * 这样你就有机会通过这个事件来用 e-mail 或 HipChat 通知你的团队。
     * 例如我们可以在 Laravel 内置的 AppServiceProvider 中对这个事件附加一个回调函数*/

    /*
     * 重试失败任务
     * 要查看你在 failed_jobs 数据表中的所有失败任务，则可以用 queue:failed 这个 Artisan 命令：
     * php artisan queue:failed
     * queue:failed 命令会列出所有任务的 ID、连接、队列以及失败时间，
     * 任务 ID 可以被用在重试失败的任务上。例如要重试一个 ID 为 5 的失败任务，其命令如下：
     * php artisan queue:retry 5
     * 要重试所有失败的任务，可以使用 queue:retry 并使用 all 作为 ID：
     * php artisan queue:retry all
     * 如果你想删除掉一个失败任务，可以用 queue:forget 命令：
     * php artisan queue:forget 5
     * ueue:flush 命令可以让你删除所有失败的任务：
     * php artisan queue:flush*/


    /*
     * 任务事件
     * 使用队列的 before 和 after 方法，你能指定任务处理前和处理后的回调处理。
     * 在这些回调里正是实现额外的日志记录或者增加统计数据的好时机。
     * 通常情况下，你应该在 服务容器 中调用这些方法。例如，我们使用 Laravel 中的 AppServiceProvider*/
}


?>