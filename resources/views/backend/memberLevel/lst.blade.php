
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
    <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
       会员级别列表
        <small> <a href="{{url('backend/memberLevel/add')}}" class="pull-right">添加会员级别</a></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="common_search">
        <form class="form-inline" action="{{url('backend/memberLevel/lst')}}" id="form_search">
            <input type="text" class="form-control" name="level_name" placeholder="级别名称" value="{{request('level_name','')}}">
            <input  type="hidden" name="page_size" id="cur_show_page" value="{{$page_size}}"/>
            <input class="btn btn-flat btn-primary" type="submit" value="搜索">
            <input class="btn btn-flat btn-warning multi_del" type="button" value="批量删除">
        </form>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box" >
                <div class="box-header">
                    <h3 class="box-title">
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr >
                            <th style="text-align: center"> <input type="checkbox" name="is_multi_select"  class="is_multi_select"> </th>
                            <th style="text-align: center">id</th>
                            <th style="text-align: center">级别名称</th>
                            <th style="text-align: center">积分下限</th>
                            <th style="text-align: center">积分上限</th>
                            <th style="text-align: center">优惠比率</th>
                            <th style="text-align: center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($list as $v):?>
                        <tr  style="width: 40px;text-align: center">
                            <td><input type="checkbox" class="multi_select_input" name="multi_select[]" value="{{$v->id}}"/></td>
                            <td>{{$v->id}}</td>
                            <td>{{$v->level_name}}</td>
                            <td>{{$v->bottom_num}}</td>
                            <td>{{$v->top_num}}</td>
                            <td>{{$v->rate}}</td>
                            <td>
                                <a href="<?php echo url("backend/memberLevel/edit?id={$v->id}"); ?>" title="编辑">编辑</a> |
                                <a href="javascript:void(0)" data-id="{{$v->id}}" class="cur_del" title="移除">删除</a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <div>
                        {{$list->links()}}
                        {!! $page_size_select !!}
                    </div>

                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection

@section('script')
<script src="/static/backend/dist/js/admin_common.js" type="text/javascript" ></script>

<script>
    $(function () {
        /**
         * 删除单个
         */
        $('.cur_del').on('click',function () {
            var id = $(this).attr('data-id');
            var url = "<?php echo url('backend/memberLevel/delete');?>";
            delete_one(url, id);
        });

        $('.is_multi_select').on('click',function () {
            if ($(this).is(':checked')) {
                $("input[name='multi_select[]']").each(function () {
                    $(this).prop("checked","checked");
                });
            } else {
                $("input[name='multi_select[]']").each(function () {
                    $(this).prop('checked',false);
                });
            }
        });

        /**
         * 批量删除
         */
        $('.multi_del').on('click',function () {
            var has_checked  = [];
            $("input[name='multi_select[]']:checked").each(function () {
                has_checked.push($(this).val());
            });
            var has_checked_str = has_checked.join(',');
            if (has_checked_str == '' || has_checked_str.length == 0) {
                alert('请勾选删除项！');
                return false;
            }
            var url = "<?php echo url('backend/memberLevel/multiDelete');?>";

            delete_multiply(url, has_checked_str);

        });

        // 修改每页显示数量
        $(".page_size_select").on('change',function() {
            var page_size = $(this).val();
            $('#cur_show_page').val(page_size);
            $('#form_search').trigger('submit');
            return false;

        });


    })
</script>

@endsection
