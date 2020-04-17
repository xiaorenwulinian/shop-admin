
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        文章添加
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">文章列表</a></small>

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

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">所属分类：</label>
                            <div class="col-sm-9">
                                <select name="type_id" id="type_id" class="form-control">
                                    <option value="0">选择分类</option>
                                    <?php foreach($cateData as $k=>$v):?>
                                    <option value="{{$v['id']}}">
                                        <?php echo str_repeat('-', 8*$v['level']).$v['title']; ?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="art_title" class="col-sm-2 control-label">文章标题：</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="art_title" id="art_title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="art_desc" class="col-sm-2 control-label">文章描述：</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" name="art_desc" id="art_desc"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">文章内容</label>
                            <div class="col-sm-9">
                                <textarea id="art_content" name="art_content"></textarea>
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
    <script src="/static/backend/ueditor/ueditor.config.js" type="text/javascript" ></script>
    <script src="/static/backend/ueditor/ueditor.all.min.js" type="text/javascript" ></script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script src="/static/backend/ueditor/lang/zh-cn/zh-cn.js" type="text/javascript" ></script>
    <script>
        $(function () {

            var cur_ue = UE.getEditor('art_content', {
                "initialFrameWidth" : "100%",   // 宽
                "initialFrameHeight" : 600,      // 高
                "maximumWords" : 10000            // 最大可以输入的字符数量
            });


            $('.curSubmit').on('click',function () {
                var type_id = $('#type_id').val();
                if (type_id == '' || type_id == 0) {
                    layer.msg("请选择所属类别!", {icon: 5,time:2000});
                    return false;
                }

                var art_title = $('#art_title').val();
                if (art_title == '' || art_title.length == 0) {
                    layer.msg("请输入文章标题!", {icon: 5,time:2000});
                    return false;
                }
                var art_desc = $('#art_desc').val();
                var art_content = cur_ue.getContent(); // UEditor 内容
                var url = "<?php echo url('backend/article/addStore');?>";
    //            var form_param = $('#formSubmit').serialize();
                $.ajax({
                    type: 'post',
                    url:  url,
                    dataType: 'json',
                    data: {
                        type_id : type_id,
                        art_title : art_title,
                        art_desc : art_desc,
                        art_content : art_content
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
                                    location.href = "<?php echo url('backend/article/lst');?>";
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