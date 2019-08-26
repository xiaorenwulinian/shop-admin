<?php

namespace App\Http\Controllers\Kathy;

use App\Model\Kathy\Admin;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;


class LoginController extends Controller

{
    /**
     * 登录
     */
    public function login(Request $request)
    {

        $param = $request->only(['phone', 'password']);
        $this->validation([
            'phone'    => 'required',
            'password' => 'required',
        ]);

        $user = Admin::query()->with('role')->where('phone', $param['phone'])->first();
        abort_if(!optional($user)->status, 400, '账户被禁用');
        (empty($user) || !password_verify($param['password'], $user['password'])) && abort(400, '账号或密码错误');
        $role       = optional($user)->role;
        $menu       = [];
        if ($role) {
            foreach ($role as $item) {
                $menu[] = json_decode($item['menu'], true);
            }
            $menu = collect($menu)->collapse()->unique()->all();
        }
        $role_id         = collect($role)->pluck('id');
        $user->is_online = 1;
        $user->save();
        $user_info['role_id']            = $role_id;
        $token                           = jwt_encode($user_info, 46400, config('common.app.crm-dashboard.jwt_secret'));

        $result['token'] = $token;

        return res_success($result);
    }
}

