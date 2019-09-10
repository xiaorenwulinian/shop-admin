
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
        商品编辑
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
                        <input type="hidden" name="id" value="{{$goodsData['id']}}">
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
                                            <input type="text" class="form-control" name="goods_name" id="goods_name" value="{{$goodsData['goods_name']}}">
                                        </div>
                                        <label for="" class="col-sm-2 control-label">品牌：</label>
                                        <div class="col-sm-3">
                                            <select name="brand_id" id="brand_id" class="form-control">
                                                <option value="">选择品牌</option>
                                                <?php foreach($brandData as $k=>$v): ?>
                                                    <?php $v = (array)$v;?>
                                                    <option value="{{$v['id']}}" <?php echo $v['id'] == $goodsData['brand_id'] ? 'selected="selected"' : '';?> >
                                                        {{$v['brand_name']}}
                                                    </option>
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
                                                <option value="{{$v['id']}}" <?php echo $v['id'] == $goodsData['category_id'] ? 'selected="selected"' : '';?>>
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

                                                <?php if (count($goodsExtCateIds) == 0):?>
                                                    <select name="ext_cat_id[]" id="" class="form-control" style="margin: 5px 0">
                                                        <option value="">选择分类</option>
                                                        <?php foreach($categoryData as $k=>$v):?>
                                                        <option value="{{$v['id']}}" >
                                                            <?php echo str_repeat('-', 8*$v['level']).$v['category_name']; ?>
                                                        </option>
                                                        <?php endforeach;?>
                                                    </select>
                                                <?php else:?>
                                                    <?php foreach ($goodsExtCateIds as $cateId ) :?>
                                                    <select name="ext_cat_id[]" id="" class="form-control" style="margin: 5px 0">
                                                    <option value="">选择分类</option>
                                                    <?php foreach($categoryData as $k=>$v):?>
                                                        <option value="{{$v['id']}}" <?php echo $v['id'] == $cateId ? 'selected="selected"' : '';?> >
                                                            <?php echo str_repeat('-', 8*$v['level']).$v['category_name']; ?>
                                                        </option>
                                                    <?php endforeach;?>
                                                </select>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品图片</label>
                                        <div class="col-sm-5" >
                                            <button type="button" class="btn btn-primary upload_logo_button "> 上传图片 </button>
                                            <div class="img_show_content" style="margin-top: 10px;">
                                                <div>
                                                    <a src="javascript:void(0);" data-id="{{$goodsData['id']}}" onclick="delete_logo_img(this);">删除</a><br>
                                                    <img class="thumb_img" src="{{$goodsData['goods_img']}}"/>
                                                </div>
                                            </div>
                                            <input id="goods_img" name="goods_img" class="goods_img" type="file" style="display: none">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="market_price" class="col-sm-2 control-label">市场价(元)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="market_price" id="market_price" value="{{$goodsData['market_price']}}">
                                        </div>
                                        <label for="shop_price" class="col-sm-2 control-label">本店价(元)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="shop_price" id="shop_price" value="{{$goodsData['shop_price']}}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jifen" class="col-sm-2 control-label">赠送积分(不填和商品价格相同)：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jifen" id="jifen" value="<?php echo $goodsData['jifen'] ?? 0;?>">
                                        </div>

                                        <label for="jyz" class="col-sm-2 control-label">赠送经验值：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jyz" id="jyz" value="<?php echo $goodsData['jifen'] ?? 0;?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jifen_price" class="col-sm-2 control-label">积分兑换，需要的积分数:（不填代表不能使用积分兑换）</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="jifen_price" id="jifen_price" value="<?php echo $goodsData['jifen'] ?? 0;?>">
                                        </div>
                                    </div>

                                    <div class="form-group">

                                        <label for="" class="col-sm-2 control-label">是否促销</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_promote" class="is_promote" value="0" onclick="change_promote_type(this)"
                                                    <?php echo $goodsData['is_promote'] == 0 ? 'checked="checkde"' : '' ?>  > 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_promote" class=" is_promote" value="1" onclick="change_promote_type(this)"
                                                    <?php echo $goodsData['is_promote'] == 1 ? 'checked="checkde"' : '' ?> > 是
                                            </label>
                                        </div>
                                        <label for="" class="col-sm-2 control-label">促销价：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price" name="promote_price" id="promote_price"
                                                   <?php if ($goodsData['is_promote'] == 0) echo 'disabled="disabled"' ;?>
                                                   value="<?php echo $goodsData['promote_price'] ?? 0;?>" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">促销开始时间：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price"   name="promote_start_time" id="promote_start_time"
                                                   <?php if ($goodsData['is_promote'] == 0) echo 'disabled="disabled"' ;?>
                                                   value="<?php echo $goodsData['promote_start_time'] ? date('Y-m-d',$goodsData['promote_start_time']) : '';?>" />
                                        </div>
                                        <label for="" class="col-sm-2 control-label">促销结束时间：</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control promote_price"   name="promote_end_time" id="promote_end_time"
                                                   <?php if ($goodsData['is_promote'] == 0) echo 'disabled="disabled"' ;?>
                                                   value="<?php echo $goodsData['promote_end_time'] ? date('Y-m-d',$goodsData['promote_end_time']) : '';?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">是否新品</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_new" class=" is_new" value="0" <?php echo $goodsData['is_new'] == 0 ? 'checked="checkde"' : '' ?>  /> 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_new" class=" is_new" value="1"  <?php echo $goodsData['is_new'] == 1 ? 'checked="checkde"' : '' ?>  /> 是
                                            </label>
                                        </div>

                                        <label for="" class="col-sm-2 control-label">是否热卖</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_hot" class="is_hot" value="0" <?php echo $goodsData['is_hot'] == 0 ? 'checked="checkde"' : '' ?>  /> 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_hot" class=" is_hot" value="1" <?php echo $goodsData['is_hot'] == 1 ? 'checked="checkde"' : '' ?>  /> 是
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">是否商户推荐</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_best" class=" is_best" value="0" <?php echo $goodsData['is_best'] == 0 ? 'checked="checkde"' : '' ?>  /> 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_best" class=" is_best" value="1" <?php echo $goodsData['is_best'] == 1 ? 'checked="checkde"' : '' ?>  /> 是
                                            </label>
                                        </div>

                                        <label for="" class="col-sm-2 control-label">是否上架</label>
                                        <div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="is_on_sale" class=" is_on_sale" value="0" <?php echo $goodsData['is_on_sale'] == 0 ? 'checked="checkde"' : '' ?>  /> 否
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_on_sale" class=" is_on_sale" value="1" <?php echo $goodsData['is_on_sale'] == 1 ? 'checked="checkde"' : '' ?>  /> 是
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">seo优化_关键字</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="seo_keyword" id="seo_keyword" value="{{$goodsData['seo_keyword']}}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">seo优化_描述</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="seo_description" id="seo_description" value="{{$goodsData['seo_description'] }}" />
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_description" style="padding-top: 20px" >
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品描述</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-1 col-sm-10">
                                            <textarea id="goods_desc" class="goods_desc" name="goods_desc">{{$goodsData['goods_desc']}}</textarea>
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
                                                ￥<input type="text" size="10" data-level-id="{{$v['id']}}"  name="mp[{{$v['id']}}]"
                                                    value="<?php echo isset($memberPriceData[$v['id']]) ? $memberPriceData[$v['id']] : '' ;?>" /> 元
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="nav_goods_attribute">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品类型：</label>
                                        <div class="col-sm-3">
                                            <select name="type_id" id="type_id" class="form-control">
                                                <option value="">选择类型</option>
                                                <?php foreach($typeData as $k=>$v): ?>
                                                <?php $v = (array)$v;?>
                                                <option value="{{$v['id']}}" <?php echo $v['id'] == $goodsData['type_id'] ? 'selected="selected"' : ''?>>{{$v['type_name']}}</option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="attr_container">
                                        <?php
                                            // 该商品已经存在的属性ID
                                            $hasAttrId = [];

                                        ?>
                                        <?php foreach ($goodsAttrArr as $k => $v):?>
                                            <div class='form-group'>
                                                <label class='col-sm-2 control-label'><?php echo $v['attr_name'];?></label>
                                                <div class='col-sm-3'>
                                                     <?php
                                                        if ($v['attr_type'] == 1) :
                                                            if (!in_array($v['attr_id'], $hasAttrId)) {
                                                                $operator = "[+]";
                                                                $hasAttrId[] = $v['attr_id'];
                                                            } else {
                                                                $operator = "[-]";
                                                            }
                                                     ?>
                                                        <a data-good-attr-id="{{$v['id']}}" onclick='addnew(this);' href='javascript:void(0);'>{{$operator}}</a>
                                                     <?php endif;?>

                                                     <?php
                                                         $old_ = isset($v['attr_value']) && !empty($v['attr_value']) ? 'old_' : '';  // 是否设置过这个属性，已经设置过，该属性前添加 old_ 进行区分
                                                     ?>
                                                     <?php
                                                         // 判断有没有可选值，如果有就是下拉框，否则是文本框
                                                         if (!$v['attr_option_values']) :?>
                                                            <input type='text' class='form-control'  data-attr-type-value='0'  data-attr-id="<?php echo $v['attr_id']?>"
                                                                   name="{{$old_}}ga['{{$v['attr_id']}}'][{{$v['id']}}]" value="{{$v['attr_value']}}" />
                                                     <?php
                                                         else:
                                                             $attr_option_values_arr = explode(',',$v['attr_option_values'])
                                                      ?>
                                                            <select name="{{$old_}}ga['{{$v['attr_id']}}'][$v['id']]" class='form-control' data-attr-type-value='1' data-attr-id={{$v['attr_id']}} >
                                                                <option value="">请选择</option>
                                                                <?php foreach ($attr_option_values_arr as $attr_option_values_one):;?>
                                                                    <?php
                                                                        if (isset($v['attr_value']) && $v['attr_value'] == $attr_option_values_one) {
                                                                            $selected =  'selected="selected"';
                                                                        } else {
                                                                            $selected = '';
                                                                        }
                                                                        ?>
                                                                    <option value="{{$attr_option_values_one}}"  {{$selected}} >
                                                                        {{$attr_option_values_one}}
                                                                    </option>
                                                                <?php endforeach;?>
                                                            </select>
                                                     <?php endif;?>
                                                    <?php if($v['attr_type'] == 1):?>
                                                         属性价格(元)：
                                                         <input class='form-control' data-attr-id={{$v['attr_id']}}  name="old_attr_price['{{$v['attr_id']}}']['{{$v['id']}}']" type='text' value="{{$v['attr_price']}}" />
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="nav_goods_img" style="padding-top: 20px;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">商品相册</label>
                                        <div class="col-sm-5" >
                                            <button type="button" class="btn btn-primary upload_photo_button_multi "> 上传相册 </button>
                                            <div class="photo_multi_show_content" style="margin-top: 10px;">
                                                <?php foreach($goodsExtImgData as $extImg):?>
                                                    <div>
                                                        <a src="javascript:void(0);" data-id="{{$extImg['id']}}"  onclick='delete_photo_multi(this);'  >
                                                            删除
                                                        </a><br>
                                                        <img class="thumb_img" src="{{$extImg['goods_ext_img']}} "/>
                                                    </div>
                                                <?php endforeach;?>
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
    var logo_path = "<?php echo $goodsData['goods_img'];?>";
    var logo_thumb_path = "<?php echo $goodsData['goods_thumb_img'];?>";

    var goods_id = "<?php echo $goodsData['id'];?>";

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
            url: "{{ url('backend/goods/editUpload') }}",
            dataType: 'json',
            add: function (e,data) {
                $("#goods_img").fileupload(
                    'option',
                    'formData',
                    {'goods_id': goods_id}
                ); // 传参不能放在初始化语句中，否则只能传递参数的初始化值
                data.submit();
            },
            done: function (e, data) {
                console.log(data);
                var _result = data.result;
                if(_result.code == 200) {
                    var _data = _result.data;
                    logo_path = _data.logo_file_path;
                    var _html = "";
                    _html += "<div>";
                    _html +=    "<a src='javascript:void(0);'data-id='"+goods_id+"' onclick='delete_logo_img(this);'  >删除</a><br>";
                    _html +=    "<img class='thumb_img' src='"+_data.logo_file_path+"'/>";
                    _html += "</div>";

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

    // 点击+号
    function addnew(a) {
        var p = $(a).parent();  // 选中a标签所在的p标签
        // 先获取A标签中的内容
        if($(a).html() == "[+]") {
            var newP = p.clone();  // 把p克隆一份
            newP.find("a").html("[-]");  // 把克隆出来的P里面的a标签变成-号
            p.after(newP); // 放在后面
        }
        else {
            p.remove();
        }
    }


    /**
     * 切换是否促销
     * @param _this
     */
    function change_promote_type(_this) {
        if ($(_this).val() == 1 ) {
            $('.promote_price').removeAttr('disabled');
        } else {
            $('.promote_price').attr('disabled', 'disabled');
        }
    }
</script>
@endsection
