
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
                                        <label for="" class="col-sm-2 control-label">商品图片</label>
                                        <div class="col-sm-5" >
                                            <button type="button" class="btn btn-primary upload_logo_button "> 上传图片 </button>
                                            <div class="img_show_content" style="margin-top: 10px;">

                                            </div>
                                            <input id="goods_img" name="goods_img" class="goods_img" type="file" style="display: none">
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

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">是否新品</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_new" class=" is_new" value="0" checked  > 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_new" class=" is_new" value="1" > 是
                                            </label>
                                        </div>

                                        <label for="" class="col-sm-2 control-label">是否热卖</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_hot" class="is_hot" value="0" checked  > 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_hot" class=" is_hot" value="1" > 是
                                            </label>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">是否商户推荐</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_best" class=" is_best" value="0" checked  > 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_best" class=" is_best" value="1" > 是
                                            </label>
                                        </div>

                                        <label for="" class="col-sm-2 control-label">是否上架</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_on_sale" class=" is_on_sale" value="0" checked  > 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_on_sale" class=" is_on_sale" value="1" > 是
                                            </label>
                                        </div>
                                    </div>


                                </div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_description" style="padding-top: 20px" >
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品描述</label>

                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-1 col-sm-10">
                                            <textarea id="goods_desc" class="goods_desc" name="goods_desc"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="nav_member_price">
                                    <div class="form-group" style="text-align: center;color: black;font-weight: 700">
                                        <h4 >
                                            会员价格（如果没有填会员价格就按折扣率计算价格，如果填了就按填的价格算，不再打折）
                                        </h4>
                                    </div>
                                    <?php foreach ($memberLevelData as $k => $v): ?>
                                        <?php  $v = (array)$v; ?>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">
                                                {{$v['level_name']}}（<?php echo $v['rate']/10; ?> 折） ：
                                            </label>
                                            <div class="col-sm-7">
                                                ￥<input type="text" size="10" data-level-id="{{$v['id']}}"  name="mp[{{$v['id']}}]" /> 元
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="nav_goods_attribute">ddd</div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_img" style="padding-top: 20px;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品相册</label>
                                        <div class="col-sm-5" >
                                            <button type="button" class="btn btn-primary upload_photo_button_multi "> 上传相册 </button>
                                            <div class="photo_multi_show_content" style="margin-top: 10px;">

                                            </div>
                                            <input id="photo_multi" name="photo_multi" class="photo_multi" type="file" style="display: none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit"  class=" curSubmit btn btn-primary  btn-lg">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="margin-bottom: 200px;"></div>
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


    <script src="/static/backend/ueditor/ueditor.config.js" type="text/javascript" ></script>
    <script src="/static/backend/ueditor/ueditor.all.min.js" type="text/javascript" ></script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script src="/static/backend/ueditor/lang/zh-cn/zh-cn.js" type="text/javascript" ></script>

    <script>
        var logo_path = '';
        var logo_thumb_path = '';
        var photo_multi_path = [];
        var photo_multi_thumb_path = [];

        /**
         * 切换是否促销
         * @param _this
         */
        function change_promote_type(_this) {
            if ($(_this).is(':checked') ) {
                $('.promote_price').removeAttr('disabled');
            } else {
                $('.promote_price').attr('disabled', 'disabled');
            }
        }
        $(function () {

            var cur_ue = UE.getEditor('goods_desc', {
                "initialFrameWidth" : "100%",   // 宽
                "initialFrameHeight" : 600,      // 高
                "maximumWords" : 10000            // 最大可以输入的字符数量
            });

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
                $('#goods_img').click();
            });
            $('#goods_img').fileupload({
                autoUpload: true,//是否自动上传
                url: "{{ url('backend/goods/addUploadOne')}}",
                dataType: 'json',
                done: function (e, data) {
                    console.log(data);
                    var _result = data.result;
                    console.log(_result);
                    if (_result.code == 200) {
                        var _data = _result.data;
                        logo_path = _data.logo_file_path;
                        logo_thumb_path = _data.logo_file_path_thumb;
                        var _html ="";
                        _html +="<div>";
                        _html +="<a src='javascript:void(0);'data-path='"+_data.logo_file_path+"' data-path-thumb='"+_data.logo_file_path_thumb+"' onclick='delete_logo_img(this);'  >删除</a><br>";
                        _html +="<img class='thumb_img' src='"+_data.logo_file_path+"'/>";
                        _html +="</div>";
                        $('.img_show_content').html(_html);
                    }
                }
            });
            //商品相册，多文件
            $('.upload_photo_button_multi').on('click',function(){
                $('#photo_multi').click();
            });
            $('#photo_multi').fileupload({
                autoUpload: true,//是否自动上传
                url: "{{ url('backend/goods/addUploadMulti')}}",
                dataType: 'json',
                done: function (e, data) {
                    var _result = data.result;
                    console.log(_result);
                    if (_result.code == 200) {
                        var _data = _result.data;
                        photo_multi_path.push(_data.logo_file_path);
                        photo_multi_thumb_path.push(_data.logo_file_path_thumb);
                        var _html ="";
                        _html +="<div style='margin-top: 15px;'>";
                        _html +="<a src='javascript:void(0);'data-path='"+_data.logo_file_path+"' data-path-thumb='"+_data.logo_file_path_thumb+"' onclick='delete_photo_multi(this);'  >删除</a><br>";
                        _html +="<img class='thumb_img' src='"+_data.logo_file_path+"'/>";
                        _html +="</div>";
                        $('.photo_multi_show_content').append(_html);
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
            var cur_logo_path_thumb = $(cur_this).attr('data-path-thumb');
            var url = "<?php echo url('backend/goods/addDeleteImg');?>";
            var _this = cur_this;
            if(confirm('确定要删除吗?')) {
                $.ajax({
                    type: 'get',
                    url:  url,
                    dataType: 'json',
                    data: {
                        cur_logo_path : cur_logo_path,
                        cur_logo_path_thumb : cur_logo_path_thumb,
                    },
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 200) {
                            $(_this).parent().remove();
                            logo_path = '';
                            logo_thumb_path = '';
                        } else {
                            alert(ret.msg);
                            return false;
                        }
                    }
                });
            }
            return false;
        }

        //删除相册
        function delete_photo_multi(cur_this){
            var cur_logo_path = $(cur_this).attr('data-path');
            var cur_logo_path_thumb = $(cur_this).attr('data-path-thumb');
            var url = "<?php echo url('backend/goods/addDeleteImg');?>";
            var _this = cur_this;
            if(confirm('确定要删除吗?')) {
                $.ajax({
                    type: 'get',
                    url:  url,
                    dataType: 'json',
                    data: {
                        cur_logo_path : cur_logo_path,
                        cur_logo_path_thumb : cur_logo_path_thumb,
                    },
                    success: function(ret){
                        console.log(ret);
                        if(ret.code == 200) {
                            $(_this).parent().remove();
                            var photo_big = $.inArray(cur_logo_path,photo_multi_path);
                            if(photo_big > -1) {
                                photo_multi_path.splice(photo_big,1);
                            }
                            var photo_small = $.inArray(cur_logo_path_thumb,photo_multi_thumb_path);
                            if(photo_small > -1) {
                                photo_multi_thumb_path.splice(photo_small,1);
                            }
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
