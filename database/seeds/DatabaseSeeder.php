<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*在 DatabaseSeeder 类中，你可以使用 call 方法来运行其他的 seed 类。为避免单个 seeder 类过大，
        可使用 call 方法将数据填充拆分成多个文件。只需简单传递要运行的 seeder 类名称即可*/
        // $this->call(UsersTableSeeder::class);
        $this->call(LaratrustSeeder::class);


        //可以调用数据工厂
       /* factory(App\User::class, 50)->create()->each(function ($u) {
            $u->posts()->save(factory(App\Post::class)->make());
        });*/
    }
}
