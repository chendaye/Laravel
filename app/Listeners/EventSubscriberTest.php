<?php

namespace App\Listeners;

/**
 * 编写事件订阅者
 * 事件订阅者是一个在自身内部可以订阅多个事件的类，允许你在单个类中定义多个事件处理器。
 * 订阅者应该定义一个 subscribe 方法，
 * 这个方法接受一个事件分发器的实例。你可以调用事件分发器的 listen 方法来注册事件监听器
 *
 * Class EventSubscriberTest
 * @package App\Listeners
 */
class EventSubscriberTest
{
    /**
     * 处理用户登录事件。
     */
    public function onUserLogin($event)
    {
        //
    }

    /**
     * 处理用户注销事件。
     */
    public function onUserLogout($event)
    {
        //
    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login', //登录
            'App\Listeners\EventSubscriberTest@onUserLogin' //登录的事件处理控制器
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',  //登出
            'App\Listeners\EventSubscriberTest@onUserLogout'    //登出的事件处理控制器
        );
    }

    /*
     * 注册事件订阅者
     * 一旦订阅者被定义，它就可以被注册到事件分发器中。
     * 你可以在 EventServiceProvider 类的  $subscribe 属性注册订阅者。
     * 例如，添加 UserEventSubscriber 到列表中*/

}