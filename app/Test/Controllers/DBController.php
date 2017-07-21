<?php
namespace App\Test\Controllers;
use Illuminate\Support\Facades\DB;


/**
 * DB 操作数据库
 * Class SignleController
 * @package App\Test\Controllers
 */
class DBController extends Controller
{
    /**
     * 配置好数据库连接以后，你可以使用 DB facade 来执行查询。
     * DB facade 提供了 select 、 update 、 insert 、 delete 和 statement 的查询方法。
     */
    public function native_sql()
    {
        /*
         * 传递到 select 方法的第一个参数是一个原生的 SQL 查询，
         * 而第二个参数则是传递的所有绑定到查询中的参数值。
         * 通常，这些都是 where 字句约束中的值。参数绑定可以避免 SQL 注入攻击。
        select 方法以数组的形式返回结果集，数组中的每一个结果都是一个PHP StdClass 对象*/
        $users = DB::select('select * from users where id = ?', [1]);
        dump($users);

        //使用命名绑定
        $users = DB::select('select * from users where id = :id', ['id' => 1]);
        dump($users);

        //运行 insert
        //DB::insert('insert into users (id, name, email, password) values (?, ?, ?, ?)', [10, 'Dayle', 'qq@.com', bcrypt(123456)]);
        $users = DB::select('select * from users where id = :id', ['id' => 10]);
        dump($users);

        //运行 Update  update 方法用于更新已经存在于数据库的记录。该方法会返回此语句执行所影响的行数：
        $affected = DB::update('update users set name = "chen" where name = ?', ['Dayle']);
        $users = DB::select('select * from users where id = :id', ['id' => 10]);
        dump($users);

        //运行 Delete  delete 方法用于删除已经存在于数据库的记录。如同 update 一样，删除的行数将会被返回。
        $deleted = DB::delete('delete from users WHERE  name = ?', ['chen']);

        //运行一般声明  有些数据库没有返回值， 对于这种类型的操作，可以使用 DB facade 的 statement 方法
        // DB::statement('drop table users');
    }

    /**
     * 数据库事务
     * 想要在一个数据库事务中运行一连串操作，可以使用 DB facade 的 transaction 方法。
     * 如果在事务的 Closure 中抛出了异常，那么事务会自动的执行回滚操作。
     * 如果 Closure 成功的执行，那么事务就会自动的进行提交操作。
     * 你不需要在使用 transaction 方法时考虑手动执行回滚或者提交操作：
     */
    public function transaction ()
    {
        //如果在事务的 Closure 中抛出了异常，那么事务会自动的执行回滚操作
        DB::transaction(function () {
            DB::table('users')->update(['votes' => 1]);

            DB::table('posts')->delete();
        });

        //手动操作事务 如果你想要手动开始一个事务的回滚和提交操作，你可以使用 DB facade 的 beginTransaction 方法。
        DB::beginTransaction();
        //可以通过 rollBack 方法来回滚事务
        DB::rollBack();
        //可以通过 commit 方法来提交这个事务
        DB::commit();
    }

