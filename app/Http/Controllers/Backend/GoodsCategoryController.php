<?php

namespace App\Http\Controllers\backend;

use App\Common\Library\Tools\ArrayTool;
use App\Model\GoodsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;

/**
 * 商品分类
 * Class GoodsCategoryController
 * @package App\Http\Controllers\backend
 */
class GoodsCategoryController extends BackendBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lst()
    {
        $category = DB::table('goods_category')->get()->toArray();
        $arrayTool = new ArrayTool();
        $cateData = $arrayTool->getTreeRec($category);
        $ret = [
            'cateData' => $cateData
        ];
        return view('backend.goodsCategory.lst', $ret);
    }
    /**
     * 添加商品分类
     */
    public function add()
    {
        $cateData = DB::table('goods_category')->get();
        $arrayTool = new ArrayTool();
        $parentData = $arrayTool->getTreeRec($cateData);
        $typeData = DB::table('type')->get();
        $ret = [
            'parentData' => $parentData,
            'typeData' => $typeData,
        ];
        return view('backend.goodsCategory.add', $ret);
    }

    public function addStore(Request $request)
    {
        $this->validate($request, [
            'parent_id'        => 'required|numeric',
            'category_name'      => 'required|max:255',
        ],[
            'parent_id.required'     => '父级分类必传',
            'category_name.id'         => '商品分类标题应必传！',
            'category_name.max'        => '分类名称的值最长不能超过 255 个字符！',
        ]);
        $data = $request->input();
        DB::table('goods_category')->insert([
           'category_name' => $data['category_name'],
           'parent_id'     => $data['parent_id'],
        ]);
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
        $cateId = $request->input('id');
        if (empty($cateId) || $cateId < 1) {
            return res_fail('ID必填');
        }
        $cateData = DB::table('goods_category')->where('id','=',$cateId)->first();
        $cateData = (array)$cateData;
        if (empty($cateData)) {
            return res_fail('非法攻击');
        }
        $parentModel = DB::table('goods_category')->get();
        $arrayTool = new ArrayTool();
        $parentData = $arrayTool->getTreeRec($parentModel);
        $children = $arrayTool->getChildTreeRec($parentModel, $cateId);
        $ret = [
            'parentData' => $parentData,
            'cateData' => $cateData,
            'children' => $children,
        ];
        return view('backend.goodsCategory.edit', $ret);
    }

    /**
     * 修改保存
     * @param ArticleEditStoresRequest $request
     * @return false|string
     */
    public function editStore(Request $request)
    {
        $this->validate($request, [
            'id'                => 'required|numeric',
            'parent_id'         => 'required|numeric',
            'category_name'     => 'required|max:255',
        ],[
            'parent_id.required'       => '父级分类必传',
            'category_name.id'         => '商品分类标题应必传！',
            'category_name.max'        => '分类名称的值最长不能超过 255 个字符！',
        ]);
        $reqData = $request->input();
        $article = GoodsCategory::find($reqData['id']);
        if (empty($article)) {
            return res_fail('非法攻击');
        }
        $article->parent_id = $reqData['parent_id'];
        $article->category_name = $reqData['category_name'];
        $article->save();
        return res_success([],'修改成功');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $isExist = GoodsCategory::find($id);
        if (empty($isExist)) {
            return res_fail('非法攻击');
        }
        /**
         * todo 当前分类和子集分类不能被商品使用
         */
        $goodsCate = DB::table('goods')->where('category_id','=',$id)->first();
        $goodsExtCate = DB::table('goods_ext_category')->where('category_id','=',$id)->first();
        if (!empty($goodsCate) || !empty($goodsExtCate)) {
            return res_fail('已有商品使用该分类，不能删除');
        }
        // 先找出所有的子分类
        $parentModel = DB::table('goods_category')->get();
        $arrayTool = new ArrayTool();
        $children = $arrayTool->getChildTreeRec($parentModel, $id);
        array_push($children,$id);
        DB::table('goods_category')->whereIn('id',$children)->delete();
        return res_success();
    }


}
