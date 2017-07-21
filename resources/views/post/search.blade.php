@extends("layout.main")
@section("content")
    <div class="alert alert-success" role="alert">
        下面是搜索"{{ $search }}"出现的文章，共{{ $content->total() }}条
    </div>
    <div class="col-sm-8 blog-main">
        @foreach($content as $post)
        <div class="blog-post">
            <h2 class="blog-post-title"><a href="/Laravel/posts/{{$post->id}}" >{{ $post->title }}</a></h2>
            <p class="blog-post-meta">{{$post->created_at->toFormattedDateString()}}<a href="#">{{ $post->user->name }}</a></p>
            <p>{!! str_limit($post->content, 100, '......') !!}</p>
        </div>
        @endforeach
            {{$content->links()}}
    </div><!-- /.blog-main -->
@endsection