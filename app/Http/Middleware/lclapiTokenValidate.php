<?php

namespace App\Http\Middleware;

use App\Common\JwtToken\FirebaseJwtToken;
use Closure;
use Firebase\JWT\JWT;

class lclapiTokenValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // 秘钥 ，小程序 mini_program_token , IOS或安卓 app_token,前后端分离后的后台 backend_token,
        // 如果 app 和 小程序 也要求单点登陆，那么可以使用相同的方法
        FirebaseJwtToken::getInstance()->lclApiUserId();

        return $next($request);
    }
}
