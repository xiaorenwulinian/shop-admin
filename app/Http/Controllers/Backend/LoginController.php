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
        $username = $request->input('username');
        $pwd = $request->input('password');
//        if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
//            return res_fail([], '请输入正确的手机号码');
//        }
        if (empty($pwd) || empty($username)) {
            return res_fail('用户名和密码不能为空！');
        }
        $adminUser = DB::table('admin')->where('username', '=', $username)->first();
        if (empty($adminUser)) {
            return res_fail('该用户不存在！');
        }
        $adminUser = (array)$adminUser;
        $isValidate = Hash::check($pwd, $adminUser['password']);
        if (!$isValidate) {
            return res_fail('密码错误');
        }
        session(['admin_user_id' => $adminUser['id']]);
        return res_success([],'登陆成功！');
    }

}

