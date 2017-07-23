<?php
namespace App\Test\Controllers;
use App\Events\EventTest;

/**
 * 事件系统
 * Laravel 事件机制实现了一个简单的观察者模式，让我们可以订阅和监听应用中出现的各种事件。
 * 事件类 (Event) 类通常保存在 app/Events 目录下，而它们的监听类 (Listener) 类被保存在 app/Listeners 目录下。
 * 如果你在应用中看不到这些文件夹也不要担心，因为当你使用 Artisan 命令来生成事件和监听器时他们会被自动创建。
 * 事件机制是一种很好的应用解耦方式，因为一个事件可以拥有多个互不依赖的监听器。
 * 例如，每次把用户的订单发完货后都希望给他发个 Slack 通知。
 * 这时候你可以发起一个 OrderShipped 事件，它会被监听器接收到再传递给 Slack 通知模块，
 * 这样你就不用把订单处理的代码跟 Slack 通知的代码耦合在一起了。
 * Class EventController
 * @package App\Test\Controllers
 */
class EventController extends Controller
{

    /*
     * 注册事件和监听器
     * Laravel 应用中的 EventServiceProvider 提供了一个很方便的地方来注册所有的事件监听器。
     * 它的 listen 属性是一个数组，包含所有的事件（键）以及事件对应的监听器（值）。
     * 你也可以根据应用需求来增加事件到这个数组中。例如，增加一个 OrderShipped 事件*/

    /*
     * 生成事件和监听器
     * 当然，手动创建每个事件和监听器是很麻烦的。
     * 简单的方式是，在 EventServiceProvider 类中添加好事件和监听器，然后使用 event:generate 命令。
     * 这个命令会自动生成 EventServiceProvider 类中列出的所有事件和监听器。
     * 当然已经存在的事件和监听器将保持不变：
     *
     * php artisan event:generate
     * 此命令会自动生成已经注册的 Event 和 Listener 文件 并引入适当的命名空间
     */


    /*
     * 手动注册事件
     * 一般来说，事件必须通过 EventServiceProvider 类的 $listen 数组进行注册；
     * 不过，你也可以在 EventServiceProvider 类的 boot 方法中注册闭包事件
     */

    /*
     * 定义事件
     * 事件类就是一个包含与事件相关信息数据的容器。
     * 例如，假设我们生成的 OrderShipped 事件接受一个 Eloquent ORM 对象*/

    /*
     * 事件的监听器。
     * 事件监听器在 handle 方法中接受了事件实例作为参数。
     * event:generate 命令将会在事件的 handle 方法中自动加载正确的事件类和类型提示。
     * 在 handle 方法中，你可以运行任何需要响应该事件的业务逻辑*/


    /*
     * 队列化事件监听器
     * 如果你的监听器中需要实现一些耗时的任务，比如发送邮件或者进行 HTTP 请求，
     * 那把它放到队列中处理是非常有用的。
     * 在使用队列化监听器之前，一定要在服务器或者本地环境中配置 队列 并开启一个队列监听器。
     * 要对监听器进行队列化的话，只需增加 ShouldQueue 接口到你的监听器类。
     * 由 Artisan 命令  event:generate 生成的监听器已经将此接口导入到命名空间了，因此你可以直接使用它*/


    /*
     * 处理失败任务
     * 有时你队列化的事件监听器可能失败了。
     * 如果队列监听器任务执行次数超过在工作队列中定义的最大尝试次数，
     * 监听器的 failed 方法将会被自动调用。 failed 方法接受事件实例和失败的异常作为参数*/

    /*
     * 触发事件
     * 如果要触发事件，你可以传递一个事件实例给 event 辅助函数。
     * 这个函数将会把事件分发到它所有已经注册的监听器上。
     * 因为 event 函数是全局可访问的，所以你可以在应用中的任何地方调用它*/

    /**
     * 将传递过来的订单发货。
     *触发事件
     * 如果要触发事件，你可以传递一个事件实例给 event 辅助函数。
     * 这个函数将会把事件分发到它所有已经注册的监听器上。
     * 因为 event 函数是全局可访问的，所以你可以在应用中的任何地方调用它
     *
     * @param  int  $orderId
     * @return Response
     */
    public function ship($orderId)
    {
        $order = Order::findOrFail($orderId);

        // 订单的发货逻辑...  触发一个事件 事件调用其监听器处理 任务
        event(new EventTest($order));
    }


    /*
     * 事件订阅者
     * 编写事件订阅者
     * 事件订阅者是一个在自身内部可以订阅多个事件的类，允许你在单个类中定义多个事件处理器。
     * 订阅者应该定义一个 subscribe 方法，这个方法接受一个事件分发器的实例。
     * 你可以调用事件分发器的 listen 方法来注册事件监听器*/

}
?>