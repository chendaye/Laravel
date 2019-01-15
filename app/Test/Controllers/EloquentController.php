<?php
namespace App\Test\Controllers;
use App\Post;
use App\ProductsInstockShipping;
use App\Scopes\TestScope;
use App\User;

/**
 * Eloquent
 * Class EloquentController
 * @package App\Test\Controllers
 *
 * Laravel 的 Eloquent ORM 提供了漂亮、简洁的 ActiveRecord 实现来和数据库进行交互。
 * 每个数据库表都有一个对应的「模型」可用来跟数据表进行交互。
 * 你可以通过模型查询数据表内的数据，以及将记录添加到数据表中。
 */
class EloquentController extends Controller
{

    /*
     * 模型通常放在 app 目录中，不过你可以将他们随意放在任何可通过 composer.json 自动加载的地方。
     * 所有的 Eloquent 模型都继承自 Illuminate\Database\Eloquent\Model 类。
     * php artisan make:model User
     * 当你生成一个模型时想要顺便生成一个 数据库迁移，可以使用 --migration 或 -m 选项
     * php artisan make:model User --migration
     * php artisan make:model User -m*/

    /*
     * 数据表名称
    请注意，我们并没有告诉 Eloquent Flight 模型该使用哪一个数据表。
    除非数据表明确地指定了其它名称，否则将使用类的「蛇形名称」、复数形式名称来作为数据表的名称。
    因此在此例子中，Eloquent 将会假设 Flight 模型被存储记录在 flights 数据表中。
    你可以在模型上定义一个 table 属性，用来指定自定义的数据表名称

    protected $table = 'my_flights';
    */

    /*
     * 主键
     * Eloquent 也会假设每个数据表都有一个叫做 id 的主键字段。你也可以定义一个 $primaryKey 属性来重写这个约定
     * Eloquent 假定主键是一个递增的整数值，这意味着在默认情况下主键将自动的被强制转换为 int。
     * 如果你想使用非递增或者非数字的主键，你必须在你的模型 public $incrementing 属性设置为fals
     * protected $primaryKey = 'products_instock_id';*/

    /*
     * 时间戳
     * 默认情况下，Eloquent 会认为在你的数据库表有 created_at 和 updated_at 字段。
     * 如果你不希望让 Eloquent 来自动维护这两个字段，可在模型内将 $timestamps 属性设置为 false
     *  public $timestamps = false;*/

    /*
     * 如果你需要自定义自己的时间戳格式，可在模型内设置 $dateFormat 属性。
     * 这个属性决定了日期应如何在数据库中存储，以及当模型被序列化成数组或 JSON 格式
     *  protected $dateFormat = 'U';*/

    /*
     * 数据库连接
     * 默认情况下，所有的 Eloquent 模型会使用应用程序中默认的数据库连接设置。
     * 如果你想为模型指定不同的连接，可以使用 $connection 属性
     *  protected $connection = 'connection-name';*/

    /*
     * 一旦你创建并 关联了一个模型到数据表 上，那么你就可以从数据库中获取数据。
     * 可把每个 Eloquent 模型想像成强大的 查询构造器，它让你可以流畅地查询与模型关联的数据表*/


