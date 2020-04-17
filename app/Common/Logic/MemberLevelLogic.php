<?php

namespace App\Common\Logic;


use Illuminate\Support\Facades\DB;

class MemberLevelLogic
{
    private static $instance = null;
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
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * 后台 文章搜索
     * @param $page_size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($page_size)
    {
        $where = [];
        $request = request();
        $levelName = $request->input('level_name');
        $where[] = ['is_del','=', 0 ];
        if ($levelName) {
            $where[] = ['level_name','like','%'. $levelName . '%'];
        }
        $selectFiled = [
            'id','level_name','bottom_num', 'top_num','rate',
        ];
        $list = DB::table('member_level')
            ->where($where)
            ->select($selectFiled)
            ->orderBy('id','desc')
            ->paginate($page_size);
        $list->appends($request->input());
        return $list;
    }


}
