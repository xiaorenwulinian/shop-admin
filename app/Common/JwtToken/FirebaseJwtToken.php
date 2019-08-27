<?php

namespace App\Common\JwtToken;


use App\Exceptions\JwtTokenException;
use Firebase\JWT\JWT;

class FirebaseJwtToken
{
    protected static $instance ;
    public $lclUserId ;
    public $appUserId ;
    public $backendUserId ;
    public $miniProgramUserId ;
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
        $secretKey = 'app_user_token';
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
            $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
            $decodedArray = (array)$decoded;
            $uid =  $decodedArray['uid'];

        } catch (\Exception $exception) {
            throw new JwtTokenException($exception->getMessage());
        }
        $this->lclUserId = $uid;
        return $uid;
    }


    /**
     * 前后端分离后 token验证，获取当前用户ID
     * @return mixed
     */
    public function backendApiUserId()
    {
        $secretKey = 'backend_token';
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
            $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
            $decodedArray = (array)$decoded;
            $uid =  $decodedArray['uid'];
        } catch (\Exception $exception) {
            throw new JwtTokenException($exception->getMessage());
        }
        $this->backendUserId = $uid;
        return $uid;
    }

    /**
     * App ios / android token验证，获取当前用户ID
     * @return mixed
     */
    public function AppApiUserId()
    {
        $secretKey = 'app_token';
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
            $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
            $decodedArray = (array)$decoded;
            $uid =  $decodedArray['uid'];
        } catch (\Exception $exception) {
            throw new JwtTokenException($exception->getMessage());
        }
        $this->appUserId = $uid;
        return $uid;
    }

    /**
     * 小程序 token验证，获取当前用户ID
     * @return mixed
     */
    public function miniProgramApiUserId()
    {
        $secretKey = 'mini_program_token';
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
            $decoded = JWT::decode($jwtToken, $secretKey, array('HS256'));
            $decodedArray = (array)$decoded;
            $uid =  $decodedArray['uid'];
        } catch (\Exception $exception) {
            throw new JwtTokenException($exception->getMessage());
        }
        $this->miniProgramUserId = $uid;
        return $uid;
    }


}
