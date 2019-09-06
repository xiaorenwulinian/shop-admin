@extends('backend.layout.basic')

@section('content')
    <section class="content-header">
        <h1>
            用户管理
            <small>编辑用户</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 用户 </a></li>
            <li class="active"><a href="/backend/admin/lst">用户列表</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" action="{{ url('backend/admin/edit/'.$data['id']) }}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2 col-md-2">用户名</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" class="form-control" placeholder="admin" name="username" value="{{ $data['username'] }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label col-sm-2 col-md-2">密码</label>
                                <div class="col-sm-10 col-md-3">
                                    <input type="text" class="form-control" name="password" value="">
                                </div>
                            </div>

                            <div class="box-footer">
                                <input type="submit" class="btn btn-primary pull-right" value="提交">
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection