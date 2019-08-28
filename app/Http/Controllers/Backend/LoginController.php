<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    /**
     * 登录
     */
    public function login()
    {
        return view('backend.login.login');
    }


    public function loginSubmit(Request $request)
    {
        $phone = $request->input('phone');
        $pwd = $request->input('pwd');
        if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return res_fail([], '请输入正确的手机号码');
        }
        if (empty($pwd)) {
            return res_fail('请输入密码');
        }
        $appUser = DB::table('app_user')->where('phone', '=', $phone)->first();
        if (empty($appUser)) {
            return res_fail('手机号码不存在！');
        }
        $appUser = (array)$appUser;
        $isValidate = Hash::check($pwd, $appUser['password']);
        if (!$isValidate) {
            return res_fail('密码错误');
        }
        session('admin_user_id',$appUser['id']);
        return res_success([],'登陆成功！');
    }

}