    /**
     * 查询构造器操作 数据库
     */
    public function db_base()
    {
        if(0) {
            /*
             * get ⽅法会返回⼀个 Illuminate\Support\Collection 结果，其中每个
            结果都是⼀个 PHP StdClass 对象的实例*/
            $users = DB::table('users')->get();

            /*从数据表中获取⼀⾏数据，则可以使⽤ first ⽅法*/
            $user = DB::table('users')->where('id', 1)->first();

            /*value ⽅法来从单条记录中取出单个值。*/
            $users = DB::table('users')->where('id', 1)->value('name');

            /*获取⼀个包含单个字段值的集合，可以使⽤ pluck ⽅法*/
            $users = DB::table('users')->pluck('name');

            /*email => name*/
            $users = DB::table('users')->pluck('name', 'email');

            /*如果你需要操作数千条数据库记录，可以考虑使⽤ chunk ⽅法。这个⽅法
            每次只取出⼀⼩块结果，并会将每个块传递给⼀个 闭包 处理  前面必须用 orderBy 排序*/
            $users = DB::table('users')->orderBy('id')->chunk(100, function ($user){
                // dump($user);
            });

            /*聚合函数*/
            $users = DB::table('users')->count();
            $users = DB::table('users')->max('id');
            $users = DB::table('users')->avg('id');
            $users = DB::table('users')->min('id');

            /*获取部分字段 此select 非 彼 select*/
            $users = DB::table('users')->select('name', 'email as user_email')->get();

            /*distinct ⽅法允许你强制让查询返回不重复的结果*/
            $users = DB::table('users')->distinct()->get();

            /*如果你已有⼀个查询构造器实例，并且希望在现有的 select ⼦句中加⼊⼀个
            字段，则可以使⽤ addSelect ⽅法*/
            $users = DB::table('users')->select('name', 'email as user_email');
            $users->addSelect('id')->get();

            /*
             * 有时候你可能需要在查询中使⽤原始表达式。这些表达式将会被当作字符串
            注⼊到查询中，所以要⼩⼼避免造成 SQL 注⼊攻击！要创建⼀个原始表达
            式，可以使⽤ DB::raw ⽅法*/
            $users = DB::table('users')->select(DB::raw('count(*) as user_count, id'))->groupBy('id')->get();

            /*
             * 查询构造器也可以编写 join 语法。若要执⾏基本的「inner join」，你可以在
            查询构造器实例上使⽤ join ⽅法。传递给 join ⽅法的第⼀个参数是你
            要 join 数据表的名称，⽽其它参数则指定⽤来连接的字段约束。当然，如你
            所⻅，你可以在单个查找中连接多个数据表：
            */
            $users = DB::table('users')
                ->join('posts', 'users.id', '=', 'posts.user_id')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->select('users.*', 'posts.title', 'orders.number')
                ->get();
            $users = DB::table('posts')
                ->join('orders', 'posts.user_id', '=', 'orders.user_id')
                ->select('orders.*', 'posts.title', 'orders.number')
                ->get();

            /*leftJoin*/
            $users = DB::table('users')
                ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
                ->get();

            /*crossJoin*/
            $users = DB::table('users')
                ->crossJoin('posts')
                ->get();

            /*⾼级的 join ⼦句。让我们传递⼀个 闭包 作为 join ⽅法的
            第⼆个参数来作为开始。此 闭包 将会收到⼀个 JoinClause 对象，让你可
            以在 join ⼦句中指定约束*/
            $users = DB::table('users')
                ->join('posts', function ($join) {
                    $join->on('users.id', '=', 'posts.user_id')->orOn('users.id', '=', 'posts.id');
                })
                ->get();

            /*在连接中使⽤「where」⻛格的⼦句，则可以在连接中使⽤
            where 和 orWhere ⽅法。这些⽅法将会⽐较值和对应的字段，⽽不是⽐
            较两个字段：
            */
            $users = DB::table('users')
                ->join('posts', function ($join) {
                    $join->on('users.id', '=', 'posts.user_id')
                        ->where('posts.user_id', '<', 2000);
                })
                ->get();

            /*
             * 查询构造器也提供了⼀个快捷的⽅法来「合并」 两个查询。例如，你可以先
            创建⼀个初始查询，并使⽤ union ⽅法将它与第⼆个查询进⾏合并*/
            $first = DB::table('users')
                ->where('id', 1);
            $users = DB::table('users')
                ->where('id', 2)
                ->union($first)
                ->get();

            /*where 句子*/
            $users = DB::table('users')
                ->where('name', 'like', 'T%')
                ->get();
            $users = DB::table('users')
                ->where('id', '>=', 100)
                ->get();
            $users = DB::table('users')
                ->where('id', '<>', 100)
                ->get();
            //还可以使用数组
            $users = DB::table('users')->where([
                ['id', '>', '1'],
                ['name', 'like', 'T%'],
            ])->get();
            //or 条件
            $users = DB::table('users')
                ->where('id', '>', 100)
                ->orWhere('name', 'like', 'T%')
                ->get();

            //whereBetween  whereNotBetween
            $users = DB::table('users')
                ->whereBetween('id', [1, 100])->get();
            $users = DB::table('users')
                ->whereNotBetween('id', [1, 1000])
                ->get();

            //whereIn whereNotIn
            $users = DB::table('users')
                ->whereIn('id', [1, 2, 3])
                ->get();
            $users = DB::table('users')
                ->whereNotIn('id', [1, 2, 3])
                ->get();

            //whereNull whereNotNull
            $users = DB::table('users')
                ->whereNull('updated_at')
                ->get();
            $users = DB::table('users')
                ->whereNotNull('updated_at')
                ->get();

            /*whereDate ⽅法⽐较某字段的值与指定的⽇期是否相等*/
            $users = DB::table('users')
                ->whereDate('created_at', '2017-7-09')
                ->get();
            // whereMonth ⽅法⽐较某字段的值是否与⼀年的某⼀个⽉份相等：
            $users = DB::table('users')
                ->whereMonth('created_at', '12')
                ->get();
            //whereDay ⽅法⽐较某列的值是否与⼀⽉中的某⼀天相等：
            $users = DB::table('users')
                ->whereDay('created_at', '31')
                ->get();
            //whereYear ⽅法⽐较某列的值是否与指定的年份相等：
            $users = DB::table('users')
                ->whereYear('created_at', '2016')
                ->get();

            /*whereColumn ⽅法⽤来检测两个列的数据是否⼀致*/
            $users = DB::table('users')
                ->whereColumn('updated_at', '>', 'created_at'
                )
                ->get();
            /*whereColumn ⽅法可以接收数组参数。条件语句会使⽤ and 连接起来*/
            $users = DB::table('users')
                ->whereColumn([
                    ['name', '<>', 'email'],
                    ['updated_at', '<>', 'created_at']
                ])->get();

            /*条件分组  利用闭包实现条件分组
            传递⼀个 闭包 到 orWhere ⽅法，告诉查询构造器
            开始⼀个约束分组*/
            $users = DB::table('users')
                ->where('name', 'like', 'J%')   //条件一
                ->orWhere(function ($query) {   //条件二
                    $query->where('id', '>', 100)
                        ->where('name', '<>', 'Admin');
                })
                ->get();

            /*whereExists ⽅法允许你编写 where exists SQL ⼦句。此⽅法会接收
            ⼀个 闭包 参数，此闭包接收⼀个查询语句构造器实例，让你可以定义应放
            在「exists」SQL ⼦句中的查找*/
            /*对应
             * select * from users
            where exists (
            select 1 from orders where orders.user_id = users.id
            )*/
            $users = DB::table('users')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('orders')
                        ->whereRaw('orders.user_id = users.id');
                })
                ->get();

