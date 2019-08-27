
<?php

if (!function_exists('res_success')) {
    /**
     * 成功返回体
     * @param array $data
     * @param string $message
     * @param int $code
     * @return false|string
     */
    function res_success ($data = [], $message = '',$code = 200) {
//        $ret = [
//            'code' => $code,
//            'msg'  => $message,
//            'data' => $data,
//        ];
//        return json_encode($ret,JSON_UNESCAPED_UNICODE);
        return response()->json([
            'code' => $code,
            'msg'  => $message,
            'time' => time(),
            'data' => $data
        ], 200, [])->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('res_fail')) {
    /**
     * 失败返回体
     * @param string $message
     * @param int $code
     * @param array $data
     * @return false|string
     *
     */
    function res_fail ($message = '', $code = 404, $data = []) {
//        $ret = [
//            'code' => $code,
//            'msg'  => $message,
//            'data' => $data,
//        ];
//        return json_encode($ret,JSON_UNESCAPED_UNICODE);
//        dd($message);
        return response()->json([
            'code' => $code,
            'msg'  => $message,
            'time' => time(),
            'data' => $data
        ],200, [])->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }
}


if (!function_exists('page_size_select')) {


    /**
     * 生成页码跳转
     * @param int $page_size
     * @return string
     */
    function page_size_select($page_size=0)
    {
        $str  = '<select class="form-control page_size_select">';
        $all_page  = [
            2,5,10,20,50,100
        ];
        foreach ($all_page as $cur_page) {
            $has_selected = $page_size == $cur_page ? "selected='selected'" : '';
            $str .= "<option value='{$cur_page}' {$has_selected}>{$cur_page}条/页</option>";
        }
        $str .= '</select>';
        return $str;
    }
}

if (!function_exists('jwt_encode')) {
    /**
     * jwt加密
     *
     * @param array  $data
     * @param int    $exp
     * @param string $key
     * @return string
     */
    function jwt_encode($data = [], $exp = 86400, $key = '')
    {
        $key         = empty($key) ? env('JWT_SECRET') : $key;
        $data['exp'] = time() + $exp;

        return \Firebase\JWT\JWT::encode($data, $key);
    }
}

if (!function_exists('jwt_decode')) {
    /**
     * jwt解密
     *
     * @param        $token
     * @param string $key
     * @return array
     */
    function jwt_decode($token, $key = '')
    {
        $key = empty($key) ? env('JWT_SECRET') : $key;

        try {
            $data = \Firebase\JWT\JWT::decode($token, $key, ['HS256']);
        } catch (Exception $e) {
            $data = [];
        }

        return object_array($data);
    }
}

if (!function_exists('object_array')) {
    function object_array($d)
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(__FUNCTION__, $d);
        } else {
            return $d;
        }
    }
}


if (!function_exists('get_user')) {
    /**
     * 获取用户信息
     *
     * @param $token
     * @param $key
     * @return array
     */
    function get_user($token, $key)
    {
        $user = jwt_decode($token, $key);

        return $user;
    }
}

if (!function_exists('toArray')) {
    function dda($model)
    {
        if (method_exists($model, 'toArray')) {
            dd($model->toArray());
        } else {
            dd($model);
        }
    }
}

if (!function_exists('write_log')) {
    /**
     * 手动记录日志
     *
     * @param $tag
     * @param $msg
     */
    function write_log($tag, $msg)
    {
        $filename = storage_path() . '/logs' . DIRECTORY_SEPARATOR . $tag . '_' . date('Ymd') . '.log';

        if (!is_string($msg)) {
            $msg = var_export($msg, 1);
        }

        $handler = null;
        if (($handler = fopen($filename, 'ab+')) !== false) {
            fwrite($handler, date('r') . "\t$msg\t" . PHP_EOL);
            fclose($handler);
        }
    }
}

/**
 * 获取用户ip
 */
if (!function_exists('get_user_ip')) {
    function get_user_ip()
    {
        if (request()->get('ip')) {
            $ip = request()->get('ip');
        } elseif (request()->header('ali-cdn-real-ip')) {
            $ip = request()->header('ali-cdn-real-ip');
        } elseif (request()->server('HTTP_X_TENCENT_UA') == 'Qcloud' || request()->server('HTTP_X_FORWARDED_FOR')) {
            $for = explode(',', request()->server('HTTP_X_FORWARDED_FOR'));
            $ip  = $for[0];
        } else {
            $ip = request()->ip();
        }

        return $ip;
    }
}

/**
 * 数据加密
 *
 * @param        $value
 * @param string $salt
 * @param bool   $environment 是否环境隔离
 * @return mixed
 */
function hash_encode($value, $salt = '', $environment = true)
{
    $hash = new Hashids\Hashids(($salt ?: env('HASHIDS_SALT')) . ($environment ? app()->environment() : ''), 6);

    return $hash->encode($value);
}

/**
 * 数据解密
 *
 * @param        $value
 * @param string $salt
 * @param bool   $environment 是否环境隔离
 * @return string
 */
function hash_decode($value, $salt = '', $environment = true)
{
    $hash         = new Hashids\Hashids(($salt ?: env('HASHIDS_SALT')) . ($environment ? app()->environment() : ''), 6);
    $decode_value = $hash->decode($value);

    return $decode_value[0] ?? '';
}

if (!function_exists('get_user')) {
    /**
     * 获取用户信息
     *
     * @param $token
     * @param $key
     * @return array
     */
    function get_user($token, $key)
    {
        $user = jwt_decode($token, $key);

        return $user;
    }
}

/**
 * 手机号校验
 *
 * @param $phone
 * @return int
 */
function is_mobile($phone)
{
    return preg_match("/^1[3456789]\d{9}$/", $phone);
}

/**
 * 钉钉机器人
 *
 * @param $content
 */
function dingding_robot($content)
{
    if (env('APP_ENV') == 'local') {
        $url = 'https://oapi.dingtalk.com/robot/send?access_token=a5e3cbda8998da05495e182d72794382c31537fcf441ec31ab56488ce7339887';
    } else {
        $url = 'https://oapi.dingtalk.com/robot/send?access_token=2e1db4d6325ac5589092c014bc32f66a1573f0c3b547c7b02c440235040b7a46';
    }

    $body = [
        'msgtype'  => 'markdown',
        'markdown' => [
            'title' => 'shop系统异常提醒',
            'text'  => $content,
        ],
    ];

    $client = new \GuzzleHttp\Client();
    $client->post($url, ['headers' => ['Content-Type' => 'application/json'], 'body' => \GuzzleHttp\json_encode($body, JSON_UNESCAPED_UNICODE)]);
}