    /**
     * 通过模型取数据
     */
    public function eloquent_get()
    {
        if(0){
            $users = User::all();

            //Eloquent 的 all 方法会返回在模型数据表中的所有结果。
            //由于每个 Eloquent 模型都可以当作一个 查询构造器，所以你可以在查询中增加规则，然后使用 get 方法来获取结果
            $users = User::where('id','>', 600)
                ->orderBy('name', 'desc')
                ->take(10)
                ->get();
        }

        if(0){
            /*
           * 集合
           * 类似 all 以及 get 之类的可以取回多个结果的 Eloquent 方法，
           * 将会返回一个 Illuminate\Database\Eloquent\Collection 实例。
           * Collection 类提供 多种辅助函数 来处理你的 Eloquent 结果*/
            $users = User::all();
            $users = $users->reject(function ($user) {
                return $user->name;
            });
        }

        if(0){
            /*
           * 分块结果
           * 如果你需要处理数以千计的 Eloquent 查找结果，则可以使用 chunk 命令。
           * chunk 方法将会获取一个 Eloquent 模型的「分块」，并将它们送到指定的 闭包 (Closure) 中进行处理。
           * 当你在处理大量结果时，使用 chunk 方法可节省内存
            * 传递到方法的第一个参数表示每次「分块」时你希望接收的数据数量。
            * 闭包则作为第二个参数传递，它将会在每次从数据取出分块时被调用*/
            User::chunk(200, function ($users) {
                dump($users);
            });
        }

        if(0){
            /*
           * 使用游标
           * cursor 允许你使用游标来遍历数据库数据，一次只执行单个查询。
           * 在处理大数据量请求时 cursor 方法可以大幅度减少内存的使用*/
            foreach (User::where('id','>', 500)->cursor() as $user) {
                dump($user);
            }
        }

        if(0){
            /*
           * 取回单个模型／集合当然，
           * 除了从指定的数据表取回所有记录，你也可以通过 find 和 first 方法来取回单条记录。
           * 但这些方法返回的是单个模型的实例，而不是返回模型的集合：*/
            // 通过主键取回一个模型...
            $users = User::find(1);
            // 取回符合查询限制的第一个模型 ...
            $users = User::where('id','>', 1)->first();
            //你也可以用主键的集合为参数调用find方法，它将返回符合条件的集合：
            $users = User::find([1, 2, 3]);

        }


        if(0){
            /*
         * 有时候你可能希望在找不到模型时抛出一个异常，这在路由或是控制器内特别有用。
         * findOrFail 以及 firstOrFail 方法会取回查询的第一个结果。如果没有找到相应结果，
         * 则会抛出一个 Illuminate\Database\Eloquent\ModelNotFoundException*/
            $users = User::findOrFail(9999999);
            $users = User::where('id', '>', 100)->firstOrFail();
        }

        if(0){
            /*
        * 取回集合
        * 当然，你也可以使用 count、sum、max，和其它 查询构造器 提供的 聚合函数。
        * 这些方法会返回适当的标量值，而不是一个完整的模型实例：*/
            $users = User::where('id', 1)->count();
            $users = User::where('id', '>',100)->max('id');
        }
    }

