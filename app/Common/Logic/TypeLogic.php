<?php

namespace App\Common\Logic;


use Illuminate\Support\Facades\DB;

class TypeLogic
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
        $typeName = $request->input('type_name');
        $where[] = ['is_del','=', 0 ];
        if ($typeName) {
            $where[] = ['type_name','like','%'. $typeName . '%'];
        }
        $selectFiled = [
            'id','type_name'
        ];
        $list = DB::table('type')
            ->where($where)
            ->select($selectFiled)
            ->orderBy('id','desc')
            ->paginate($page_size);
        $list->appends($request->input());
        return $list;
    }


}
