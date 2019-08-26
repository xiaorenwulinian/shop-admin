
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
        $ret = [
            'code' => $code,
            'msg'  => $message,
            'data' => $data,
        ];
        return json_encode($ret,JSON_UNESCAPED_UNICODE);
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
        $ret = [
            'code' => $code,
            'msg'  => $message,
            'data' => $data,
        ];
        return json_encode($ret,JSON_UNESCAPED_UNICODE);
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