    /**
     * 获取记录
     */
    public function eloquent_save()
    {
        if(0){
            /*
             * 基本添加
             * 要在数据库中创建一条新记录，只需创建一个新模型实例，并在模型上设置属性和调用 save 方法即可
             * 当我们调用 save 方法，就会添加一条记录到数据库中。
             * 当 save 方法被调用时，created_at 以及 updated_at 时间戳将会被自动设置，因此我们不需要去手动设置它们*/
            $users = new User();
            $users->name = '777';
            $users->save();
        }


        if(0){

            /*
            * save 方法也可以用于更新数据库中已经存在的模型。
            * 要更新模型，则须先取回模型，再设置任何你希望更新的属性，接着调用 save 方法。
            * 同样的，updated_at 时间戳将会被自动更新，所以我们不需要手动设置它的值*/
            $users = User::find(100);
            $users->name = '777';
            $users->save();
        }

        if(0){
            /*
         * 批量更新
         * 也可以针对符合指定查询的任意数量模型进行更新。
         * */
            User::where('id', '>', 1000)->where('id','<',1004)->update(['name' => 777]);
            $user = User::where('id', '>', 1000)->where('id','<',1004)->get();
            dump($user);
        }

        if(0){
            /*
        * 批量赋值
        * 你也可以使用 create 方法通过一行代码来保存一个新模型。被插入数据库的模型实例将会返回给你。
        * 不过，在这样做之前，你需要先在你的模型上定义一个 fillable 或 guarded 属性，
        * 因为所有的 Eloquent 模型都针对批量赋值（Mass-Assignment）做了保护。
        *
        * 当用户通过 HTTP 请求传入了非预期的参数，并借助这些参数更改了数据库中你并不打算要更改的字段，
        * 这时就会出现批量赋值（Mass-Assignment）漏洞。例如，恶意用户可能会通过 HTTP 请求发送 is_admin 参数，
        * 然后对应到你模型的 create 方法，此操作能让该用户把自己升级为一个管理者。
        *
        * 开始之前，你应该定义好哪些模型属性是可以被批量赋值的。
        * 你可以在模型上使用 $fillable 属性来实现。例如，让我们让 Flight 模型的 name 属性可以被批量赋值
        *
        * 一旦我们已经设置好可以被批量赋值的属性，便能通过 create 方法来添加一条新记录到数据库。
        * create 方法将返回已经被保存的模型实例*/
            $users = ProductsInstockShipping::create(['orders_id' => 77777, 'shipping_price' => 666, 'order_number' => 6666, 'order_invoice' => 777, 'order_po' => 7777]);
            dump($users);

            //如果你已经有一个 model 实例，你可以使用一个数组传递给 fill 方法：
            $post = new Post();
            $post->fill(['content' => 'Flight 22','title' => 666]);
            dd($post);
        }

        if(0){
            /*
           * Guarding Attributes
           * $fillable 作为一个可以被批量赋值的属性「白名单」。
           * 另外你也可以选择使用 $guarded。$guarded 属性应该包含一个你不想要被批量赋值的属性数组。
           * 所有不在数组里面的其它属性都可以被批量赋值。因此，$guarded 的功能更类似一个「黑名单」。
           * 使用的时候应该只选择 $fillable 或 $guarded 中的其中一个。*/

            /*
            * 其它创建的方法
            * 还有两种其它方法，你可以用来通过属性批量赋值创建你的模型：
            * firstOrCreate 和 firstOrNew。firstOrCreate 方法将会使用指定的字段／值对，来尝试寻找数据库中的记录。
            * 如果在数据库中找不到模型，则会使用指定的属性来添加一条记录。
            * firstOrNew 方法类似 firstOrCreate 方法，
            * 它会尝试使用指定的属性在数据库中寻找符合的纪录。
            * 如果模型未被找到，将会返回一个新的模型实例。请注意 firstOrnew 返回的模型还尚未保存到数据库。
            * 你需要通过手动调用 save 方法来保存它*/

            //没有就创建 有就不创建
            $post = Post::firstOrCreate(['content' => 'Flight 22','title' => 666]);
            // 同上 但是要手动存入数据库
            $post = Post::firstOrNew(['content' => 'Flight 22','title' => 777]);
            $post->save();
            dd($post);
        }
    }

