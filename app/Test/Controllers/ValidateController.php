<?php
namespace App\Test\Controllers;

use App\Http\Requests\StoreBlog;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator ;
use Illuminate\Support\MessageBag;

/**
 * Laravel 提供了多种不同的验证方法来对应用程序传入的数据进行验证。
 * 默认情况下，Laravel 的基类控制器使用 ValidatesRequests Trait，
 * 它提供了方便的方法使用各种强大的验证规则来验证传入的 HTTP 请求数据。
 *
 *
 * Class ValidateController
 * @package App\Test\Controllers
 */
class ValidateController extends Controller
{

    /**
     * 保存一个新的博客文章。
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // 验证以及保存博客发表文章...
        $this->validate($request, [
            //在某个属性第一次验证失败后停止运行验证规则。为了达到这个目的，附加 bail 规则到该属性
            //如果 title 字段 没有通过 required 的验证规则，那么 unique 这个规则将不会被检测了
            'title' => 'bail|required|unique:posts|max:255',
            'body' => 'required',
            //如果你的 HTTP 请求包含一个 「嵌套的」 参数，你可以在验证规则中通过 「点」 语法来指定这些参数
            'author.name' => 'required',
            'author.description' => 'required',
            //如果您不希望验证程序将「null」值视为无效的，您通常需要将「可选」的请求字段标记为 nullable
            //指定 publish_at 字段可以为 null 或者一个有效的日期格式。
            //如果 nullable 的修饰词没有添加到规则定义中，验证器会认为 null 是一个无效的日期格式
            'publish_at' => 'nullable|date',
        ]);
    }

    /**
     * 表单请求验证
     * 传入的请求会在控制器方法被调用前进行验证
     * @param StoreBlog $request
     */
    public function FormRequest(StoreBlog $request)
    {
        /*
         * 创建表单请求
        在更复杂的验证情境中，你可能会想要创建一个「表单请求（ form request ）」。
        表单请求是一个自定义的请求类，里面包含着验证逻辑。
        要创建一个表单请求类，可使用 Artisan 命令行命令 make:request
        php artisan make:request StoreBlogPost

        新生成的类保存在 app/Http/Requests 目录下。
        如果这个目录不存在，那么将会在你运行 make:request 命令时创建出来。让我们添加一些验证规则到 rules 方法中

        怎样才能较好的运行验证规则呢？你所需要做的就是在控制器方法中利用类型提示传入请求。
        传入的请求会在控制器方法被调用前进行验证，意思就是说你不会因为验证逻辑而把控制器弄得一团糟：
        */

        /*
         * 如果验证失败，就会生成一个重定向响应把用户返回到先前的位置。
         * 这些错误会被闪存到 Session，所以这些错误都可以被显示。
         * 如果进来的是 AJAX 请求的话，则会传回一个 HTTP 响应，
         * 其中包含了 422 状态码和验证错误的 JSON 数据*/
    }

    /**
     * 手动创建验证请求
     * 如果你不想要使用 ValidatesRequests Trait 的 validate 方法，
     * 你可以手动创建一个 validator 实例并通过 Validator::make 方法在 Facade 生成一个新的 validator 实例
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
       if(1){
           $validator = Validator::make($request->all(), [
               'title' => 'required|unique:posts|max:255',
               'body' => 'required',
           ]);

           if ($validator->fails()) {
               return redirect('post/create')
                   ->withErrors($validator)
                   ->withInput();
           }
       }

       if(1){
           /*验证后钩子
           验证器允许你在验证完成之后附加回调函数。这使得你可以容易的执行进一步验证，
           甚至可以在消息集合中添加更多的错误信息。使用它只需在验证实例中使用 after 方法：*/

           $validator = Validator::make(...);

           $validator->after(function ($validator) {
               if ($this->somethingElseIsInvalid()) {
                   $validator->errors()->add('field', 'Something is wrong with this field!');
               }
           });

