{{--显示变量 可以是任何PHP代码--}}
Hello, {{ $name }}
{{----}}

{{--当数据存在时输出 有时候你可能想要输出一个变量，但是你并不确定这个变量是否已经被定义，我们可以用像这样的冗长 PHP 代码表达--}}
{{ isset($name) ? $name : 'Default' }}
{{--Blade 提供了更便捷的方式来代替这种三元运算符表达式--}}
{{ $name or 'Default' }}
{{----}}

{{--显示未转义过的数据
在默认情况下，Blade 模板中的 {{ }} 表达式将会自动调用 PHP htmlspecialchars 函数来转义数据以避免 XSS 的攻击。如果你不想你的数据被转义，你可以使用下面的语法--}}
Hello, {!! $name !!}
{{----}}

{{--Blade & JavaScript 框架
由于很多 JavaScript 框架都使用花括号来表明所提供的表达式，所以你可以使用 @ 符号来告知 Blade 渲染引擎你需要保留这个表达式原始形态--}}
<h1>Laravel</h1>
Hello, @{{ name }}
{{--在这个例子里，@ 符号最终会被 Blade 引擎剔除，并且 {{ name }} 表达式会被原样的保留下来，这样就允许你的 JavaScript 框架来使用它了。--}}
{{----}}

{{--@verbatim 指令
如果你需要在页面中大片区块中展示 JavaScript 变量，你可以使用 @verbatim 指令来包裹 HTML 内容，这样你就不需要为每个需要解析的变量增加 @ 符号前缀了--}}
@verbatim
    <div class="container">
        Hello, {{ name }}.
    </div>
@endverbatim
{{----}}

{{--If 语句
你可以通过 @if, @elseif, @else 及 @endif 指令构建 if 表达式。这些命令的功能等同于在 PHP 中的语法：--}}
@if (count($records) === 1)
    我有一条记录！
@elseif (count($records) > 1)
    我有多条记录！
@else
    我没有任何记录！
@endif

{{--为了方便，Blade 也提供了一个 @unless 命令：--}}
@unless (Auth::check())
    你尚未登录。
@endunless
{{----}}

{{--循环
除了条件表达式外，Blade 也支持 PHP 的循环结构，这些命令的功能等同于在 PHP 中的语法--}}
@for ($i = 0; $i < 10; $i++)
    目前的值为 {{ $i }}
@endfor

@foreach ($users as $user)
    <p>此用户为 {{ $user->id }}</p>
@endforeach

@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>没有用户</p>
@endforelse

@while (true)
    <p>我永远都在跑循环。</p>
@endwhile

{{--当使用循环时，你可能也需要一些结束循环或者跳出当前循环的命令--}}
@foreach ($users as $user)
    @if ($user->type == 1)
        @continue
    @endif

    <li>{{ $user->name }}</li>

    @if ($user->number == 5)
        @break
    @endif
@endforeach

{{--你也可以使用命令声明包含条件的方式在一条语句中达到中断  效果一样--}}
@foreach ($users as $user)
    @continue($user->type == 1)

    <li>{{ $user->name }}</li>

    @break($user->number == 5)
@endforeach

{{----}}


{{--循环变量
当循环时，你可以在循环内访问 $loop 变量。这个变量可以提供一些有用的信息，比如当前循环的索引，当前循环是不是首次迭代，又或者当前循环是不是最后一次迭代--}}
@foreach ($users as $user)
    @if ($loop->first){{--第一次循环--}}
        This is the first iteration.
    @endif

    @if ($loop->last) {{--最后一次循环--}}
        This is the last iteration.
    @endif

    <p>This is user {{ $user->id }}</p>
@endforeach

{{--如果你是在一个嵌套的循环中，你可以通过使用 $loop 变量的 parent 属性来获取父循环中的 $loop 变量--}}
@foreach ($users as $user)
    @foreach ($user->posts as $post)
        @if ($loop->parent->first)
            This is first iteration of the parent loop.
        @endif
    @endforeach
@endforeach

{{--属性	描述
$loop->index	当前循环所迭代的索引，起始为 0。
$loop->iteration	当前迭代数，起始为 1。
$loop->remaining	循环中迭代剩余的数量。
$loop->count	被迭代项的总数量。
$loop->first	当前迭代是否是循环中的首次迭代。
$loop->last	当前迭代是否是循环中的最后一次迭代。
$loop->depth	当前循环的嵌套深度。
$loop->parent	当在嵌套的循环内时，可以访问到父循环中的 $loop 变量。--}}
{{----}}

{{--PHP
在某些情况下，它对于你在视图文件中嵌入 php 代码是非常有帮助的。你可以在你的模版中使用 Blade 提供的 @php 指令来执行一段纯 PHP 代码：--}}
@php
    //
@endphp
{{----}}


{{--Blade 授权--}}
{{--通过 Blade 模板
当编写 Blade 模板时，你可能希望页面的指定部分只展示给允许授权访问给定动作的用户。
例如，你可能希望只展示更新表单给有权更新博客的用户。
这种情况下，你可以直接使用 @can 和 @cannot 指令。--}}

@can('update', $post)
    <!-- 当前用户可以更新博客 -->
@elsecan('create', $post)
    <!-- 当前用户可以新建博客 -->
@endcan

@cannot('update', $post)
    <!-- 当前用户不可以更新博客 -->
@elsecannot('create', $post)
    <!-- 当前用户不可以新建博客 -->
@endcannot

{{--等价于--}}
@if (Auth::user()->can('update', $post))
    <!-- 当前用户可以更新博客 -->
@endif

@unless (Auth::user()->can('update', $post))
    <!-- 当前用户不可以更新博客 -->
@endunless

{{--不需要指定模型的动作
和大部分其他的授权方法类似，当动作不需要模型实例时，
你可以传递一个类名给 @can 和 @cannot 指令--}}
@can('create', Post::class)
    <!-- 当前用户可以新建博客 -->
@endcan

@cannot('create', Post::class)
    <!-- 当前用户不可以新建博客 -->
@endcannot
{{----}}