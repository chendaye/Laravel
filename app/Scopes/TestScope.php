<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * 定义全局作用域
 * Class TestScope
 * @package App\Scopes
 */
class TestScope implements Scope
{
    /**
     * 应用作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        /*
         * 应用全局作用域
         * 要将全局作用域分配给模型，需要重写给定模型的 boot 方法并使用 addGlobalScope 方法*/
        return $builder->where('products_instock_id', '=', 257);
    }
}