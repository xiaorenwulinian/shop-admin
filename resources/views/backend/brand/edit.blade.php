
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/static/backend/jquery-file-upload-9.28.0/css/jquery.fileupload.css" rel="stylesheet" type="text/css"/>
    <style>
        .thumb_img{
            width: 200px;
        }
    </style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        商品品牌编辑
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">商品品牌列表</a></small>
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
                        <input type="hidden" name="id" value="{{$brandData['id']}}">
                        <div class="form-group">
                            <label for="brand_name" class="col-sm-2 control-label">品牌名称：</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="brand_name" id="brand_name" value="{{$brandData['brand_name']}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="site_url" class="col-sm-2 control-label">网站地址：</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="site_url" id="site_url" value="{{$brandData['site_url']}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-5" >
                                <button type="button" class="btn btn-primary upload_logo_button "> 上传图片 </button>
                                <div class="img_show_content" style="margin-top: 10px;">
                                    <div>
                                        <a src="javascript:void(0);" data-id="{{$brandData['id']}}" onclick="delete_logo_img(this);">删除</a><br>
                                        <img class="thumb_img" src="{{$brandData['brand_img']}}"/>
                                    </div>
                                </div>
                                <input id="brand_img" name="brand_img" class="brand_img" type="file" style="display: none">
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
    <!--上传图片插件-->
    <script src="/static/backend/jquery-file-upload-9.28.0/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/static/backend/jquery-file-upload-9.28.0/js/jquery.fileupload.js" type="text/javascript"></script>
    <script src="/static/backend/jquery-file-upload-9.28.0/js/jquery.iframe-transport.js" type="text/javascript"></script>

<script>
    var logo_path = "<?php echo $brandData['brand_img'];?>";
    var brand_id = "<?php echo $brandData['id'];?>";

    $(function () {

        //单文件上传
        $('.upload_logo_button').on('click',function(){
            $('#brand_img').click();
        });
        $('#brand_img').fileupload({
            autoUpload: true,//是否自动上传
            url: "{{ url('backend/brand/editUpload') }}",
            dataType: 'json',
            add: function (e,data) {
                $("#brand_img").fileupload(
                    'option',
                    'formData',
                    {'brand_id': brand_id}
                ); // 传参不能放在初始化语句中，否则只能传递参数的初始化值
                data.submit();
            },
            done: function (e, data) {
                console.log(data);
                var _result = data.result;
                if(_result.code == 200) {
                    var _data = _result.data;
                    logo_path = _data.logo_file_path;
                    var _html ="";
                    _html +="<div>";
                    _html +="<a src='javascript:void(0);'data-id='"+brand_id+"' onclick='delete_logo_img(this);'  >删除</a><br>";
                    _html +="<img class='thumb_img' src='"+_data.logo_file_path+"'/>";
                    _html +="</div>";

                    $('.img_show_content').html(_html);
                }
            }
        });


        $('.curSubmit').on('click',function () {

            var brand_name = $('#brand_name').val();
            if (brand_name == '' || brand_name.length == 0) {
                layer.msg("请输入品牌名称!", {icon: 5,time:2000});
                return false;
            }
            var site_url = $('#site_url').val();
            if (site_url == '' || site_url.length == 0) {
                layer.msg("请输入网站地址!", {icon: 5,time:2000});
                return false;
            }
            if (logo_path == '' || brand_name.length == 0) {
                layer.msg("请上传品牌图片!", {icon: 5,time:2000});
                return false;
            }
            var url = "<?php echo url('backend/brand/editStore');?>";
            $.ajax({
                type: 'post',
                url:  url,
                dataType: 'json',
                data: {
                    id : brand_id,
                    brand_name : brand_name,
                    site_url : site_url
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
    // 删除单文件
    function delete_logo_img(cur_this){
        var brand_id = $(cur_this).attr('data-id');
        var _this = cur_this;
        if(confirm('确定要删除吗?')) {
            $.ajax({
                type: 'get',
                url:  "<?php echo url('backend/brand/editDeleteImg');?>",
                dataType: 'json',
                data: {id : brand_id},
                success: function(ret){
                    console.log(ret);
                    if(ret.code == 200) {
                        $(_this).parent().remove();
                        logo_path = '';
                    } else {
                        alert(ret.msg);
                        return false;
                    }
                }
            });
        }
        return false;
    }
</script>
@endsection
