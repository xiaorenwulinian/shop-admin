<?php

namespace App\Common\Logic;


use Illuminate\Support\Facades\DB;

class AttributeLogic
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
        $where[] = ['a.is_del','=', 0 ];
        $attrName = $request->input('attr_name');
        if ($attrName) {
            $where[] = ['a.attr_name','like','%'. $attrName . '%'];
        }
        $typeId = $request->input('type_id');
        if ($typeId) {
            $where[] = ['t.id','=',$typeId ];
        }
        $selectFiled = [
            'a.id','a.attr_name','a.attr_type','a.attr_option_values','a.type_id','t.type_name'
        ];
        $list = DB::table('attribute AS a')
            ->leftJoin('type AS t','a.type_id','=','t.id')
            ->where($where)
            ->select($selectFiled)
            ->orderBy('id','desc')
            ->paginate($page_size);
        $list->appends($request->input());
        return $list;
    }


}
