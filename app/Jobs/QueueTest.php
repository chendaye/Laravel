<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

class QueueTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * 任务最大尝试次数
     *
     * @var int
     */
    public $tries = 5;

    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //处理具体的任务

        /*
         * 注意，在这个例子中，我们在任务类的构造器中直接传递了一个 Eloquent 模型。
         * 因为我们在任务类里引用了 SerializesModels 这个 ，
         * 使得 Eloquent 模型在处理任务时可以被优雅地序列化和反序列化。
         * 如果你的队列任务类在构造器中接收了一个 Eloquent 模型，
         * 那么只有可识别出该模型的属性会被序列化到队列里。当任务被实际运行时，
         * 队列系统便会自动从数据库中重新取回完整的模型。这整个过程对你的应用程序来说是完全透明的，
         * 这样可以避免在序列化完整的 Eloquent 模式实例时所带来的一些问题。
         * 在队列处理任务时，会调用 handle 方法，而这里我们也可以通过 handle 方法的参数类型提示，
         * 让 Laravel 的 服务容器 自动注入依赖对象*/
    }

    /**
     * 要处理的失败任务。
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // 给用户发送失败通知，等等...
    }
}
