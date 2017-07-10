<?php
namespace App\Test\Controllers;


/**
 * 集合
 * Collection 类支持链式调用，一般来说，每一个 Collection 方法会返回一个全新的 Collection 实例，你可以放心地进行链接调用
 * Class CollectionController
 * @package App\Test\Controllers
 */
class CollectionController extends Controller
{
    public  $collection;
    public function __construct()
    {
        $this->collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
    }

    /**
     * 创建集合
     */
    public function createCollect()
    {
        dump($this->collection);
    }

    /**
     * 返回集合中所有项目的平均值
     */
    public function avg()
    {
        $collect = $this->collection->avg();

        //如果集合包含了嵌套数组或对象，你可以通过传递「键」来指定使用哪些值计算平均值：
        $collection = collect([
            ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
            ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
        ]);
        $collect = $collection->avg('pages');

        dump($collect);
    }

    /**
     * 将集合拆成多个指定大小的较小集合
     */
    public function chunk()
    {
        $chunks = $this->collection->chunk(4);
        $collect = $chunks->toArray();

//        @foreach ($products->chunk(3) as $chunk)
//        <div class="row">
//        @foreach ($chunk as $product)
//                <div class="col-xs-4">{{ $product->name }}</div>
//        @endforeach
//        </div>
//        @endforeach
        dump($collect);
    }

    /**
     * 将多个数组组成的集合合成单个一维数组集合
     */
    public function collapse()
    {
        $collection = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);

        $collapsed = $collection->collapse();

        $collect = $collapsed->all();

        dump($collect);
    }

    /**
     * 将集合的值作为「键」，合并另一个数组或者集合作为「键」对应的值
     */
    public function combine()
    {
        //键
        $collection = collect(['name', 'age']);
        //值
        $combined = $collection->combine(['George', 29]);

        $collect = $combined->all();

        dump($collect);
    }

    /**
     * 判断集合是否含有指定项目
     * 判断值
     */
    public function contains()
    {
        $collection = collect(['name' => 'Desk', 'price' => 100]);

        $collect = $collection->contains('Desk');

        $collect = $collection->contains('New York');

        //可以传入一个回调函数到 contains 方法内运行你自己的判断语句：
        $collection = collect([1, 2, 3, 4, 5]);

        $collect = $collection->contains(function ($value, $key) {
            return $value = 5;
        });
        dump($collect);
    }

    /**
     * 返回该集合内的项目总数
     */
    public function count()
    {
        $collection = collect([1, 2, 3, 4]);

        $collection = $collection->count();

        dump($collection);

    }

    /**
     * 将集合与其它集合或纯 PHP 数组 进行值的比较，
     * 返回第一个集合中存在而第二个集合中不存在
     */
    public function diff()
    {
        //把第二个集合中有的数据排除
        $collection = collect([1, 2, 3, 4, 5]);

        $diff = $collection->diff([2, 4, 6, 8]);

        $collection = $diff->all();

        dump($collection);
    }

    /**
     * 将集合与其它集合或纯 PHP 数组 的「键」进行比较，
     * 返回第一个集合中存在而第二个集合中不存在「键」所对应的键值对
     */
    public function diffKeys()
    {
        $collection = collect([
            'one' => 10,
            'two' => 20,
            'three' => 30,
            'four' => 40,
            'five' => 50,
        ]);

        $diff = $collection->diffKeys([
            'two' => 2,
            'four' => 4,
            'six' => 6,
            'eight' => 8,
        ]);

        $collection = $diff->all();

        dump($collection);
    }

    /**
     * 遍历集合中的项目，并将之传入回调函数
     * 回调函数中返回 false 以中断循环
     */
    public function each()
    {
        $collection = $this->collection->each(function ($item, $key) {
            dump($item);
            if($item > 5) return false;
        });
    }

    /**
     * 判断集合中每一个元素是否都符合指定条件
     */
    public function every()
    {
        collect([1, 2, 3, 4])->every(function ($value, $key) {
            return $value > 2;
        });
    }

    /**
     * 返回集合中除了指定键以外的所有项目
     */
    public function except()
    {
        $collection = collect(['product_id' => 1, 'price' => 100, 'discount' => false]);

        $filtered = $collection->except(['price', 'discount']);

        $collection = $filtered->all();
        dump($collection);
    }



}
?>