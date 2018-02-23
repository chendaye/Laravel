{{--为方便开始，让我们先通过一个简单的例子来上手。首先，我们需要确认一个 "master" 的页面布局。
因为大多数 web 应用是在不同的页面中使用相同的布局方式，我们可以很方便的定义这个 Blade 布局视图：
@section 命令正如其名字所暗示的一样是用来定义一个视图区块的，而 @yield 指令是用来显示指定区块的内容的--}}
<html>
<head>
    <title>应用程序名称 - @yield('title')</title>
</head>
<body>
{{--@section 命令正如其名字所暗示的一样是用来定义一个视图区块的--}}
@section('sidebar')
    这是 master 的侧边栏。
@show

<div class="container">
    {{--@yield 指令是用来显示指定区块的内容的--}}
    @yield('content')
</div>
</body>
</html>
{{----}}


{{--继承页面布局

当定义子页面时，你可以使用 Blade 提供的 @extends 命令来为子页面指定其所 「继承」 的页面布局。
当子页面继承布局之后，即可使用 @section 命令将内容注入于布局的 @section 区块中。
切记，在上面的例子里，布局中使用 @yield 的地方将会显示这些区块中的内容：--}}
@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    {{--sidebar 区块利用了 @@parent 命令追加布局中的 sidebar 区块中的内容，如果不使用则会覆盖掉布局中的这部分内容。
    @@parent 命令会在视图被渲染时替换为布局中的内容。--}}
    @@parent

    <p>This is appended to the master sidebar.</p>
@endsection


@section('content')
    <p>This is my body content.</p>
@endsection
{{----}}


{{--组件 & Slots

组件和 slots 能提供类似于区块和布局的好处；
不过，一些人可能发现组件和 slots 更容易理解。首先，让我们假设一个会在我们应用中重复使用的「警告」组件:--}}

{{--{{ $slot }} 变量将包含我们希望注入到组件的内容。现在，我们可以使用 @component 指令来构造这个组件：--}}
<div class="alert alert-danger">
    {{ $slot }}
</div>

{{--构造组件--}}
@component('alert')
    <strong>哇！</strong> 出现了一些问题！
@endcomponent

{{--有些时候它对于定义组件的多个 slots 是非常有帮助的。
让我们修改我们的警告组件，让它支持注入一个「标题」。 已命名的 slots 将显示「相对应」名称的变量的值:--}}
<div class="alert alert-danger">
    <div class="alert-title">{{ $title }}</div>

    {{ $slot }}
</div>

{{--现在，我们可以使用 @slot 指令注入内容到已命名的 slot 中，任何没有被 @slot 指令包裹住的内容将传递给组件中的 $slot 变量:--}}
@component('alert')
    @slot('title')
        拒绝
    @endslot
    你没有权限访问这个资源！
@endcomponent

{{--传递额外的数据给组件

有时候你可能需要传递额外的数据给组件。
为了解决这个问题，你可以传递一个数组作为第二个参数传递给 @component 指令。所有的数据都将以变量的形式传递给组件模版:--}}
@component('alert', ['foo' => 'bar'])
    ...
@endcomponent

{{----}}


{{--引入子视图
你可以使用 Blade 的 @include 命令来引入一个已存在的视图，所有在父视图的可用变量在被引入的视图中都是可用的。--}}
<div>
    @include('shared.errors')

    <form>
        <!-- Form Contents -->
    </form>
</div>
{{--尽管被引入的视图会继承父视图中的所有数据，你也可以通过传递额外的数组数据至被引入的页面：--}}
@include('view.name', ['some' => 'data'])
{{--当然，如果你尝试使用 @include 去引用一个不存在的视图，Laravel 会抛出错误。如果你想引入一个视图，而你又无法确认这个视图存在与否，你可以使用 @includeIf 指令--}}
@includeIf('view.name', ['some' => 'data'])
{{----}}


{{--为集合渲染视图
你可以使用 Blade 的 @each 命令将循环及引入结合成一行代码--}}
@each('view.name', $jobs, 'job')
{{--第一个参数为每个元素要渲染的子视图，第二个参数是你要迭代的数组或集合，而第三个参数为迭代时被分配至子视图中的变量名称。
举个例子，如果你需要迭代一个 jobs 数组，通常子视图会使用 job 作为变量来访问 job 信息。子视图使用 key 变量作为当前迭代的键名。--}}

{{--你也可以传递第四个参数到 @each 命令。当需要迭代的数组为空时，将会使用这个参数提供的视图来渲染。--}}
@each('view.name', $jobs, 'job', 'view.empty')
{{----}}


{{--堆栈
Blade 也允许你在其它视图或布局中为已经命名的堆栈中压入数据，这在子视图中引入必备的 JavaScript 类库时尤其有用--}}
@push('scripts')
<script src="/example.js"></script>
@endpush
{{--你可以根据需要多次压入堆栈，通过 @stack 命令中键入堆栈的名字来渲染整个堆栈：--}}
<head>
    <!-- Head Contents -->

    @stack('scripts')
</head>
{{----}}


{{--服务注入
你可以使用 @inject 命令来从 Larvel service container 中取出服务。
传递给 @inject 的第一个参数为置放该服务的变量名称，而第二个参数为你想要解析的服务的类或是接口的名称--}}
@inject('metrics', 'App\Services\MetricsService')

<div>
    Monthly Revenue: {{ $metrics->monthlyRevenue() }}.
</div>
{{----}}