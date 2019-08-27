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
    public function getUserIdLclApi()
    {
        $secretKey = config('firebaseJwt.secret_key_lclapi');
        try {
            $authorization = request()->header('authorization');
            $authorizationArr = explode(' ', $authorization);
            $jwtToken = $authorizationArr[1];
//            JWT::$leeway = 40; // $leeway in seconds  token 过期时间到期，延迟失效 单位秒
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
    public function getUserIdBackend()
    {
        $secretKey = config('firebaseJwt.secret_key_backend');
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
    public function getUserIdApp()
    {
        $secretKey = config('firebaseJwt.secret_key_app');
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
    public function getUserIdMiniProgram()
    {
        $secretKey = config('firebaseJwt.secret_key_mini_program');
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

    /**
     * lclapi 生成token
     * @param Array $user 当前用户
     * @return string
     */
    public function generateTokenLclapi(Array $user)
    {
        $secretKey = config('firebaseJwt.secret_key_lclapi');
        $time = time();
        $expireTime = $time + config('firebaseJwt.expire_time_lcl_api');
        $secretToken = array(
            "iss" => "shop.yjflower.com",
            "aud" => "shop.yjflower.com",
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime,
            "uid" => $user['id'], // 不建议存太多信息，用户ID和姓名即可，敏感信息会被窃取
        );
        $jwtToken = JWT::encode($secretToken, $secretKey);
        return $jwtToken;
    }

    /**
     * app 生成token
     * @param Array $user 当前用户
     * @return string
     */
    public function generateTokenApp(Array $user)
    {
        $secretKey = config('firebaseJwt.secret_key_app');
        $time = time();
        $expireTime = $time + config('firebaseJwt.expire_time_app');
        $secretToken = array(
            "iss" => "shop.yjflower.com",
            "aud" => "shop.yjflower.com",
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime,
            "uid" => $user['id'], // 不建议存太多信息，用户ID和姓名即可，敏感信息会被窃取
        );
        $jwtToken = JWT::encode($secretToken, $secretKey);
        return $jwtToken;
    }

    /**
     * MiniProgram 生成token
     * @param Array $user 当前用户
     * @return string
     */
    public function generateTokenMiniProgram(Array $user)
    {
        $secretKey = config('firebaseJwt.secret_key_mini_program');
        $time = time();
        $expireTime = $time + config('firebaseJwt.expire_time_mini_program');
        $secretToken = array(
            "iss" => "shop.yjflower.com",
            "aud" => "shop.yjflower.com",
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime,
            "uid" => $user['id'], // 不建议存太多信息，用户ID和姓名即可，敏感信息会被窃取
        );
        $jwtToken = JWT::encode($secretToken, $secretKey);
        return $jwtToken;
    }

    /**
     * MiniProgram 生成token
     * @param Array $user 当前用户
     * @return string
     */
    public function generateTokenBackend(Array $user)
    {
        $secretKey = config('firebaseJwt.secret_key_backend');
        $time = time();
        $expireTime = $time + config('firebaseJwt.expire_time_backend');
        $secretToken = array(
            "iss" => "shop.yjflower.com",
            "aud" => "shop.yjflower.com",
            "iat" => $time,
            "nbf" => $time,
            "exp" => $expireTime,
            "uid" => $user['id'], // 不建议存太多信息，用户ID和姓名即可，敏感信息会被窃取
        );
        $jwtToken = JWT::encode($secretToken, $secretKey);
        return $jwtToken;
    }


}
