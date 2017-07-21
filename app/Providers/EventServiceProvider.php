<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        //注册事件 监听器
        'App\Events\EvenTest' => [
            'App\Listeners\ListenerTest',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();


        /*
         * 手动注册事件
         * 一般来说，事件必须通过 EventServiceProvider 类的 $listen 数组进行注册；
         * 不过，你也可以在 EventServiceProvider 类的 boot 方法中注册闭包事件*/
        Event::listen('event.name', function ($foo, $bar) {
            //
        });


        /*
         * 通配符事件监听器
         * 你甚至可以在注册监听器时使用 * 通配符参数，它让你在一个监听器中可以监听到多个事件。
         * 通配符监听器接受的第一个参数是事件名称，第二个参数是整个的事件数据*/
        Event::listen('event.*', function ($eventName, array $data) {
            //
        });

    }
}
