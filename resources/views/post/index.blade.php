@extends("layout.main")
@section("content")
<div class="col-sm-8 blog-main">
    <div>
        <div id="carousel-example" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example" data-slide-to="1"></li>
                <li data-target="#carousel-example" data-slide-to="2"></li>
            </ol><!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="http://ww1.sinaimg.cn/large/44287191gw1excbq6tb3rj21400migrz.jpg" alt="..." />
                    <div class="carousel-caption">...</div>
                </div>
                <div class="item">
                    <img src="http://ww3.sinaimg.cn/large/44287191gw1excbq5iwm6j21400min3o.jpg" alt="..." />
                    <div class="carousel-caption">...</div>
                </div>
                <div class="item">
                    <img src="http://ww2.sinaimg.cn/large/44287191gw1excbq4kx57j21400migs4.jpg" alt="..." />
                    <div class="carousel-caption">...</div>
                </div>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span></a>
            <a class="right carousel-control" href="#carousel-example" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>
    </div>        <div style="height: 20px;">
    </div>
    <div>
        @foreach($post as $key => $val)
        <div class="blog-post">
            <h2 class="blog-post-title"><a href="/Laravel/posts/{{$val->id}}" >{{$val->title}}</a></h2>
            <p class="blog-post-meta">{{$val->created_at->toFormattedDateString()}} <a href="/user/{{ $val->user_id }}">{{ $val->user->name }}</a></p>

            <p>{!! str_limit($val->content, 1000, '......') !!}
            <p class="blog-post-meta">赞 0  | 评论 {{ $val->comment_count }}</p>
        </div>
        @endforeach
        {{$post->links()}}

    </div><!-- /.blog-main -->
</div>
@endsection