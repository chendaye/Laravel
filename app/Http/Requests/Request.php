<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * 自定义错误格式
     * 如果你想要自定义验证失败时闪存到 Session 的验证错误格式，
     * 可在你的基底请求 (App\Http\Requests\Request) 中重写 formatErrors。
     * 别忘了文件上方引入 Illuminate\Contracts\Validation\Validator 类
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}
