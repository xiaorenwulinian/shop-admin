<?php

namespace App\Http\Controllers\backend;

use App\Common\Logic\TypeLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

/**
 * 商品属性分类
 * Class TypeController
 * @package App\Http\Controllers\backend
 */
class TypeController extends BackendBaseController
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
        $typedata = DB::table('type')->get();
        $typeLogic = TypeLogic::getInstance();
        $list = $typeLogic->search($page_size);
        $page = $list->render();
        $cur_page = $request->input('page',1);
        $ret = [
            'typedata'         => $typedata,
            'list'             => $list,
            'page_show'        => $page,
            'page_size_select' => $page_size_select,
            'page_size'        => $page_size,
            'page'             => $cur_page
        ];
        return view('backend.type.lst', $ret);
    }

    /**
     * 添加显示
     */
    public function add()
    {
//        $cateData = DB::table('type')->get();
//        $cateData = $this->getTree($cateData);
        $ret = [
//            'cateData' => $cateData,
        ];
        return view('backend.type.add', $ret);
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
            'type_name'      => 'required|max:255',
        ],[
            'type_name.required'     => '分类必传',
            'type_name.max'        => '标题应小于255个字！',
        ]);
        $data = $request->input();
        Type::create($data);
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
        $typeData = DB::table('type')->where('id','=',$reqData['id'])->first();
        $typeData = (array)$typeData;
        if (empty($typeData)) {
             return res_fail('非法攻击');
        }
        $ret = [
            'typeData' => $typeData,
        ];
        return view('backend.type.edit', $ret);
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
            'type_name'      => 'required|max:255',
        ],[
            'type_name.required'     => '分类必传',
            'type_name.max'        => '标题应小于255个字！',
        ]);
        $reqData = $request->input();
        $type = Type::find($reqData['id']);
        if (empty($type)) {
            return res_fail('非法攻击');
        }
        $type->type_name = $reqData['type_name'];
        $type->save();
        return res_success([],'修改成功');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $type = Type::find($id);
        if (empty($type)) {
            return res_fail('非法攻击');
        }
        $type->is_del = 1;
        $type->save();
        return res_success();
    }

    public function multiDelete(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('type')->whereIn('id',explode(',',$ids))->update([
            'is_del' => 1
        ]);
        return res_success();
    }

}
