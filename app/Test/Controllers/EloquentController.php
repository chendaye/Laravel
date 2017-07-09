<?php
namespace App\Test\Controllers;
use App\Post;
use App\ProductsInstockShipping;
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

            /*
             * 集合
             * 类似 all 以及 get 之类的可以取回多个结果的 Eloquent 方法，
             * 将会返回一个 Illuminate\Database\Eloquent\Collection 实例。
             * Collection 类提供 多种辅助函数 来处理你的 Eloquent 结果*/
            $users = User::all();
            $users = $users->reject(function ($user) {
                return $user->name;
            });

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

            /*
            * 使用游标
            * cursor 允许你使用游标来遍历数据库数据，一次只执行单个查询。
            * 在处理大数据量请求时 cursor 方法可以大幅度减少内存的使用*/
            foreach (User::where('id','>', 500)->cursor() as $user) {
                dump($user);
            }

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

            /*
           * 有时候你可能希望在找不到模型时抛出一个异常，这在路由或是控制器内特别有用。
           * findOrFail 以及 firstOrFail 方法会取回查询的第一个结果。如果没有找到相应结果，
           * 则会抛出一个 Illuminate\Database\Eloquent\ModelNotFoundException*/
            $users = User::findOrFail(9999999);
            $users = User::where('id', '>', 100)->firstOrFail();

            /*
          * 取回集合
          * 当然，你也可以使用 count、sum、max，和其它 查询构造器 提供的 聚合函数。
          * 这些方法会返回适当的标量值，而不是一个完整的模型实例：*/
            $users = User::where('id', 1)->count();
            $users = User::where('id', '>',100)->max('id');
        }elseif(1){

        }
        dump($users);
    }

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

            /*
            * save 方法也可以用于更新数据库中已经存在的模型。
            * 要更新模型，则须先取回模型，再设置任何你希望更新的属性，接着调用 save 方法。
            * 同样的，updated_at 时间戳将会被自动更新，所以我们不需要手动设置它的值*/
            $users = User::find(100);
            $users->name = '777';
            $users->save();

            /*
            * 批量更新
            * 也可以针对符合指定查询的任意数量模型进行更新。
            * */
            User::where('id', '>', 1000)->where('id','<',1004)->update(['name' => 777]);
            $user = User::where('id', '>', 1000)->where('id','<',1004)->get();
            dump($user);

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
        }else{





        }
    }

}
?>