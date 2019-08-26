<?php

namespace App\Common\JwtToken;


use App\Exceptions\JwtTokenException;
use Firebase\JWT\JWT;

class FirebaseJwtToken
{
    protected static $instance ;
    private function __construct()
    {

    }

    /**
     * 获取实例对象，单例模式
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * lclapi 测试 token验证，获取当前用户ID
     * @return mixed
     */
    public function lclApiUserId()
    {
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
            $secretKey = 'app_user_token';
            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
            $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
            $decoded_array = (array)$decoded;
        } catch (\Exception $exception) {
            throw new JwtTokenException($exception->getMessage());
        }
        return $decoded_array['id'];
    }

    /**
     * 前后端分离后 token验证，获取当前用户ID
     * @return mixed
     */
    public function backendApiUserId()
    {
        $authorization = request()->header('authorization');
        $authorizationArr = explode(' ', $authorization);
        $jwtToken = $authorizationArr[1];
        $secretKey = 'backend_token';
        JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
        $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
        $decoded_array = (array)$decoded;
        return $decoded_array['id'];
    }


    /**
     * App ios / android token验证，获取当前用户ID
     * @return mixed
     */
    public function AppApiUserId()
    {
        $authorization = request()->header('authorization');
        $authorizationArr = explode(' ', $authorization);
        $jwtToken = $authorizationArr[1];
        $secretKey = 'app_token';
        JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
        $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
        $decoded_array = (array)$decoded;
        return $decoded_array['id'];
    }

    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function miniProgramApiUserId()
    {
        $authorization = request()->header('authorization');
        $authorizationArr = explode(' ', $authorization);
        $jwtToken = $authorizationArr[1];
        $secretKey = 'mini_program_token';
        JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
        $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
        $decoded_array = (array)$decoded;
        return $decoded_array['id'];
    }


}
