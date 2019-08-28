<?php

namespace App\Http\Controllers\backend;

use App\Common\Logic\ArticleLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

class ArticleController extends BackendBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lst(Request $request)
    {
        $default_page_size = 10; // 每一页默认显示的条数
        $page_size = $request->input('page_size',$default_page_size);
        $page_size_select = page_size_select($page_size); //生成下拉选择页数框
        $cat_data = DB::table('category')->get();
        $articleLogic = ArticleLogic::getInstance();
        $list = $articleLogic->search($page_size);
        $page = $list->render();
        $cur_page = $request->input('page',1);
        $ret = [
            'cat_data'         => $cat_data,
            'list'             => $list,
            'page_show'        => $page,
            'page_size_select' => $page_size_select,
            'page_size'        => $page_size,
            'page'             => $cur_page
        ];
        return view('backend.article.lst', $ret);
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