           if ($validator->fails()) {
               //
           }
       }

        // 创建文章


        /*
         * 命名错误包
        如果你在一个一个页面中有多个表单，你也许会希望命名错误信息包 MessageBag ，
        错误信息包允许你从指定的表单中接收错误信息。简单的给 withErrors 方法传递第二个参数作为一个名*/

        return redirect('register')
            ->withErrors($validator, 'login');
        //然后你能从 $errors 变量中获取到 MessageBag 实例  {{ $errors->login->first('email') }}
    }

    /**
     * 处理错误消息
     * 调用 Validator 实例的 errors 方法，会得到一个 Illuminate\Support\MessageBag 的实例，
     * 里面有许多可让你操作错误消息的便利方法。
     * $errors 值可以自动的被所有的视图获取，并且是一个MessageBag类的实例。
     * 自动对所有视图可用的 $errors 变量也是 MessageBag 类的一个实例。
     *
     * @param Request $request
     */
    public function deal_error(Request $request)
    {
        //自定义错误消息
        $messages = [
            'required' => 'The :attribute field is required.',
            //指定自定义消息到特定的属性
            'email.required' => 'We need to know your e-mail address!',
        ];
        /*
         * 在语言文件中指定自定义的消息提示
        多数情况下，你会在语言文件中指定自定义的消息提示，而不是将定制的消息传递给 Validator 。
        实现它需要在语言文件 resources/lang/xx/validation.php 中，将定制的消息添加到 custom 数组。*/
        if(0){
            [
                'custom' => [
                    'email' => [
                        'required' => 'We need to know your e-mail address!',
                    ],
                ],
                /*
                    * 在语言文件中自定义属性
                   如果希望将验证消息的:attribute 部分替换为自定义属性名称，
                   则可以在 resources/lang/xx/validation.php 语言文件的 attributes 数组中指定自定义名称：*/
                'attributes' => [
                    'email' => 'email address',
                ],
            ];
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
            'email' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect('post/create')
                ->withErrors($validator)
                ->withInput();
        }

        $errors = $validator->errors();

        //查看特定字段的第一个错误消息
        //如果要查看特定字段的第一个错误消息，可以使用 first 方法：
        echo $errors->first('email');

        //查看特定字段的所有错误消息
        //如果你想通过指定字段来简单的获取所有消息中的一个数组，则可以使用 get 方法：
        foreach ($errors->get('email') as $message) {
            //
        }

        //如果你正在验证的一个表单字段类型是数组，你可以使用 * 来获取每个元素的所有错误信息：
        foreach ($errors->get('attachments.*') as $message) {
            //
        }

        //查看所有字段的所有错误消息
        //如果你想要得到所有字段的消息数组，则可以使用 all 方法：
        foreach ($errors->all() as $message) {
            //
        }

        //判断特定字段是否含有错误消息
        //可以使用 has 方法来检测一个给定的字段是否存在错误信息：
        if ($errors->has('email')) {
            //
        }
    }

    /**
     * 按条件增加规则
     */
    public function condition_validate()
    {
        /*
         * 当字段存在的时候进行验证
        在某些情况下，你可能 只想 在输入数据中有此字段时才进行验证。
        可通过增加 sometimes 规则到规则列表来实现*/
        $v = Validator::make(\request(), [
            'email' => 'sometimes|required|email',
        ]);

        /*
         * 复杂的条件验证
        有时候你可能希望增加更复杂的验证条件，
        例如，你可以希望某个指定字段在另一个字段的值超过 100 时才为必填。
        或者当某个指定字段有值时，另外两个字段要拥有符合的特定值。增加这样的验证条件并不难。
         * */
        $v = Validator::make(\request(), [
            'email' => 'required|email',
            'games' => 'required|numeric',
        ]);
        /*
         * 假设我们有一个专为游戏收藏家所设计的网页应用程序。
         * 如果游戏收藏家收藏超过一百款游戏，我们会希望他们来说明下为什么他们会拥有这么多游戏。
         * 比如说他们有可能经营了一家二手游戏商店，或者只是为了享受收集的乐趣。
         * 为了在特定条件下加入此验证需求，可以在 Validator 实例中使用 sometimes 方法*/
        $v->sometimes('reason', 'required|max:500', function ($input) {
            return $input->games >= 100;
        });
        /*
         * 传入 sometimes 方法的第一个参数是我们要用条件认证的字段名称。
         * 第二个参数是我们想使用的验证规则。
         * 闭包 作为第三个参数传入，如果其返回 true，则额外的规则就会被加入。
         * 这个方法可以轻松的创建复杂的条件式验证。你甚至可以一次对多个字段增加条件式验证：*/
        $v->sometimes(['reason', 'cost'], 'required', function ($input) {
            return $input->games >= 100;
        });
    }

    /**
     * 验证数组
     */
    public function validate_array()
    {
        /*
         * 验证基于数组的表单输入字段并不一定是一件痛苦的事情。
         * 要验证指定数组输入字段中的每一个 email 是否唯一，可以这么做：*/
        $validator = Validator::make(\request(), [
            'person.*.email' => 'email|unique:users',
            'person.*.first_name' => 'required_with:person.*.last_name',
        ]);
        /*
         * 同理，你在语言文件定义验证信息的时候可以使用星号 * 字符，可以更加容易的在基于数组格式的字段中使用相同的验证信息*/
        if(0){
            [
                'custom' => [
                    'person.*.email' => [
                        'unique' => 'Each person must have a unique e-mail address',
                    ]
                ],
            ];
        }
    }

    /**
     * 自定义验证规则
     */
    public function self_validate()
    {
        /*
         * Laravel 提供了许多有用的验证规则。但你可能想自定义一些规则。注册自定义验证规则的方法之一，
         * 就是使用 Validator Facade 中的 extend 方法，让我们在 服务提供者 中使用这个方法来注册自定义的验证规则
         * AppServiceProvider boot 方法中注册 */

        /*自定义错误消息
        另外你可能还需要为自定义规则来定义一个错误消息。这可以通过使用自定义内联消息数组或是在验证语言包中加入新的规则来实现。此消息应该被放在数组的第一级，而不是被放在 custom 数组内，这是仅针对特定属性的错误消息:

        "foo" => "你的输入是无效的!",
        "accepted" => ":attribute 必须被接受。",*/

        /*隐式扩展功能默认情况下，若有一个类似 required 这样的规则，
        当此规则被验证的属性不存在或包含空值时，其一般的验证规则（包括自定扩展功能）都将不会被运行。
        例如，当 integer 规则的值为 null 时 unique 将不会被运行：*/
        $rules = ['name' => 'unique'];

        $input = ['name' => null];

        Validator::make($input, $rules)->passes(); // true

        /*
         * 如果要在属性为空时依然运行此规则，则此规则必须暗示该属性为必填。
         * 要创建一个「隐式」扩展功能，可以使用 Validator::extendImplicit() 方法*/
        if(0){
            Validator::extendImplicit('foo', function ($attribute, $value, $parameters, $validator) {
                return $value == 'foo';
            });
        }
    }

}
?>