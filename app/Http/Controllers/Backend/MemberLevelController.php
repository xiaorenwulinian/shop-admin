<?php

namespace App\Http\Controllers\backend;

use App\Common\Logic\MemberLevelLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\MemberLevel;
use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

/**
 * 商品属性分类
 * Class TypeController
 * @package App\Http\Controllers\backend
 */
class MemberLevelController extends BackendBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lst(Request $request)
    {
        $default_page_size = 10; // 每一页默认显示的条数
        $page_size = $request->input('page_size',$default_page_size);
        $page_size_select = page_size_select($page_size); //生成下拉选择页数框
        $memberLevelLogic = MemberLevelLogic::getInstance();
        $list = $memberLevelLogic->search($page_size);
        $page = $list->render();
        $cur_page = $request->input('page',1);
        $ret = [
            'list'             => $list,
            'page_show'        => $page,
            'page_size_select' => $page_size_select,
            'page_size'        => $page_size,
            'page'             => $cur_page
        ];
        return view('backend.memberLevel.lst', $ret);
    }

    /**
     * 添加显示
     */
    public function add()
    {
//        $cateData = DB::table('memberLevel')->get();
//        $cateData = $this->getTree($cateData);
        $ret = [
//            'cateData' => $cateData,
        ];
        return view('backend.memberLevel.add', $ret);
    }

    /**
     * 添加保存
     * @param Request $request
     * @return false|string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addStore(Request $request)
    {
        $this->validate($request, [
            'level_name'      => 'required|max:255',
            'bottom_num'      => 'required|integer',
            'top_num'      => 'required|integer',
            'rate'      => 'required|integer|between:0,100',

        ],[
            'level_name.required'     => '分类必传',
            'level_name.max'        => '标题应小于255个字！',
        ]);
        $data = $request->input();
        MemberLevel::create($data);
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
            'id.required'   => 'ID必填！',
        ]);
        $reqData =  $request->all();
        $memberLevelData = DB::table('member_level')->where('id','=',$reqData['id'])->first();

        if (empty($memberLevelData)) {
             return res_fail('非法攻击');
        }
        $memberLevelData = (array)$memberLevelData;
        $ret = [
            'memberLevelData' => $memberLevelData,
        ];
        return view('backend.memberLevel.edit', $ret);
    }

    /**
     * 修改保存
     * @param ArticleEditStoresRequest $request
     * @return false|string
     */
    public function editStore(Request $request)
    {

        $this->validate($request, [
            'id' => "required",
            'level_name'      => 'required|max:255',
            'bottom_num'      => 'required|integer',
            'top_num'      => 'required|integer',
            'rate'      => 'required|integer|between:0,100',

        ],[
            'level_name.required'     => '分类必传',
            'level_name.max'        => '标题应小于255个字！',
        ]);
        $reqData = $request->input();
        $memberLevel = MemberLevel::find($reqData['id']);
        if (empty($memberLevel)) {
            return res_fail('非法攻击');
        }
        $memberLevel->level_name = $reqData['level_name'];
        $memberLevel->bottom_num = $reqData['bottom_num'];
        $memberLevel->top_num = $reqData['top_num'];
        $memberLevel->rate = $reqData['rate'];
        $memberLevel->save();
        return res_success([],'修改成功');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $memberLevel = MemberLevel::find($id);
        if (empty($memberLevel)) {
            return res_fail('非法攻击');
        }
        $memberLevel->is_del = 1;
        $memberLevel->save();
        return res_success();
    }

    public function multiDelete(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('member_level')->whereIn('id',explode(',',$ids))->update([
            'is_del' => 1
        ]);
        return res_success();
    }

}
