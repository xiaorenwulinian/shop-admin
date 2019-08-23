<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller

{
    /**
     * 登录
     */
    public function login(Request $request)
    {
        /*$request->validate([
            'name' => 'required|max:5',
//            'body' => 'required',
//            'publish_at' => 'nullable|date',
        ]);*/

        /*$validator = Validator::make($request->all(), [
            'name' => 'required|max:5',
        ]);
        if ($validator->fails()) {
            dd($validator->errors()->messages());
        }*/

       $phone = $request->input('phone');
       $pwd   = $request->input('pwd');
       if (!preg_match('/^1[3-9]\d{9}$/',$phone)) {
            return '请输入正确的手机号码';
       }
       if (empty($pwd)) {
           return ' 请输入密码！';
       }

       return 'ok';
    }
}

