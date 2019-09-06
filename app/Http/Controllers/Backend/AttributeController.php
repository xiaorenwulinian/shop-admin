<?php

namespace App\Http\Controllers\backend;

use App\Common\Logic\AttributeLogic;
use App\Common\Logic\TypeLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Attribute;
use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

/**
 * 商品属性
 * Class TypeController
 * @package App\Http\Controllers\backend
 */
class AttributeController extends BackendBaseController
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
        $typeData = DB::table('type')->get();
        $attributeLogic = AttributeLogic::getInstance();
        $list = $attributeLogic->search($page_size);
        $page = $list->render();
        $cur_page = $request->input('page',1);
        $ret = [
            'typeData'    => $typeData,
            'list'             => $list,
            'page_show'        => $page,
            'page_size_select' => $page_size_select,
            'page_size'        => $page_size,
            'page'             => $cur_page
        ];
        return view('backend.attribute.lst', $ret);
    }

    /**
     * 添加显示
     */
    public function add()
    {
        $typeData = DB::table('type')->get();
        $ret = [
            'typeData' => $typeData,
        ];
        return view('backend.attribute.add', $ret);
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
            'type_id'    => 'required',
            'attr_name'  => 'required|max:255',
            'attr_type'  => 'required|max:255',
        ],[
            'attr_name.required'   => '分类必传',
            'attr_name.max'        => '标题应小于255个字！',
        ]);
        $data = $request->input();
        foreach ($data as $k=>$v) {
            if (empty($v)) {
                unset($data[$k]);
            }
        }
        Attribute::create($data);
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
        $attributeData = DB::table('attribute')->where('id','=',$reqData['id'])->first();
        $attributeData = (array)$attributeData;
        if (empty($attributeData)) {
             return res_fail('非法攻击');
        }
        $typeData = DB::table('type')->get();
        $ret = [
            'attributeData' => $attributeData,
            'typeData' => $typeData,
        ];
        return view('backend.attribute.edit', $ret);
    }

    /**
     * 修改保存
     * @param ArticleEditStoresRequest $request
     * @return false|string
     */
    public function editStore(Request $request)
    {
        $this->validate($request, [
            'id'         => "required",
            'type_id'    => 'required',
            'attr_name'  => 'required|max:255',
            'attr_type'  => 'required|max:255',
        ],[
            'attr_name.required'   => '分类必传',
            'attr_name.max'        => '标题应小于255个字！',
        ]);

        $reqData = $request->input();
        $attribute = Attribute::find($reqData['id']);
        if (empty($attribute)) {
            return res_fail('非法攻击');
        }
        $attribute->type_id = $reqData['type_id'];
        $attribute->attr_name = $reqData['attr_name'];
        $attribute->attr_type = $reqData['attr_type'];
        if (is_null($reqData['attr_option_values'])) {
            $reqData['attr_option_values'] = '';
        }
        $attribute->attr_option_values = $reqData['attr_option_values'] ;
        $attribute->save();
        return res_success([],'修改成功');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $attribute = Attribute::find($id);
        if (empty($attribute)) {
            return res_fail('非法攻击');
        }
        $attribute->is_del = 1;
        $attribute->save();
        return res_success();
    }

    public function multiDelete(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('attribute')->whereIn('id',explode(',',$ids))->update([
            'is_del' => 1
        ]);
        return res_success();
    }

}
