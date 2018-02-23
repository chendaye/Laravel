@extends("layout.main")
@section("content")
<div class="col-sm-8 blog-main">
    <form action="/Laravel/posts/{{ $post->id }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label>标题</label>
            <input name="title" type="text" class="form-control" placeholder="这里是标题" value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label>内容</label>
            <textarea id="content" name="content" class="form-control" style="height:400px;max-height:500px;"  placeholder="这里是内容">{{ $post->content }}</textarea>
            @include('layout.error')
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
    <br>
</div>
    @endsection