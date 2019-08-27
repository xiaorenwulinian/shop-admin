<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

class ArticleController extends BackendBaseController
{
//    public function __construct()
//    {
//        parent::__construct();
//    }

    public function lst(Request $request)
    {
        $all_param = $request->all();
        $default_page_size = 10; // 每一页默认显示的条数
        $page_size = $request->input('page_size',$default_page_size);
        $page_size_select = page_size_select($page_size); //生成下拉选择页数框
        $cat_data = DB::table('category')->get();
//        $article = new Article();
//        $list = $article->search($page_size);
        $where = [];
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
        $page = $list->render();
        $cur_page = $request->input('page',1);
//        $url = url('add',['page'=>$cur_page]);
//        $this->setPageBtn('文章列表', '添加文章',$url) ;
        $ret = [
            'cat_data'=>$cat_data,
            'list'=>$list,
            'page_show'=>$page,
            'page_size_select'=>$page_size_select,
            'page_size'=>$page_size,
            'page'=>$cur_page
        ];
        return view('backend.article.lst', $ret);
        return $this->fetch();
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
        ],[
            'id.required'   => '用户ID必填！',
        ]);
        $reqData =  $request->all();
        $articleData = DB::table('article')->where('id','=',$reqData['id'])->first();
        $articleData = (array)$articleData;
        if (empty($articleData)) {
             return res_fail('非法攻击');
        }
        $cateData = DB::table('category')->get();
        $cateData = $this->getTree($cateData);
        $ret = [
            'articleData' => $articleData,
            'cateData' => $cateData,
        ];
        return view('backend.article.edit', $ret);
    }

    public function editStore(ArticleEditStoresRequest $request)
    {

        $reqData = $request->input();
        $article = Article::find($reqData['id']);
        if (empty($article)) {
            return res_fail('非法攻击');
        }
        $article->type_id = $reqData['type_id'];
        $article->art_title = $reqData['art_title'];
        $article->art_desc = $reqData['art_desc'];
        $article->art_content = $reqData['art_content'];
        $article->save();
        return res_success([],'修改成功');
    }

    /**
     * 递归获取所有分类以及级别
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    private function getTree($data, $parent_id = 0, $level = 0, $isClear = TRUE)
    {
        static $ret = array();
        if ($isClear)
            $ret = array();
        foreach ($data as $k => $v) {
            $v = (array)$v;
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                $this->getTree($data, $v['id'], $level + 1, FALSE);
            }
        }
        return $ret;
    }

}
