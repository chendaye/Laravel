<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;

/**
 *
 * 创建表单请求
 * 在更复杂的验证情境中，你可能会想要创建一个「表单请求（ form request ）」。
 * 表单请求是一个自定义的请求类，里面包含着验证逻辑。
 * 要创建一个表单请求类，可使用 Artisan 命令行命令 make:request
 * php artisan make:request StoreBlogPost

 * 新生成的类保存在 app/Http/Requests 目录下。
 *如果这个目录不存在，那么将会在你运行 make:request 命令时创建出来。让我们添加一些验证规则到 rules 方法中

 * Class StoreBlog  继承自 App\Http\Requests\Request
 * @package App\Http\Requests
 */
class StoreBlog extends Request
{
    /**
     * 授权表单请求
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if(0){
            /*
             * 由于所有的表单请求都是扩展于基础的 Laravel 请求类，所以我们可以使用 user 方法去获取当前认证登录的用户。
             * 同时请注意上述例子中对 route 方法的调用。这个方法授权你获取调用的路由规则中的 URI 参数，譬如下面例子中的｛comment｝`参数：
            Route::post('comment/{comment}');
            如果 authorize 方法返回 false，则会自动返回一个 HTTP 响应，其中包含 403 状态码，而你的控制器方法也将不会被运行。*/
            $comment = Comment::find($this->route('comment'));  //表单请求类 也是 Request
            return $comment && $this->user()->can('update', $comment);
        }
        //如果你打算在应用程序的其它部分处理授权逻辑，只需从 authorize 方法返回 true
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *怎样才能较好的运行验证规则呢？你所需要做的就是在控制器方法中利用类型提示传入请求。
     * 传入的请求会在控制器方法被调用前进行验证，意思就是说你不会因为验证逻辑而把控制器弄得一团糟：
     *
     * @return array
     */
    public function rules()
    {
        /**
         * 请求规则
         */
        return [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ];
    }

    /**
     * 添加表单请求后钩子
     * 如果你想在表单请求「之后」添加钩子，你
     * 可以使用 withValidator 方法。这个方法接收一个完整的验证类，允许你在实际判断验证规则调之前调用验证类的所有方法：
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }

    /**
     * 获取已定义验证规则的错误消息。
     *你可以通过重写表单请求的 messages 方法来自定义错误消息。
     * 此方法必须返回一个数组，其中含有成对的属性或规则以及对应的错误消息
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'body.required'  => 'A message is required',
        ];
    }
}
