<?php

namespace App\Http\Controllers\Lclapi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller

{
    /**
     * 登录
     */
    public function login(Request $request)
    {

       $phone = $request->input('phone');
       $pwd   = $request->input('pwd');
       if (!preg_match('/^1[3-9]\d{9}$/',$phone)) {
            return res_fail([],'请输入正确的手机号码');
       }
       if (empty($pwd)) {
           return res_fail('请输入密码');
       }
        $appUser = DB::table('app_user')->where('phone','=',$phone)->first();
        if (empty($appUser)) {
            return res_fail('手机号码不存在！');
        }
        $isValidate = Hash::check($pwd,$appUser->password);
        if (!$isValidate) {
            return res_fail('密码错误');
        }

       return res_success([],'login ok');
    }

    /**
     * 注册
     * @param Request $request
     * @return false|string
     */
    public function register(Request $request)
    {
        $phone = $request->input('phone');
        $pwd   = $request->input('pwd');
        if (!preg_match('/^1[3-9]\d{9}$/',$phone)) {
            return res_success('请输入正确的手机号码');
        }
        if (empty($pwd)) {
            return res_fail(' 请输入密码！');
        }
        $isExist = DB::table('app_user')->where('phone','=',$phone)->count();
        if ($isExist > 0) {
            return res_fail('手机号码已存在');
        }
        $data = [
            'phone'      => $phone,
            'password'   => Hash::make($pwd),
            'name'       => 'lcl',
            'created_at' => date('Y-m-d H:i:s'),
            'login_ip'   => ip2long($request->getClientIp()),
        ];
        $id = DB::table('app_user')->insertGetId($data);
        $ret = ['req_param'=>$request->all(),'newId'=>$id];
        return res_success($ret,'ok');

    }
}

