
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            商品分类编辑
            <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">商品分类列表</a></small>

        </h1>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" id="formSubmit" action="">
                            <input type="hidden" name="id" value="{{$cateData['id']}}">

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">所属分类：</label>
                                <div class="col-sm-9">
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="0">顶级分类</option>
                                        <?php foreach($parentData as $k=>$v):?>

                                        <?php if($v['id'] == $cateData['id'] || in_array($v['id'], $children)) continue ; ?>

                                        <option value="{{$v['id']}}" <?php if($v['id'] == $cateData['parent_id'] ) echo "selected='selected'";?>>
                                            <?php echo str_repeat('-', 8*$v['level']).$v['category_name']; ?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">分类名称：：</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="category_name" id="category_name" value="{{$cateData['category_name']}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit"  class=" curSubmit btn btn-primary  btn-lg">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div style="margin-bottom: 100px;"></div>
@endsection

@section('script')

    <script>
        $(function () {
            var cate_id = "<?php echo $cateData['id'];?>";
            $('.curSubmit').on('click',function () {
                var parent_id = $('#parent_id').val();
                if (parent_id == '' ) {
                    layer.msg("请选择所属类别!", {icon: 5,time:2000});
                    return false;
                }

                var category_name = $('#category_name').val();
                if (category_name == '' || category_name.length == 0) {
                    layer.msg("请输入分类名称!", {icon: 5,time:2000});
                    return false;
                }
                var url = "<?php echo url('backend/goodsCategory/editStore');?>";
                $.ajax({
                    type: 'post',
                    url:  url,
                    dataType: 'json',
                    data: {
                        id : cate_id,
                        parent_id : parent_id,
                        category_name : category_name,
                    },
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 200) {
                            layer.msg('编辑成功',{
                                time:1000,
                                icon: 6,
                                end:function () {
                                    location.href = "<?php echo url('backend/goodsCategory/lst');?>";
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