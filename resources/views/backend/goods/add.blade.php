
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/static/backend/jquery-file-upload-9.28.0/css/jquery.fileupload.css" rel="stylesheet" type="text/css"/>

    <!--时间插件样式-->
    <link href="/static/backend/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

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
        商品添加
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">商品列表</a></small>

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

                        <div>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#nav_base_info" aria-controls="home" role="tab" data-toggle="tab">基本信息</a></li>
                                <li role="presentation"><a href="#nav_goods_description" aria-controls="profile" role="tab" data-toggle="tab">商品描述</a></li>
                                <li role="presentation"><a href="#nav_member_price" aria-controls="messages" role="tab" data-toggle="tab">会员价格</a></li>
                                <li role="presentation"><a href="#nav_goods_attribute" aria-controls="settings" role="tab" data-toggle="tab">商品属性</a></li>
                                <li role="presentation"><a href="#nav_goods_img" aria-controls="settings" role="tab" data-toggle="tab">商品相册</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div style="margin: 20px 0;"></div>
                                <div role="tabpanel" class="tab-pane active" id="nav_base_info">
                                    <div class="form-group">
                                        <label for="goods_name" class="col-sm-2 control-label">商品名称：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="goods_name" id="goods_name">
                                        </div>
                                        <label for="" class="col-sm-2 control-label">品牌：</label>
                                        <div class="col-sm-3">
                                            <select name="type_id" id="type_id" class="form-control">
                                                <option value="0">选择品牌</option>
                                                <?php foreach($brandData as $k=>$v): ?>
                                                <?php $v = (array)$v;?>
                                                <option value="{{$v['id']}}">{{$v['brand_name']}}</option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品分类：</label>
                                        <div class="col-sm-3">
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="0">选择分类</option>
                                                <?php foreach($categoryData as $k=>$v):?>
                                                <option value="{{$v['id']}}">
                                                    <?php echo str_repeat('-', 8*$v['level']).$v['category_name']; ?>
                                                </option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">扩展分类：</label>
                                        <div class="col-sm-3">
                                            <div style="margin: 5px 0;">
                                                <input onclick="$(this).parent().append($(this).next('select').clone());" type="button" value="添加" class=" btn btn-success" />
                                                <select name="ext_cat_id[]" id="ext_cat_id" class="form-control" style="margin: 5px 0">
                                                    <option value="0">选择分类</option>
                                                    <?php foreach($categoryData as $k=>$v):?>
                                                    <option value="{{$v['id']}}">
                                                        <?php echo str_repeat('-', 8*$v['level']).$v['category_name']; ?>
                                                    </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="market_price" class="col-sm-2 control-label">市场价(元)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="market_price" id="market_price" value="0.00">
                                        </div>
                                        <label for="shop_price" class="col-sm-2 control-label">本店价(元)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="shop_price" id="shop_price" value="0.00">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jifen" class="col-sm-2 control-label">赠送积分(不填和商品价格相同)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jifen" id="jifen" value="0">
                                        </div>

                                        <label for="jyz" class="col-sm-2 control-label">赠送经验值：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jyz" id="jyz" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jifen_price" class="col-sm-2 control-label">积分兑换，需要的积分数:（不填代表不能使用积分兑换）</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jifen_price" id="jifen_price" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">
                                            <input value="1" name="is_promote" onclick="change_promote_type(this)"  type="checkbox" />促销价：
                                        </label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price" disabled="disabled" name="promote_price" id="promote_price" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">促销开始时间：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price" disabled="disabled"  name="promote_start_time" id="promote_start_time" >
                                        </div>
                                        <label for="" class="col-sm-2 control-label">促销结束时间：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price" disabled="disabled"  name="promote_end_time" id="promote_end_time" >
                                        </div>
                                    </div>


                                </div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_description">bb</div>
                                <div role="tabpanel" class="tab-pane" id="nav_member_price">cc</div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_attribute">ddd</div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_img">eee</div>
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

    <!--时间插件-->
    <script src="/static/backend/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <script src="/static/backend/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js" type="text/javascript" ></script>


    <!--上传图片插件-->
    <script src="/static/backend/jquery-file-upload-9.28.0/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/static/backend/jquery-file-upload-9.28.0/js/jquery.fileupload.js" type="text/javascript"></script>
    <script src="/static/backend/jquery-file-upload-9.28.0/js/jquery.iframe-transport.js" type="text/javascript"></script>

    <script>
        logo_path = '';
        function change_promote_type(_this) {
            if ($(_this).is(':checked') ) {
                $('.promote_price').removeAttr('disabled');
            } else {
                $('.promote_price').attr('disabled', 'disabled');
            }
        }
        $(function () {
            $("#promote_start_time").datetimepicker({
                format: 'yyyy-mm-dd',
                // format: 'yyyy-mm-dd hh:ii:ss', 年月日时分秒，搜索不需要时分秒
                language : 'zh-CN',
                autoclose: 1,
                todayBtn: 1,
                minView:'month',
            });

            $("#promote_end_time").datetimepicker({
                format: 'yyyy-mm-dd',
                // format: 'yyyy-mm-dd hh:ii:ss', 年月日时分秒，搜索不需要时分秒
                language : 'zh-CN',
                autoclose: 1,
                todayBtn: 1,
                minView:'month',
            });


            //单文件上传
            $('.upload_logo_button').on('click',function(){
                $('#brand_img').click();
            });
            $('#brand_img').fileupload({
                autoUpload: true,//是否自动上传
                url: "{{ url('backend/brand/addUpload')}}",
                dataType: 'json',
                done: function (e, data) {
                    console.log(data);
                    var _result = data.result;
                    if(_result.code == 200) {
                        var _data = _result.data;
                        logo_path = _data.logo_file_path;

                        var _html ="";
                        _html +="<div>";
                        _html +="<a src='javascript:void(0);'data-path='"+_data.logo_file_path+"' data-path-thumb='"+_data.logo_file_path+"' onclick='delete_logo_img(this);'  >删除</a><br>";
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

                var url = "<?php echo url('backend/brand/addStore');?>";
    //            var form_param = $('#formSubmit').serialize();
                $.ajax({
                    type: 'post',
                    url:  url,
                    dataType: 'json',
                    data: {
                        brand_name : brand_name,
                        site_url : site_url,
                        brand_img : logo_path,
                    },
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 200) {
                            layer.msg('添加成功',{
                                time:1000,
                                icon: 6,
                                end:function () {
    //                               location.reload();
                                    location.href = "<?php echo url('backend/brand/lst');?>";
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

        //删除单 图片
        function delete_logo_img(cur_this){
            var cur_logo_path = $(cur_this).attr('data-path');
            var url = "<?php echo url('backend/brand/addDeleteImg','',false);?>";
            var _this = cur_this;
            if(confirm('确定要删除吗?')) {
                $.ajax({
                    type: 'get',
                    url:  url,
                    dataType: 'json',
                    data: {
                        cur_logo_path : cur_logo_path,
                    },
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 200) {
                            $(_this).parent().remove();
                            logo_path = '';
                        } else {
                            alert(ret.msg);
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.msg(XMLHttpRequest.responseJSON.msg, {icon: 5,time:1000,});
                        return false;

                    }
                });
            }
            return false;
        }
    </script>
@endsection