            /*Laravel 也⽀持查询 JSON 类型的字段。⽬前，本特性仅⽀持 MySQL 5.7+
            和 Postgres数据库。可以使⽤ -> 运算符来查询 JSON 列数据*/
            $users = DB::table('users')
                ->where('options->language', 'en')
                ->get();
            $users = DB::table('users')
                ->where('preferences->dining->meal', 'salad')
                ->get();

            /*orderBy*/
            $users = DB::table('users')
                ->orderBy('name', 'desc')
                ->get();
            /*limit*/
            $users = DB::table('users')
                ->limit(10)
                ->get();

            /*latest 和 oldest ⽅法允许你更容易的依据⽇期对查询结果排序。默认
            查询结果将依据 created_at 列。或者,你可以使⽤字段名称排序*/
            $users = DB::table('users')
                ->latest()
                ->first();
            $users = DB::table('users')
                ->oldest()
                ->first();

            /*inRandomOrder ⽅法可以将查询结果随机排序。例如，你可以使⽤这个⽅
            法获取⼀个随机⽤户*/
            $users = DB::table('users')
                ->inRandomOrder()
                ->first();

            /*groupBy 和 having ⽅法可⽤来对查询结果进⾏分组。 having ⽅法的
            ⽤法和 where ⽅法类似*/
            $users = DB::table('users')
                ->select('id')
                ->groupBy('id')
                ->having('id', '>', 100)
                ->get();
            /*havingRaw ⽅法可以将⼀个原始的表达式设置为 having ⼦句的值*/
            $users = DB::table('users')
                ->select(DB::raw('SUM(id) as total_sales'))
                ->groupBy('id')
                ->havingRaw('SUM(id) > 2500')
                ->get();

