
@extends('backend.layout.basic')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        会员级别添加
        <small> <a href="javascript:void(0);" onclick="window.history.back();" class="pull-right">会员级别列表</a></small>

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
                            <label for="level_name" class="col-sm-2 control-label">级别名称：</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="level_name" id="level_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bottom_num" class="col-sm-2 control-label">积分下限：</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="bottom_num" id="bottom_num">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="top_num" class="col-sm-2 control-label">积分上限：</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="top_num" id="top_num">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rate" class="col-sm-2 control-label">优惠比率(95折，输入95)：</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control" name="rate" id="rate">
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

            $('.curSubmit').on('click',function () {

                var level_name = $('#level_name').val();
                if (level_name == '' || level_name.length == 0) {
                    alert('请输入级别名称');
                    return false;
                }
                var reg = /^\d{1,10}$/;
                var bottom_num = $('#bottom_num').val();
                if (!reg.test(bottom_num)) {
                    alert('请输入正确的积分下限格式!');
                    return false;
                }
                var top_num = $('#top_num').val();
                if (!reg.test(top_num)) {
                    alert('请输入正确的积分上限格式!');
                    return false;
                }
                var rate = $('#rate').val();
                if (!reg.test(rate)) {
                    alert('请输入正确的比率!');
                    return false;
                }
                var url = "<?php echo url('backend/memberLevel/addStore');?>";
                $.ajax({
                    type: 'post',
                    url:  url,
                    dataType: 'json',
                    data: {
                        level_name : level_name,
                        bottom_num : bottom_num,
                        top_num : top_num,
                        rate : rate,
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
                                    location.href = "<?php echo url('backend/memberLevel/lst');?>";
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