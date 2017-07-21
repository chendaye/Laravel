<?php
namespace App\Test\Controllers;

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

}
?>