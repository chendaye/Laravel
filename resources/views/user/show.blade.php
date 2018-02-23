@extends('layout.main')
@section('content')
    <div class="col-sm-8">
        <blockquote>
            <p>
                <img src="{{ $user->avatar }}" alt="" class="img-rounded" style="border-radius:500px; height: 40px">
                {{ $user->name }}
            </p>
            <footer>关注：{{ $user->stars_count }}｜粉丝：{{ $user->fans_count }}｜文章：{{ $user->posts_count }}</footer>
        </blockquote>
    </div>
    <div class="col-sm-8 blog-main">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">文章</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">关注</a></li>
                <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">粉丝</a></li>
            </ul>
            <div class="tab-content">
                @foreach($posts as $post)
                <div class="tab-pane active" id="tab_1">
                    <div class="blog-post" style="margin-top: 30px">
                        <p class=""><a href="/user/{{ $post->user->id }}">{{ $post->user->name }}</a> {{ $post->created_at->diffForHumans() }}</p>
                        @include('layout.focus', ['focus' => $user])
                        <p class=""><a href="/Laravel/posts/{{ $post->user->id }}" >{{ $post->title }}</a></p>
                        <p><p>{!! $post->content !!}</p>
                    </div>
                </div>
                @endforeach
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    @foreach($starers as $star)
                    <div class="blog-post" style="margin-top: 30px">
                        <p class="">{{ $star->name }}</p>
                        {{--一个模型 就定义了一个表的所有关系  从表里任意取出一个记录（对象）都会具有这些属性--}}
                        <p class="">关注：{{ $star->stars_count }} | 粉丝：{{ $star->fans_count }}｜ 文章：{{ $star->posts_count }}</p>
                        @include('layout.focus', ['focus' => $user])
                    </div>
                        @endforeach

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                    @foreach($faners as $fan)
                        <div class="blog-post" style="margin-top: 30px">
                            <p class="">{{ $fan->name }}</p>
                            {{--一个模型 就定义了一个表的所有关系  从表里任意取出一个记录（对象）都会具有这些属性--}}
                            <p class="">关注：{{ $fan->stars_count }} | 粉丝：{{ $fan->fans_count }}｜ 文章：{{ $fan->posts_count }}</p>
                            @include('layout.focus', ['focus' => $user])
                        </div>
                    @endforeach
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>


    </div><!-- /.blog-main -->
@endsection