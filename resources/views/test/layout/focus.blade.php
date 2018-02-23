
@if(\Illuminate\Support\Facades\Auth::id() != $focus->id)
    <div>
    {{--操作的是当前登录用户   当前用户是否已经关注了某人--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasStar($focus->id))
    <button class="btn btn-default like-button" like-value="1" like-user="{{ $focus->id }}" _token="{{ csrf_token() }}" type="button">取消关注</button>
    @else
    <button class="btn btn-default like-button" like-value="0" like-user="{{ $focus->id }}" _token="{{ csrf_token() }}" type="button">关注</button>
    @endif
</div>
@endif

