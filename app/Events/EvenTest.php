<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 定义事件
 * 事件类就是一个包含与事件相关信息数据的容器。
 * 例如，假设我们生成的 OrderShipped 事件接受一个 Eloquent ORM 对象
 * Class EvenTest
 * @package App\Events
 */
class EvenTest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;


    /**
     * EvenTest constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
