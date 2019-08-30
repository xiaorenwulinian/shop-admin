<?php

namespace App\Http\Controllers\backend;

use App\Common\Logic\ArticleLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    /**
     * 添加文章
     */
    public function add()
    {
        $cateData = DB::table('category')->get();
        $cateData = $this->getTree($cateData);
        $ret = [
            'cateData' => $cateData,
        ];
        return view('backend.article.add', $ret);
    }

    public function addStore(Request $request)
    {
        $this->validate($request, [
            'type_id'        => 'required|numeric',
            'art_title'      => 'required|max:255',
            'art_content'    => 'required'
        ],[
            'type_id.required'     => '文章分类必传',
            'art_title.id'         => '文章标题应必传！',
            'art_title.max'        => '文章标题应小于255个字！',
        ]);
        $data = $request->input();
        foreach ($data as $k=>$v) {
            if (empty($v)) {
                unset($data[$k]);
            }
        }
        $data['add_time'] = time();
        Article::create($data);
        return res_success();
    }

    /**
     * 修改显示
     * @param Request $request
     * @return false|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * 修改保存
     * @param ArticleEditStoresRequest $request
     * @return false|string
     */
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

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $isExist = Article::find($id);
        if (empty($isExist)) {
            return res_fail('非法攻击');
        }
        Article::delete($id);
        return res_success();
    }

    public function multiDelete(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('article')->whereIn('id',explode(',',$ids))->delete();
        return res_success();
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
