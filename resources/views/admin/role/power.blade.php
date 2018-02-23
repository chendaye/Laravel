@extends('admin.layout.main')
@section('content')
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-10 col-xs-6">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">权限列表</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="/Laravel/admin/roles/{{ $role->id }}/power" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            @foreach($powers as $power)
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="powers[]"
                                           @if($myPower->contains($power))
                                           checked
                                           @endif
                                           value="{{ $power->id }}">
                                    {{ $power->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection