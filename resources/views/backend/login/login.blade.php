<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/static/backend/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/backend/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/backend/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/backend/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>后台管理中心</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">填写登录</p>
        <p style="color: red" class="error_msg"></p>
        <form action="" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control username" name="username" placeholder="用户名">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control password" name="password" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat curSubmit">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="/static/backend/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/static/backend/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
{{--<script src="../../plugins/iCheck/icheck.min.js"></script>--}}
<script>
    $(function () {

        $('.curSubmit').on('click',function () {
            $('.error_msg').text();
            var username = $('.username').val();
            var password = $('.password').val();
            var verify = $('.verify').val();
            if (username == '' || username.length==0) {
                $('.error_msg').text('请输入用户名!');
                return false;
            }
            if (password == '') {
                $('.error_msg').text('请输入密码!');
                return false;
            }
            var url = "<?php echo url('backend/loginSubmit','',false);?>";
            $.ajax({
                type: 'post',
                url:  url,
                dataType: 'json',
                data: {
                    username: username,
                    password: password,
                    password: password,
                },
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(ret){
                    console.log(ret);
                    if(ret.code == 200) {
                        top.location.href = "<?php echo url('backend');?>";
                    } else {
                        $('.error_msg').text(ret.msg);
                        return false;
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('.error_msg').text(XMLHttpRequest.responseJSON.msg);
                    return false;
                }
            });
            return false;
        });
    });
</script>
</body>
</html>
