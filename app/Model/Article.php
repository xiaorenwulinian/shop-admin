<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected $table = 'article';
    public $timestamps = false;
    protected $fillable = [
        'art_title','art_desc','art_content','type',
        'order_id','add_time','edit_time'
    ];


    public function search($page_size)
    {
        $where = [];
        $request = request();
        $art_title = $request->input('art_title');
        if ($art_title) {
            $where[] = ['a.art_title','like','%'. $art_title . '%'];
        }
        $type_id = $request->input('type_id');
        if ($type_id) {
            $where[] = ['a.type_id','=', $type_id];
        }
        $begin_time = $request->input('begin_time');
        $end_time = $request->input('end_time');
        if ($begin_time && $end_time) {
            //开始时间 大于 结束时间 ，特殊情况 可以不处理，或者将两个时间替换
            if (($end_time) < $begin_time) {
                list($end_time,$begin_time) = [$begin_time,$end_time];
            }
            $begin_time_int = strtotime(date('Y-m-d',strtotime($begin_time)) . ' 00:00:00');
            $end_time_int = strtotime(date('Y-m-d',strtotime($end_time)) . ' 23:59:59');
            $where[] = ['a.add_time','between',[$begin_time_int,$end_time_int]];
        } elseif ($begin_time) {
            //只有开始时间
            $begin_time_int = strtotime(date('Y-m-d',strtotime($begin_time)) . ' 00:00:00');
            $where[] = ['a.add_time','>=',$begin_time_int];
        } elseif ($end_time) {
            // 只有结束时间
            $end_time_int = strtotime(date('Y-m-d',strtotime($end_time)) . ' 23:59:59');
            $where[] = ['a.add_time','<=',$end_time_int];
        }
        $select_filed = [
            'a.id','a.art_title','a.art_desc','a.type_id','c.title as cat_title','a.add_time'
        ];
        $list = DB::table('article as a')
            ->leftJoin('category as c','a.type_id', '=', 'c.id')
            ->where($where)
            ->select($select_filed)
            ->orderBy('a.id','desc')
            ->paginate($page_size);
//            ->paginate($page_size,false,['query' => request()-
        $list->appends($request->input());
        return $list;
    }
}
