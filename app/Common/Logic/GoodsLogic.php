<?php

namespace App\Common\Logic;


use Illuminate\Support\Facades\DB;

class GoodsLogic
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
        $goodsName = $request->input('goods_name');
        $where[] = ['is_delete','=', 0 ];
        if ($goodsName) {
            $where[] = ['goods_name','like','%'. $goodsName . '%'];
        }
        $selectFiled = [
            'id','goods_name','goods_number','category_id','brand_id',
            'market_price','shop_price','jifen','jifen_price','promote_price','promote_start_time',
            'promote_end_time','goods_img','goods_thumb_img','is_hot','is_new','is_best',
            'is_on_sale','seo_keyword','seo_description','type_id','sort_num','addtime',
            'goods_desc','is_promote'
        ];
        $list = DB::table('goods')
            ->where($where)
            ->select($selectFiled)
            ->orderBy('id','desc')
            ->paginate($page_size);
        $list->appends($request->input());
        return $list;
    }


}
