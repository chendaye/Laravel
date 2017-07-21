<?php

namespace App\Listeners;

use App\Events\EvenTest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 实现implements ShouldQueue 接口
 * 实现队列化事件监听器
 *
 * 就这样！当事件被监听器调用时， 事件分发器会使用 Laravel 的 队列系统 自动将它进行队列化。
 * 如果监听器通过队列运行且没有抛出任何异常，则已执行完的任务将会自动从队列中删除
 *
 * Class ListenerTest
 * @package App\Listeners
 */
class ListenerTest implements ShouldQueue
{

    /*
     * 手动访问队列
     * 如果你需要手动访问底层队列任务的 delete 和 release 方法，
     * 你可以使用  Illuminate\Queue\InteractsWithQueue trait 来实现。
     * 这个 trait 在生成的监听器中是默认加载的，它提供了这些方法*/
    use InteractsWithQueue;


    /*
     * 自定义队列的连接和名称
     * 如果你想要自定义队列的连接和名称，
     * 你可以在监听器类中定义 $connection 和 $queue 属性。*/

    /**
     * 队列化任务使用的连接名称。
     *
     * @var string|null
     */
    public $connection = 'sqs';

    /**
     * 队列化任务使用的队列名称。
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 这个事件监听器 被注入了  EvenTest  事件的实例
     * @param  EvenTest  $event
     * @return void
     */
    public function handle(EvenTest $event)
    {
        /*
         * 事件的监听器。事件监听器在 handle 方法中接受了事件实例作为参数。
         * event:generate 命令将会在事件的 handle 方法中自动加载正确的事件类和类型提示。
         * 在 handle 方法中，你可以运行任何需要响应该事件的业务逻辑*/
        dump($event->user);

        /*
         * 你的事件监听器也可以在构造函数中对任何依赖使用类型提示。
         * 所有的事件监听器会经由 Laravel 的 服务容器 做解析，所以所有的依赖都将会被自动注入*/

        /*
         * 手动访问队列
         * 如果你需要手动访问底层队列任务的 delete 和 release 方法，
         * 你可以使用  Illuminate\Queue\InteractsWithQueue trait 来实现。
         * 这个 trait 在生成的监听器中是默认加载的，它提供了这些方法*/
        if (true) {
            $this->release(30);
        }
    }


    /**
     * 处理失败任务
     * 有时你队列化的事件监听器可能失败了。
     * 如果队列监听器任务执行次数超过在工作队列中定义的最大尝试次数，
     * 监听器的 failed 方法将会被自动调用。 failed 方法接受事件实例和失败的异常作为参数
     * @param EvenTest $event
     * @param $exception
     */
    public function failed(EvenTest $event, $exception)
    {
        dump($exception->getMessage());
    }
}
