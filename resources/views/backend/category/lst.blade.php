@extends('backend.layout.basic')

@section('style')
    <style>

    </style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        文章分类列表
        <small> <a href="{{url('/backend/category/add')}}" class="pull-right">添加文章分类</a></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
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
                        <tr>
                            <th>id</th>
                            <th>parent_id</th>
                            <th>title</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($cateData as $v):?>
                        <tr>
                            <td>{{$v['id']}}</td>
                            <td>{{$v['parent_id']}}</td>
                            <td><?php echo str_repeat('-', 8*$v['level']); ?>{{$v['title']}}</td>
                            <td>
                                <a href="<?php echo url('/backend/category/edit?id='.$v['id']); ?>" title="编辑">编辑</a> |
                                <a href="javascript:void(0)" data-id="{$v['id']}" class="cur_del" title="移除">删除</a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
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

<script>
    $(function () {
        $('.cur_del').on('click',function () {
            var id = $(this).attr('data-id');
            layer.confirm('确定要删除吗？', {
                btn: ['确定','取消']
            }, function(){
                var url = "<?php echo url('backend/category/delete');?>";
                $.ajax({
                    type: 'post',
                    url:  url,
                    dataType: 'json',
                    data: {id:id},
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 0) {
                            layer.msg('删除成功',{
                                time:1000, icon: 6,
                                end:function () {
                                    location.href = location.href;
                                }
                            })
                        } else {
                            layer.msg(ret.msg, {icon: 5,time:3000,});
                            return false;
                        }
                    }
                });
            }, function(){
            });
            return false;
        });
        $('.edit_sort_id').on('blur',function () {
            var id = $(this).attr('data-id');
            var sort_id = $(this).val();
            var reg =  /^\d+$/;
            if (!(reg.test(sort_id))) {
                layer.msg("排序请输入正整数!", {icon: 5,time:2000});
                return false;
            }
            var url = "<?php echo url('flowerUse/edit_sort','',false);?>";
            $.ajax({
                type: 'get',
                url:  url,
                dataType: 'json',
                data: {
                    id : id,
                    sort_id : parseInt(sort_id),
                },
                success: function(ret){
                    console.log(ret);
                    if(ret.code == 0) {
                        layer.msg('修改成功',{
                            time:1000, icon: 6,
                            end:function () {
                                location.href = location.href;
                            }
                        })
                    } else {
                        layer.msg(ret.msg, {icon: 5,time:1000,});
                        return false;
                    }
                }
            });
            return false;
        });
    })
</script>
@endsection

