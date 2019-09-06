@extends('backend.layout.basic')


@section('style')
    <style>
        .common_search {
            margin: 10px auto;
        }
        .page_size_select{
            width: 105px;
            float: right;
            display: inline-block;
            padding-left: 0;
            margin: 20px 10px;
            border-radius: 4px;
            padding: 6px 12px;
            /*text-align: right;*/
        }
        .common_search input,.common_search select{
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            用户管理
            <small><a href="{{url('backend/admin/add')}}" class="pull-right">添加用户</a></small>
        </h1>
    </section>
    <section class="content">
        <div class="common_search">
            <form class="form-inline" action="{{url('backend/admin/lst')}}" id="form_search">
                <input type="text" class="form-control" name="username" placeholder="用户名称" value="{{request('username','')}}">

                <input  type="hidden" name="page_size" id="cur_show_page" value="{{$page_size}}"/>

                <input class="btn btn-flat btn-primary" type="submit" value="搜索">
            </form>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-active table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>是否禁用</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->username}}</td>
                                        <td>{{$v->is_use==1?'启用':'禁用'}}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{url("backend/admin/edit/{$v->id}")}}" title="编辑">编辑</a>
                                            @if($v->is_use == 1)
                                                <a class="btn btn-info btn-sm change_status" href="#" data-status="0" data-id="{{$v->id}}">禁用</a>
                                            @else
                                                <a class="btn btn-info btn-sm change_status" href="#" data-status="1" data-id="{{$v->id}}">启用</a>
                                            @endif
                                            <a class="btn btn-danger btn-sm" href="{{url("backend/admin/delete/{$v->id}")}}" title="删除">删除</a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div>
                            {{$list->links()}}
                            {!! $page_size_select !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="/static/backend/dist/js/admin_common.js" type="text/javascript" ></script>
    <script>
        $(function(){
            // 修改每页显示数量
            $(".page_size_select").on('change',function() {
                let page_size = $(this).val();
                $('#cur_show_page').val(page_size);
                $('#form_search').trigger('submit');
                return false;
            });
        });
        // 修改状态
        $('.change_status').click(function () {
            let text = $(this).text();
            let id = $(this).attr('data-id');
            let is_use = $(this).attr('data-status');
            if(confirm('确认'+ text + '?')){
                $.ajax({
                    url: "{{url('backend/admin/changeStatus')}}",
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {'is_use': is_use, 'id': id},
                    success:function(res){
                        console.log(res);
                        if(res.code == 0){
                            window.location.reload();
                        }else {
                            alert(data.error);
                        }
                    }
                })
            }
        });
    </script>

@endsection