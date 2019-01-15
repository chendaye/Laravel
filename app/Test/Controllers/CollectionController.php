<?php
namespace App\Test\Controllers;
use App\User;


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

    /**
     * 使用回调函数筛选集合，只留下那些通过判断测试的项目
     */
    public function filter()
    {
        $collection = collect([1, 2, 3, 4]);

        //筛选符合条件的记录
        $filtered = $collection->filter(function ($value, $key) {
            return $value > 2;
        });
        $collection = $filtered->all();

        //如果没有提供回调函数，集合中所有返回 false 的元素都会被移除：
        $collection = collect([1, 2, 3, null, false, '', 0, []]);

        $collection = $collection->filter()->all();
        dump($collection);
    }

    /**
     * 返回集合第一个通过指定测试的元素
     */
    public function first()
    {
        $collection = collect([1, 2, 3, 4])->first(function ($value, $key) {
            return $value > 2;
        });

        //你也可以不传入参数使用 first 方法以获取集合中第一个元素。如果集合是空的，则会返回
        $collection = collect([1, 2, 3, 4])->first();
        dump($collection);
    }

    /**
     * 对集合内所有子集遍历执行回调，并在最后转为一维集合
     */
    public function flatMap()
    {
        $collection = collect([
            ['name' => 'Sally'],
            ['school' => 'Arkansas'],
            ['age' => 28]
        ]);
        //对每个值执行回调函数
        $flattened = $collection->flatMap(function ($values) {
            return array_map('strtoupper', $values);
        });

        $collection = $flattened->all();
        dump($collection);
    }

    /**
     * 将多维集合转为一维集合
     */
    public function flatten()
    {
        $collection = collect(['name' => 'taylor', 'languages' => ['php', 'javascript']]);

        $flattened = $collection->flatten();

        $collection = $flattened->all();

        //可以选择性地传入遍历深度的参数
        /*
         * 调用 flatten 方法时不传入深度参数会遍历嵌套数组降维成一维数组，
         * 生成 ['iPhone 6S', 'Apple', 'Galaxy S7', 'Samsung']，传入深度参数能让你限制降维嵌套数组的层数*/
        $collection = collect([
            'Apple' => [
                ['name' => 'iPhone 6S', 'brand' => 'Apple'],
            ],
            'Samsung' => [
                ['name' => 'Galaxy S7', 'brand' => 'Samsung']
            ],
        ]);

        $products = $collection->flatten(1);

        $collection = $products->values()->all();
        dump($collection);
    }

    /**
     * 将集合中的键和对应的数值进行互换
     */
    public function flip()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $flipped = $collection->flip();

        $collection = $flipped->all();

        dump($collection);
    }

    /**
     * 通过集合的键来移除掉集合中的一个项目
     */
    public function forget()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);
        //通过键名删除元素
        $collection->forget('name');
        $collection = $collection->all();
        dump($collection);
    }

    /**
     * 返回可用来在指定页码上所显示项目的新集合。这个方法第一个参数是页码数，第二个参数是每页显示的个数
     */
    public function forPage()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        //页码 每页显示的个数
        $chunk = $collection->forPage(2, 3);

        $collection = $chunk->all();
        dump($collection);
    }

    /**
     * 返回指定键的项目。如果该键不存在，则返回 null
     */
    public function get()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $value = $collection->get('name');

        //可以选择性地传入一个默认值作为第二个参数
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $value = $collection->get('foo', 'default-value');

        //可以传入回调函数当默认值。如果指定的键不存在，就会返回回调函数的运行结果
        $value = $collection->get('email', function () {
            return 'default-value-clouse';
        });

        dump($value);
    }

    /**
     * 根据指定的「键」为集合内的项目分组
     */
    public function groupBy()
    {
        $collection = collect([
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ]);

        $grouped = $collection->groupBy('account_id');

        $collection = $grouped->toArray();

        //除了传入字符串的「键」之外，你也可以传入回调函数。该函数应该返回你希望用来分组的键的值
        $grouped = $collection->groupBy(function ($item, $key) {
            return substr($item['account_id'], -3);
        });

        $collection = $grouped->toArray();

        dump($collection);
    }

    /**
     * 检查集合中是否含有指定的「键」
     */
    public function has()
    {
        $collection = collect(['account_id' => 1, 'product' => 'Desk']);

        $collection = $collection->has('product');

        dump($collection);
    }

    /**
     * implode 方法合并集合中的项目。它的参数依集合中的项目类型而定。
     * 假如集合含有数组或对象，你应该传入你希望连接的属性的「键」，以及你希望放在数值之间的拼接字符串
     */
    public function implode()
    {
        $collection = collect([
            ['account_id' => 1, 'product' => 'Desk'],
            ['account_id' => 2, 'product' => 'Chair'],
        ]);

        $collection = $collection->implode('product', ', ');

        //假如集合只含有简单的字符串或数字，则只需要传入拼接的字符串作为该方法的唯一参数即可

        $collection = collect([1, 2, 3, 4, 5])->implode('-');
        dump($collection);
    }

    /**
     * 移除任何指定 数组 或集合内所没有的数值。最终集合保存着原集合的键
     */
    public function intersect()
    {
        $collection = collect(['Desk', 'Sofa', 'Chair']);

        //就是取交集
        $intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);

        $collection = $intersect->all();
        dump($collection);
    }

    /**
     * 如果集合是空的，isEmpty 方法会返回 true：否则返回 false
     */
    public function isEmpty()
    {
        $collect = collect([])->isEmpty();
        dump($collect);
    }

    /**
     * 以指定键的值作为集合项目的键。如果几个数据项有相同的键，那在新集合中只显示最后一项
     */
    public function keyBy()
    {
        //用指定的键值 排序
        $collection = collect([
            ['product_id' => 'prod-100', 'name' => 'desk'],
            ['product_id' => 'prod-200', 'name' => 'chair'],
        ]);

        $keyed = $collection->keyBy('product_id');

        $collect = $keyed->all();

        //也可以传入自己的回调函数，该函数应该返回集合的键的值：
        $keyed = $collection->keyBy(function ($item) {
            return strtoupper($item['product_id']);
        });

        $collect = $keyed->all();
        dump($collect);
    }

    /**
     * 返回该集合所有的键
     */
    public function keys()
    {
        $collection = collect([
            'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
            'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);
        $keys = $collection->keys();
        $collect = $keys->all();
        dump($collect);
    }

    /**
     * 返回集合中，最后一个通过指定测试的元素
     */
    public function last()
    {
        $collect = collect([1, 2, 3, 4])->last(function ($value, $key) {
            return $value < 3;
        });

        //你也可以不传入参数使用 last 方法以获取集合中最后一个元素。如果集合是空的，则会返回
        $collect = collect([1, 2, 3, 4])->last();
        dump($collect);
    }

    /**
     * 遍历整个集合并将每一个数值传入回调函数。回调函数可以任意修改并返回项目，形成修改过的项目组成的新集合
     */
    public function map()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $multiplied = $collection->map(function ($item, $key) {
            return $item * 2;
        });

        $collection = $multiplied->all();
        dd($collection);
    }

    /**
     * 遍历整个集合并将每一个数值传入回调函数。回调函数返回包含一个键值对的关联数组
     */
    public function mapWithKeys()
    {
        //自己组装键值对
        $collection = collect([
            [
                'name' => 'John',
                'department' => 'Sales',
                'email' => 'john@example.com'
            ],
            [
                'name' => 'Jane',
                'department' => 'Marketing',
                'email' => 'jane@example.com'
            ]
        ]);

        /*
    [
        'john@example.com' => 'John',
        'jane@example.com' => 'Jane',
    ]
*/
        $keyed = $collection->mapWithKeys(function ($item) {
            return [$item['email'] => $item['name']];
        });

        $collection = $keyed->all();
        dd($collection);
    }

    /**
     * 计算指定键的最大值
     */
    public function max()
    {
        $max = collect([['foo' => 10], ['foo' => 20]])->max('foo');

        $max = collect([1, 2, 3, 4, 5])->max();
        dump($max);
    }

    /**
     * 合并数组进集合。数组「键」对应的数值会覆盖集合「键」对应的数值
     */
    public function merge()
    {
        $collection = collect(['product_id' => 1, 'price' => 100]);

        $merged = $collection->merge(['price' => 200, 'discount' => false]);

        $collection = $merged->all();

        //如果指定数组的「键」为数字，则「值」将会合并到集合的后面：
        $collection = collect(['Desk', 'Chair']);

        $merged = $collection->merge(['Bookcase', 'Door']);

        $merged->all();
        dump($collection);
    }

    /**
     * 计算指定「键」的最小值
     */
    public function min()
    {
        $min = collect([['foo' => 10], ['foo' => 20]])->min('foo');

        $min = collect([1, 2, 3, 4, 5])->min();
        dd($min);
    }

    /**
     * 由每隔第 n 个元素组成一个新的集合
     */
    public function nth()
    {
        $collection = collect(['a', 'b', 'c', 'd', 'e', 'f']);
        $collection = $collection->nth(4);

        //你也可以选择传入一个偏移量作为第二个参数
        $collection = $collection->nth(4, 1);
        dd($collection);
    }

    /**
     * 返回集合中指定键的所有项目
     */
    public function only()
    {
        $collection = collect(['product_id' => 1, 'name' => 'Desk', 'price' => 100, 'discount' => false]);

        $filtered = $collection->only(['product_id', 'name']);

        $ret = $filtered->all();
        dump($ret);
    }

    /**
     * 结合 PHP 中的 list 方法来分开符合指定条件的元素以及那些不符合指定条件的元素
     */
    public function partition()
    {
        $collection = collect([1, 2, 3, 4, 5, 6]);

        list($underThree, $aboveThree) = $collection->partition(function ($i) {
            return $i < 3;
        });
    }

    /**
     * 将集合传给回调函数并返回结果
     */
    public function pipe()
    {
        $collection = collect([1, 2, 3]);

        $piped = $collection->pipe(function ($collection) {
            return $collection->sum();
        });

        dump($piped);
    }

    /**
     * 获取集合中指定「键」所有对应的值
     */
    public function pluck()
    {
        $collection = collect([
            ['product_id' => 'prod-100', 'name' => 'Desk'],
            ['product_id' => 'prod-200', 'name' => 'Chair'],
        ]);

//        $plucked = $collection->pluck('name');
//
//        $collection  = $plucked->all();

        //也可以指定最终集合的键
        $plucked = $collection->pluck('name', 'product_id');

        $collection  = $plucked->all();
        dd($collection);
    }

    /**
     * 移除并返回集合最后一个项目
     */
    public function pop()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $collection->pop();

        $ret = $collection->all();
        dump($ret);
    }

    /**
     * 在集合前面增加一项数组的值
     */
    public function prepend()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $collection->prepend(0);

        $ret = $collection->all();

        //你可以传递第二个参数来设置新增加项的键
        $collection = collect(['one' => 1, 'two' => 2]);

        $collection->prepend(0, 'zero');

        $ret = $collection->all();
        dump($ret);
    }

    /**
     * 把「键」对应的值从集合中移除并返回
     */
    public function pull()
    {
        $collection = collect(['product_id' => 'prod-100', 'name' => 'Desk']);

        $collection->pull('name');

        $ret = $collection->all();
        dump($ret);
    }

    /**
     * 在集合的后面新添加一个元素
     */
    public function push()
    {
        $collection = collect([1, 2, 3, 4]);

        $collection->push(5);

        $ret = $collection->all();
        dump($ret);
    }

    /**
     * 在集合内设置一个「键/值」
     */
    public function put()
    {
        $collection = collect(['product_id' => 1, 'name' => 'Desk']);

        $collection->put('price', 100);

        $collection = $collection->all();
        dump($collection);
    }

    /**
     * random 方法从集合中随机返回一个项目：
     */
    public function random()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $ret = $collection->random();

        //可以选择性地传入一个整数到 random。如果该整数大于 1，则会返回一个集合：
        $random = $collection->random(3);
        $ret = $random->all();
        dump($ret);
    }

    /**
     * reduce 方法将集合缩减到单个数值，该方法会将每次迭代的结果传入到下一次迭代
     */
    public function reduce()
    {
        $collection = collect([1, 2, 3]);

        $total = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        });

        //第一次迭代时 $carry 的数值为 null；然而你也可以传入第二个参数进 reduce 以指定它的初始值
        $total = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 4);
        dump($total);
    }

    /**
     * reject 方法以指定的回调函数筛选集合。会移除掉那些通过判断测试（即结果返回 true）的项目
     */
    public function reject()
    {
        $collection = collect([1, 2, 3, 4]);

        //去掉符合要求的
        $filtered = $collection->reject(function ($value, $key) {
            return $value > 2;
        });

        $ret = $filtered->all();
        dump($ret);
    }

    /**
     * reverse 方法倒转集合内项目的顺序
     */
    public function reverse()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $reversed = $collection->reverse();

        $ret = $reversed->all();
        dump($ret);
    }

    /**
     * search 方法在集合内搜索指定的数值并返回找到的键。假如找不到项目，则返回 false
     */
    public function search()
    {
        $collection = collect([2, 4, 6, 8]);

       $ret =  $collection->search(4);

       //搜索是用「宽松」匹配来进行，也就是说如果字符串值是整数那它就跟这个整数是相等的。
        //要使用严格匹配的话，就传入 true 为该方法的第二个参数
        $ret = $collection->search('4', true);

        //你可以传入你自己的回调函数来搜索第一个通过你判断测试的项目
        $ret =  $collection->search(function ($item, $key) {
            return $item > 5;
        });
       dump($ret);
    }

    /**
     * shift 方法移除并返回集合的第一个项目
     */
    public function shift()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $collection->shift();

        $collection = $collection->all();
        dump($collection);
    }

    /**
     * shuffle 方法随机排序集合的项目
     */
    public function shuffle()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $shuffled = $collection->shuffle();

        $collection = $shuffled->all();
        dd($collection);
    }

    /**
     * slice 方法返回集合从指定索引开始的一部分切片
     */
    public function slice()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $slice = $collection->slice(4);

        $collection = $slice->all();
        dump($collection);

        //如果你想限制返回切片的大小，就传入想要的大小为方法的第二个参数
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $slice = $collection->slice(4, 2);

        $collection = $slice->all();
        dump($collection);
        //返回的切片将会保留原始键作为索引。假如你不希望保留原始的键，你可以使用 values 方法来重新建立索引
    }

    /**
     * 对集合排序。排序后的集合保留着原始数组的键，
     * 所以在这个例子里我们用 values 方法来把键设置为连续数字的键
     */
    public function sort()
    {
        $collection = collect([5, 3, 1, 2, 4]);
        //对值排序 排序之后键保留不变
        $sorted = $collection->sort();

        $sorted = $sorted->values()->all();
        dump($sorted);
    }

    /**
     * 以指定的键排序集合。
     * 排序后的集合保留了原始数组键，
     * 所以在这个例子中我们用 values method 把键设置为连续数字的索引建
     */
    public function sortBy()
    {
        $collection = collect([
            ['name' => 'Desk', 'price' => 200],
            ['name' => 'Chair', 'price' => 100],
            ['name' => 'Bookcase', 'price' => 150],
        ]);

        //用指定的键值来排序 保留原始的键
        $sorted = $collection->sortBy('price');
        $sorted = $sorted->values()->all();

        //也可以传入自己的回调函数以决定如何排序集合数值
        $collection = collect([
            ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
            ['name' => 'Chair', 'colors' => ['Black']],
            ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
        ]);

        $sorted = $collection->sortBy(function ($value, $key) {
            return count($value['colors']);
        });

        $sorted->values()->all();
        dump($sorted);
    }

    /**
     * 与 sortBy 有着一样的形式，但是会以相反的顺序来排序集合
     */
    public function sortByDesc()
    {

    }

    /**
     * 返回从指定的索引开始的一小切片项目，原本集合也会被切除
     */
    public function splice()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        //集合切为两份
        $chunk = $collection->splice(2);

        $a = $chunk->all();
        dump($a);
        $b = $collection->all();
        dump($b);

        //你可以传入第二个参数以限制大小
        $collection = collect([1, 2, 3, 4, 5]);

        $chunk = $collection->splice(2, 1);

        $a = $chunk->all();
        dump($a);
        $b = $collection->all();
        dump($b);

        //你可以传入含有新项目的第三个参数以取代集合中被移除的项目
        $collection = collect([1, 2, 3, 4, 5]);

        $chunk = $collection->splice(2, 1, [10, 11]);

        $a = $chunk->all();
        dump($a);
        $b = $collection->all();
        dump($b);
    }

    /**
     * 将集合按指定组数分解
     */
    public function split()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $groups = $collection->split(3);
        dump($groups);
        $array = $groups->toArray();
        dump($array);
    }

    /**
     * 返回集合内所有项目的总和
     */
    public function sum()
    {
        $sum = collect([1, 2, 3, 4, 5])->sum();

        //如果集合包含嵌套数组或对象，你应该传入一个「键」来指定要用哪些数值来计算总和
        $collection = collect([
            ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
            ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
        ]);

        $sum = $collection->sum('pages');

        //可以传入自己的回调函数来决定要用哪些数值来计算总和

        $collection = collect([
            ['name' => 'Chair', 'colors' => ['Black']],
            ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
            ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
        ]);

        $sum = $collection->sum(function ($product) {
            return count($product['colors']);
        });
        dump($sum);
    }

    /**
     * 返回有着指定数量项目的集合
     */
    public function take()
    {
        $collection = collect([0, 1, 2, 3, 4, 5]);

        $chunk = $collection->take(3);

        $take = $chunk->all();

        //你也可以传入负整数以获取从集合后面来算指定数量的项目 返回后面的元素
        $collection = collect([0, 1, 2, 3, 4, 5]);

        $chunk = $collection->take(-2);

        $take = $chunk->all();
        dump($take);
    }

    /**
     * 将集合转换成纯 PHP 数组。假如集合的数值是 Eloquent 模型，也会被转换成数组
     */
    public function toArray()
    {
        $collection = collect(['name' => 'Desk', 'price' => 200]);

        $array = $collection->toArray();
        dump($array);
    }

    /**
     * 将集合转换成 JSON
     */
    public function toJson()
    {
        $collection = collect(['name' => 'Desk', 'price' => 200]);

        $json = $collection->toJson();
        dump($json);
    }

    /**
     * 遍历集合并对集合内每一个项目调用指定的回调函数。集合的项目将会被回调函数返回的数值取代掉
     */
    public function transform()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $collection->transform(function ($item, $key) {
            return $item * 2;
        });

        $collection = $collection->all();
        dump($collection);
    }

    /**
     * 将给定的数组合并到集合中，如果数组中含有与集合一样的「键」，集合的键值会被保留
     */
    public function union()
    {
        $collection = collect([1 => ['a'], 2 => ['b']]);
        //有一样的保留原元素
        $union = $collection->union([3 => ['c'], 1 => ['b']]);

        $ret = $union->all();
        dump($ret);
    }

    /**
     * unique 方法返回集合中所有唯一的项目。
     * 返回的集合保留着原始键，所以在这个例子中我们用 values 方法来把键重置为连续数字的键
     */
    public function unique()
    {
        $collection = collect([1, 1, 2, 2, 3, 4, 2]);

        $unique = $collection->unique();

        $ret = $unique->values()->all();

        //当处理嵌套数组或对象的时候，你可以指定用来决定唯一性的键
        $collection = collect([
            ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
            ['name' => 'iPhone 5', 'brand' => 'Apple', 'type' => 'phone'],
            ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
            ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
            ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
        ]);

        $unique = $collection->unique('brand');

        $ret = $unique->values()->all();

//        你可以传入自己的回调函数来确定项目的唯一性  自定义唯一性
        $unique = $collection->unique(function ($item) {
            return $item['brand'].$item['type'];
        });

        $ret = $unique->values()->all();
        dump($ret);
    }

    /**
     * 返回「键」重新被设为「连续整数」的新集合
     */
    public function values()
    {
        $collection = collect([
            10 => ['product' => 'Desk', 'price' => 200],
            11 => ['product' => 'Desk', 'price' => 200]
        ]);

        $values = $collection->values();

        $ret = $values->all();
        dump($ret);
    }

    /**
     * 当第一个参数运算结果为 true 的时候，会执行第二个参数传入的闭包：
     */
    public function when()
    {
        $collection = collect([1, 2, 3]);

        $collection->when(true, function ($collection) {
            return $collection->push(4);
        });

        $ret = $collection->all();
        dump($ret);
    }

    /**
     * 以一对指定的「键／数值」筛选集合
     */
    public function where()
    {
        $collection = collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->where('price', 100);

        $ret = $filtered->all();
        dump($ret);

    }

    /**
     * 这个方法与 where 方法有着一样的形式；但是会以「严格」匹配来匹配数值
     */
    public function whereStrict()
    {

    }

    /**
     * 基于参数中的键值数组进行过滤：
     */
    public function whereIn()
    {
        $collection = collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => 100],
        ]);

        $filtered = $collection->whereIn('price', [150, 200]);

        $ret = $filtered->all();
        dump($ret);
    }

    /**
     * zip 方法将集合与指定数组相同索引的值合并在一起
     */
    public function zip()
    {
        $collection = collect(['Chair', 'Desk']);

        //键值相同的聚合在一起
        $zipped = $collection->zip([100, 200]);

        $ret = $zipped->all();
        dump($ret);
    }

    /**
     * 高阶信息传递
     * 集合也提供「高阶信息传递支持」，这是对集合执行常见操作的快捷方式。
     * 支持高阶信息传递的集合方法有：
     * contains， each， every， filter， first， map， partition， reject， sortBy， sortByDesc 和 sum
     */
    public function HighOrder()
    {
        /*
         * 每个高阶信息都能作为集合实例的动态属性来访问。
         * 例如，我们在集合中使用 each 高阶信息传递方法拉哎对每个对象去调用一个方法*/
        $users = User::where('id', '<', 500)->get();

      // $ret = $users->each->markAsVip();

        //同样 可以使用 sum 高阶信息传递的方式来统计出集合中用户总共的
        $users = User::where('id', '7')->get();
        //当做动态属性
        $ret = $users->sum->id;
        dump($ret);
    }

}
?>