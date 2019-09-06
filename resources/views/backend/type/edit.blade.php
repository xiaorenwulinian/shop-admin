
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        属性分类编辑
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">属性分类列表</a></small>
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
                        <input type="hidden" name="id" value="{{$typeData['id']}}">


                        <div class="form-group">
                            <label for="type_name" class="col-sm-2 control-label">文章标题：</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="type_name" id="type_name" value="{{$typeData['type_name']}}">
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

        var type_id = "<?php echo $typeData['id'];?>";
        $('.curSubmit').on('click',function () {

            var type_name = $('#type_name').val();
            if (type_name == '' || type_name.length == 0) {
                layer.msg("请输入分类名称!", {icon: 5,time:2000});
                return false;
            }
            var url = "<?php echo url('backend/type/editStore');?>";
            $.ajax({
                type: 'post',
                url:  url,
                dataType: 'json',
                data: {
                    id : type_id,
                    type_name : type_name,

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
                                window.history.back();
                              // location.reload();
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