    /**
     * 删除模型
     */
    public function eloquent_del(){
        if(0){
            /*要删除模型，必须在模型实例上调用 delete 方法*/
            $post = Post::find(10);
            $post->delete();

            /*
            * 通过键来删除现有的模型
            * 在上面的例子中，我们在调用 delete 方法之前会先从数据库中取回模型。
            * 不过，如果你已知道了模型中的主键，则可以不用取回模型就能直接删除它。
            * 若要直接删除，请调用 destroy 方法*/
            Post::destroy([128,126]);

            /*通过查询来删除模型
            当然，你也可以运行在一组模型删除查询。
            在这个例子中，我们会删除被标记为不活跃的所有航班。
            像批量更新那样，批量删除不会删除的任何被删除的模型的事件*/
            Post::where('id', 126)->delete();
        }

        if(0){
            /*
            * 软删除
            * 除了从数据库中移除实际记录，Eloquent 也可以「软删除」模型。当模型被软删除时，
            * 它们并不会真的从数据库中被移除。而是会在模型上设置一个 deleted_at 属性并将其添加到数据库。
            * 如果模型有一个非空值 deleted_at，代表模型已经被软删除了。
            * 要在模型上启动软删除，则必须在模型上使用 Illuminate\Database\Eloquent\SoftDeletes trait
            * 并添加 deleted_at 字段到你的 $dates 属性上*/
//            class Flight extends Model
//            {
//                use SoftDeletes;
//
//                /**
//                 * 需要被转换成日期的属性。
//                 *
//                 * @var array
//                 */
//                protected $dates = ['deleted_at'];
//            }

            /*
             * 当然，你也应该添加 deleted_at 字段到数据表中。Laravel 结构生成器 包含了一个用来创建此字段的辅助函数*/
            Schema::table('flights', function ($table) {
                $table->softDeletes();
            });
        }

        if(0){
            /*
           * 现在，当你在模型上调用 delete 方法时，deleted_at 字段将会被设置成目前的日期和时间。
           * 而且，当查询有启用软删除的模型时，被软删除的模型将会自动从所有查询结果中排除。
           * 要确认指定的模型实例是否已经被软删除，可以使用 trashed 方法*/
            $flight->trashed();
        }

        if(0){
            /*
            * 查询被软删除的模型
            * 包含被软删除的模型
            * 如上所述，被软删除的模型将会自动从所有的查询结果中排除。
            * 不过，你可以通过在查询中调用 withTrashed 方法来强制查询已被软删除的模型：*/
            $flights = App\Flight::withTrashed()
                ->where('account_id', 1)
                ->get();
            /*withTrashed 方法也可以被用在 关联 查询*/
            $flight->history()->withTrashed()->get();
            /*只取出软删除数据onlyTrashed 会只取出软删除数据*/
            $flights = App\Flight::onlyTrashed()
                ->where('airline_id', 1)
                ->get();
        }

        if(0){
            /*
           * 恢复被软删除的模型
           * 有时候你可能希望「取消删除」一个已被软删除的模型。
           * 要恢复一个已被软删除的模型到有效状态，则可在模型实例上使用 restore 方法：*/
            $flight->restore();
            /*
             * 可以在查询上使用 restore 方法来快速地恢复多个模型：*/
            App\Flight::withTrashed()
                ->where('airline_id', 1)
                ->restore();
            /*与 withTrashed 方法类似，restore 方法也可以被用在 关联 查询上*/
            $flight->history()->restore();
        }

        if(0){
            /*
            * 永久地删除模型
            * 有时候你可能需要真正地从数据库移除模型。要永久地从数据库移除一个已被软删除的模型，则可使用 forceDelete 方法*/
            // 强制删除单个模型实例...
            $flight->forceDelete();
            // 强制删除所有相关模型...
            $flight->history()->forceDelete();
        }
    }

    /**
     * 全局作用域
     * 全局作用域允许我们为给定模型的所有查询添加条件约束。L
     * aravel 自带的 软删除功能 就使用了全局作用域来从数据库中拉出所有没有被删除的模型。
     * 编写自定义的全局作用域可以提供一种方便的、简单的方式，来确保给定模型的每个查询都有特定的条件约束
     */
    public function scope()
    {
        if(0){
            /*编写全局作用域
            自定义全局作用域很简单，首先定义一个实现 Illuminate\Database\Eloquent\Scope 接口的类，
            该接口要求你实现一个方法：apply。需要的话可以在 apply 方法中添加 where 条件到查询*/
            $p = ProductsInstockShipping::all();
            //移除全局范围
            $p = ProductsInstockShipping::withoutGlobalScope('age')->get();
            $p = ProductsInstockShipping::withoutGlobalScope(TestScope::class)->get();

            //如果你想要移除某几个或全部全局作用域，可以使用 withoutGlobalScopes 方法
            $p = ProductsInstockShipping::withoutGlobalScopes()->get();

            $p = ProductsInstockShipping::withoutGlobalScopes([TestScope::class])->get();
            dd($p);
        }

        if(1){
            /*
             * 本地作用域*/
            $p = ProductsInstockShipping::withoutGlobalScopes([TestScope::class, 'age'])->id()->get();
            //动态范围
            $p = ProductsInstockShipping::withoutGlobalScopes([TestScope::class, 'age'])->dynamic(99702)->get();
            dd($p);
        }
    }

    /**
     * 模型事件
     */
    public function event()
    {
        if(1){
            $shipping = ProductsInstockShipping::withoutGlobalScopes()->find(360);
            $shipping->orders_id = 7777;
            /*
             * 触发事件
             * "saving"
                "creating"
                "updated"
                "saved"*/
            $shipping->save();
        }
    }

}
?>