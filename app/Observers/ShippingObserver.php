<?php
namespace App\Observers;


use App\ProductsInstockShipping;

/**
 * 观察者
 * 如果你在一个给定的模型中监听许多事件，您可以使用观察者将所有监听器变成一个类。
 * 观察者类里的方法名应该反映Eloquent想监听的事件。
 * 每种方法接收 model 作为其唯一的参数。 Laravel不包括观察者默认目录，所以你可以创建任何你喜欢你的目录来存放
 *
 * 要注册一个观察者，需要用模型中的observe方法去观察。
 * 你可以在你的服务提供商之一的boot方法中注册观察者。在这个例子中，我们将在AppServiceProvider注册观察者
 *
 *
 * Eloquent 模型会触发许多事件，让你在模型的生命周期的多个时间点进行监控：
 * creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
 *
 * Class UserObserver
 * @package App\Observers
 */
class ShippingObserver
{
    /**
     * 监听用户创建的事件。
     *
     * 当一个新模型被初次保存将会触发 creating 以及 created 事件。
     * 如果一个模型已经存在于数据库且调用了 save 方法，将会触发 updating 和 updated 事件。在这两种情况下都会触发 saving 和 saved 事件
     *
     * @param  ProductsInstockShipping  $shipping
     * @return void
     */
    public function created(ProductsInstockShipping $shipping)
    {
        dump('created');
    }

    public function creating(ProductsInstockShipping $shipping)
    {
        dump('creating');
    }

    public function updating(ProductsInstockShipping $shipping)
    {
        dump('creating');
    }

    public function updated(ProductsInstockShipping $shipping)
    {
        dump('updated');
    }

    public function saving(ProductsInstockShipping $shipping)
    {
        dump('saving');
    }

    public function saved(ProductsInstockShipping $shipping)
    {
        dump('saved');
    }

    public function deleted(ProductsInstockShipping $shipping)
    {
        dump('deleted');
    }

    /**
     * 监听用户删除事件。
     *
     * @param  ProductsInstockShipping  $shipping
     * @return void
     */
    public function deleting(ProductsInstockShipping $shipping)
    {
        dump('deleting');
    }

    public function restoring(ProductsInstockShipping $shipping)
    {
        dump('restoring');
    }

    public function restored(ProductsInstockShipping $shipping)
    {
        dump('restored');
    }


}