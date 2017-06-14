@extends("layout.main")
@section("content")
<div class="col-sm-8 blog-main">
    <div class="blog-post">
        <div style="display:inline-flex">
            <h2 class="blog-post-title">{{$post->title}}</h2>
            @can('edit', $post)
            <a style="margin: auto"  href="/Laravel/posts/{{$post->id}}/edit">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </a>
            @endcan
            @can('delete', $post)
            <a style="margin: auto"  href="/Laravel/posts/{{$post->id}}/delete">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
            @endcan
        </div>

        <p class="blog-post-meta">{{$post->created_at->toFormattedDateString()}}<a href="#">{{ $post->user->name }}</a></p>

        <p><p>{!! $post->content !!}<img src="http://127.0.0.1:8000/storage/72c76b674ec8793fcfd6555ff371bfbd/nxC9ozLfkORmoY92q9lPsejXchVvdNO2cwHiR2Jf.jpeg" alt="63" style="max-width: 100%;">你好你好似懂非懂说</p><p><br></p></p>
        @if($post->zan(\Illuminate\Support\Facades\Auth::id())->exists())
            <div>
                <a href="/Laravel/posts/{{$post->id}}/cancelZzan" type="button" class="btn btn-primary btn-lg">取消赞</a>
            </div>
            @else
            <div>
                <a href="/Laravel/posts/{{$post->id}}/zan" type="button" class="btn btn-primary btn-lg">赞</a>
            </div>
        @endif
    </div>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">评论</div>

        <!-- List group -->
        <ul class="list-group">
            @foreach($post->comment as $val)
            <li class="list-group-item">
                <h5>{{ $val->created_at }} by {{ $val->user->name }}</h5>
                <div>
                   {{ $val->content }}
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">发表评论</div>

        <!-- List group -->
        <ul class="list-group">
            <form action="/Laravel/posts/{{ $post->id }}/comment" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="post_id" value="{{ $post->id }}"/>
                <li class="list-group-item">
                    <textarea name="content" class="form-control" rows="10"></textarea>
                    <button class="btn btn-default" type="submit">提交</button>
                    @include('layout.error')
                </li>
            </form>

        </ul>
    </div>

</div><!-- /.blog-main -->
@endsection