            /*使⽤ skip 和 take ⽅法来限制查询结果数量或略过指定数量的查询：*/
            $users = DB::table('users')->skip(10)->take(5)->get();
            /*offset limit*/
            $users = DB::table('users')
                ->offset(10)
                ->limit(5)
                ->get();

            /*条件查询
            只有当 when ⽅法的第⼀个参数为 true 时，闭包⾥的 where 语句才会
            执⾏。如果第⼀个参数是 false ，这个闭包将不会被执⾏*/
            $role = 1;
            $users = DB::table('users')
                ->when($role, function ($query) use ($role) {
                    return $query->where('id', 2);
                })
                ->get();
            /*第⼀个参数的值为 false 时，这个闭包将执⾏*/
            $users = DB::table('users')
                ->when($role, function ($query) use ($role) {
                    return $query->where('id', 2);
                }, function ($query) use ($role) {
                    return $query->where('id', 3);
                })
                ->get();

            /*查询构造器也提供了 insert ⽅法，⽤来插⼊记录到数据表中。 insert⽅法接收⼀个包含字段名和值的数组作为参数*/
            DB::table('users')->insert(
                ['email' => 'john@example.com', 'name' => 'chenchen', 'password' => bcrypt(123465)]
            );
            DB::table('users')->insert(
                ['email' => 'john@example.com', 'name' => 'chenchen', 'password' => bcrypt(123465)],
                ['email' => 'john@example.com', 'name' => 'chenchen', 'password' => bcrypt(123465)]
            );
            /*若数据表存在⾃增 id，则可以使⽤ insertGetId ⽅法来插⼊记录并获取其ID*/
            $users = DB::table('users')->insertGetId(
                ['email' => 'johnnn@example.com', 'name' => 'chenchenchen', 'password' => bcrypt(123465)]
            );
            /*update ⽅法和 insert ⽅法⼀样，接收含有字段及值的数组，
            其中包括要更新的字段。可以使⽤ where ⼦句来约束 update 查找*/
            $users = DB::table('users')
                ->where('id', 1)
                ->update(['name' => 888]);

            DB::table('users')
                ->where('id', 1)
                ->update(['options->enabled' => true]);

            /*查询构造器也为指定字段提供了便利的⾃增和⾃减⽅法 。此⽅法提供了⼀个
            ⽐⼿动编写 update 语法更具表达⼒且更精练的接⼝。
            这两个⽅法都必须接收⾄少⼀个参数（要修改的字段）。也可选择传⼊第⼆
            个参数，⽤来控制字段应递增／递减的量*/
            //update `users` set `id` = `id` + 10
            DB::table('users')->increment('id');
            DB::table('users')->increment('id', 5);
            DB::table('users')->decrement('id');
            DB::table('users')->decrement('id', 5);
            //还可以指定要操作中更新其它字段：
            DB::table('users')->increment('votes', 1, ['name' => 'John']);

            /*查询构造器也可使⽤ delete ⽅法从数据表中删除记录。在 delete 前，
            还可使⽤ where ⼦句来约束 delete 语法*/
            DB::table('users')->delete();
            DB::table('users')->where('id', '>', 100)->delete();
            /*如果你需要清空表，你可以使⽤ truncate ⽅法，这将删除所有⾏，并重置⾃动递增 ID 为零*/
            DB::table('users')->truncate();

