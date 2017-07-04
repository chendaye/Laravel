<?php
namespace App\Test\Controllers;

/**
 * 资源路由控制器
 * 如果你使用了路由模型绑定，并且想在资源控制器的方法中对某个模型实例做类型约束，
 * 你可以在生成控制器的时候使用 --model 选项：
 * php artisan make:controller PhotoController --resource --model=Photo
 * Class ResourceController
 * @package App\Test\Controllers
 */
class ResourceController extends Controller
{
    public function index()
    {
        return 'GET /photos';
    }

    public function create()
    {
        return 'GET /photos/create';
    }

    public function store()
    {
        return 'POST /photos';
    }

    public function show()
    {
        return 'GET /photos/{photo}';
    }

    public function edit()
    {
        return 'GET /photos/{photo}/edit';
    }

    public function update()
    {
        return 'PUT/PATCH /photos/{photo}';
    }

    public function destroy()
    {
        return 'DELETE /photos/{photo}';
    }

    /**
     * 附加资源控制器
     * 应该在调用 Route::resource 之前定义这些路由；否则，resource 方法定义的路由可能会不小心覆盖你的附加路由
     * @return string
     */
    public function method()
    {
        return '附加资源控制器';
    }
}