
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        商品属性编辑
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">商品属性列表</a></small>
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
                        <input type="hidden" name="id" value="{{$attributeData['id']}}">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">属性分类：</label>
                            <div class="col-sm-9">
                                <select name="type_id" id="type_id" class="form-control" disabled="disabled">
                                    <option value="0">选择分类</option>
                                    <?php foreach($typeData as $k=>$v): $v = (array)$v;?>
                                    <option value="{{$v['id']}}"  <?php echo $v['id'] == $attributeData['type_id'] ? 'selected="selected"' : '';?> >
                                        <?php echo $v['type_name']; ?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attr_name" class="col-sm-2 control-label">属性名称：</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="attr_name" id="attr_name" value="{{$attributeData['attr_name']}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">属性的类型：</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="attr_type" class="attr_type" disabled="disabled" value="1" <?php echo $attributeData['attr_type'] == 1 ? 'checked="checked"': ''; ?> />规格属性
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="attr_type" class="attr_type" disabled="disabled" value="2" <?php echo $attributeData['attr_type'] == 2 ? 'checked="checked"': ''; ?> />销售属性
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attr_option_values" class="col-sm-2 control-label">属性的可选值：<br/>(销售属性只可添加，不能减少)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" name="attr_option_values" id="attr_option_values">{{$attributeData['attr_option_values']}}</textarea>
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

        var attr_id = "<?php echo $attributeData['id'];?>";
        $('.curSubmit').on('click',function () {



            var type_id = $('#type_id').val();
            if (type_id == '' || type_id.length == 0) {
                layer.msg("请选择属性类型!", {icon: 5,time:2000});
                return false;
            }

            var attr_name = $.trim($('#attr_name').val());
            if (attr_name == '' || attr_name.length == 0) {
                layer.msg("请输入属性名称!", {icon: 5,time:2000});
                return false;
            }
            var attr_type = $("input[name='attr_type']:checked").val();
            var attr_option_values = $.trim($('#attr_option_values').val());
            if (attr_option_values == ''  && attr_type == 2) {
                alert("销售属性必须有属性值");
                return false;
            }
            var url = "<?php echo url('backend/attribute/editStore');?>";
            $.ajax({
                type: 'post',
                url:  url,
                dataType: 'json',
                data: {
                    id : attr_id,
                    type_id : type_id,
                    attr_name : attr_name,
                    attr_type : attr_type,
                    attr_option_values : attr_option_values,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(ret){
                    console.log(ret);
                    if(ret.code == 200) {
                        layer.msg(ret.msg,{
                            time:1000,
                            icon: 6,
                            end:function () {
                                window.location.href = document.referrer;
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