            /*查询构造器也包含⼀些可以帮助你在 select 语法上实现「悲观锁定」的
            函数 。若要在查询中使⽤「共享锁」，可以使⽤ sharedLock ⽅法。共享
            锁可防⽌选中的数据列被篡改，直到事务被提交为⽌*/
            $users = DB::table('users')->where('id', '>', 100)->sharedLock()->get();
            //可以使⽤ lockForUpdate ⽅法。使⽤「更新」锁可避免⾏被其它共享锁修改或选取
            $users = DB::table('users')->where('id', '>', 100)->lockForUpdate()->get();
        }else{
            $users = null;
        }
        dump($users);
    }

    /**
     * 最简单的是在 查询语句构造器 或
     * Eloquent 查询 中使⽤ paginate ⽅法。 paginate ⽅法会⾃动基于当前
     * ⽤户查看的当前⻚⾯来设置适当的限制和偏移。默认情况下，当前⻚⾯通过
     * HTTP 请求所带的 ?page 参数的值来检测。当然，这个值会被 Laravel ⾃
     * 动检测，并且⾃动插⼊到由分⻚器⽣产的链接中
     */
    public function paginate()
    {
        if(0){
            $users = DB::table('users')->paginate(15);

        }elseif (0){
            /*
             * 如果你只需要在你的分⻚视图中显示简单的「上⼀⻚」和「下⼀⻚」的链
            接，你可以使⽤ simplePaginate ⽅法来执⾏更⾼效的查询。当你在渲染
            视图时不需要显示⻚码链接，这对于⼤数据集⾮常有⽤。
            */
            $users = DB::table('users')->simplePaginate(15);
        }elseif (0){
            /*
             * 可以对 Eloquent 查询进⾏分⻚。在这个例⼦中，我们将对 User 模型
            进⾏分⻚并且每⻚显示 15 条数据。正如你看到的，语法⼏乎与基于查询语
            句构造器的分⻚相同*/
            $users = User::where('id', '>', 100)->paginate(15);
        }elseif (0){
            $users = User::where('id', '>', 100)->simplePaginate(15);
        }elseif (1){
            /*
             * withPath ⽅法允许你在⽣成分⻚链接时⾃定义 URI 。例如，如果你想分
            ⻚器⽣成的链接如 http://example.com/custom/url?page=N ，你应该传
            递 custom/url 到 withPath ⽅法*/
            $users = User::where('id', '>', 100)->paginate(15);
            $users->withPath('mysql/paginate');
        }elseif (1){

        }
        dump($users);
        return view('paginate', compact('users'));

        /*
         * ⼿动创建分⻚
         * 有时候你可能希望⼿动创建⼀个分⻚实例，并传递其到项⽬数组中。你可以
            依据你的需求创建 Illuminate\Pagination\Paginator 或
            Illuminate\Pagination\LengthAwarePaginator 实例。
            Paginator 类不需要知道结果集中的数据项总数；然⽽，由于这个，该类
            没有⽤于检索最后⼀⻚索引的⽅法。 LengthAwarePaginator 接收的参数⼏
            乎和 Paginator ⼀样；但是，它需要计算结果集中的数据项总数。
            换⼀种说法， Paginator 对应于查询语句构造器和 Eloquent 的
            simplePaginate ⽅法，⽽ LengthAwarePaginator 对应于 paginate
            ⽅法。
        Paginator -> simplePaginate   LengthAwarePaginator -> paginate
        */

        /*⾃定义分⻚视图最简单的⽅法是通过 vendor:publish 命令将它们
        导出到你的 resources/views/vendor ⽬录
        php artisan vendor:publish --tag=laravel-pagination

        这个命令将视图放置在 resources/views/vendor/pagination ⽬录中。这
        个⽬录下的 default.blade.php ⽂件对应于默认分⻚视图。你可以简单地
        编辑这个⽂件以修改分⻚的 HTML 。
         * */
    }
}
?>