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
}
?>