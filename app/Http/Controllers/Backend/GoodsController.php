<?php

namespace App\Http\Controllers\backend;

use App\Common\Library\Tools\ArrayTool;
use App\Common\Library\Tools\FileTool;
use App\Common\Logic\BrandLogic;
use App\Common\Logic\GoodsLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * 商品
 * Class TypeController
 * @package App\Http\Controllers\backend
 */
class GoodsController extends BackendBaseController
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
        $goodsLogic = GoodsLogic::getInstance();
        $list = $goodsLogic->search($page_size);
        $page = $list->render();
        $cur_page = $request->input('page',1);
        $ret = [
            'list'             => $list,
            'page_show'        => $page,
            'page_size_select' => $page_size_select,
            'page_size'        => $page_size,
            'page'             => $cur_page
        ];
        return view('backend.goods.lst', $ret);
    }

    /**
     * 添加显示
     */
    public function add()
    {
        $brandData = DB::table('brand')->where('is_del','=',0)->get();
        $typeData = DB::table('type')->where('is_del','=',0)->get();
        $categoryData = DB::table('goods_category')->where('is_del','=',0)->get();
        $arrayTool = new ArrayTool();
        $categoryData = $arrayTool->getTreeRec($categoryData);
        $memberLevelData = DB::table('member_level')->where('is_del','=',0)->get();
        $ret = [
            'brandData'        => $brandData,
            'typeData'         => $typeData,
            'categoryData'     => $categoryData,
            'memberLevelData'  => $memberLevelData,
        ];
        return view('backend.goods.add', $ret);
    }

    public function addUpload(Request $request)
    {
        $file = $request->file('brand_img');
        if (empty($file) || !$file->isValid()) {
            return res_fail("请输入正确的文件格式！");
        }

        $originalName = $file->getClientOriginalName(); // 文件原名
        $ext = $file->getClientOriginalExtension();  // 扩展名
        $uploadDir =  '/uploads/brand';
//        $timeArr = explode('.',microtime(true));
//        $uniqueFileName =  $timeArr[0] . $timeArr[1] . rand(100000,999999) . uniqid() . '.'. $ext; // oss文件名
        $uniqueFileName =  date('YmdHis').'-'.uniqid() . '.'. $ext; // oss文件名
        $database = $uploadDir .'/'. $uniqueFileName;
        $path = $file->move(public_path($uploadDir),$uniqueFileName);
        $bigImg = $uploadDir . DIRECTORY_SEPARATOR . $uniqueFileName;
        $ret = [
            'logo_file_path' => $bigImg,
        ];

        return res_success($ret);


        // 获取表单上传文件 例如上传了001.jpg
        $file = $request->file('brand_img');
        $upload_second_dir = 'brand'; //上传的二级目录
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>10000000])->move( "./uploads/{$upload_second_dir}/");
        if($info){

            //生成的文件路径
            $old_big_img = $big_file_path = str_replace('\\','/',$info->getSaveName());
            $thumb_img = str_replace($info->getFilename(),'thumb_'.$info->getFilename(),$old_big_img);
            $thumb_img_path = "./uploads/{$upload_second_dir}/".$thumb_img;
//            $image = \think\Image::open('./uploads/advertise/'.$big_file_path);
            $image = \think\Image::open(request()->file('ad_img'));
            $image->thumb(200, 200)->save($thumb_img_path);
            return  json([
                'code'=>0,
                'logo_file_path'=> $upload_second_dir.'/'.$big_file_path,
                'logo_file_path_thumb'=> $upload_second_dir.'/'.$thumb_img
            ]);
        }else{
            return  json(['code'=>1, 'msg'=> $file->getError()]);
        }
    }

    /**
     * 添加时删除本地文件
     * @param Request $request
     * @return false|string
     */
    public function addDeleteImg(Request $request)
    {
        $cur_logo_path = $request->input('cur_logo_path');
        if($cur_logo_path) {
            $cur_logo_path_all =  public_path($cur_logo_path);
            if(file_exists($cur_logo_path_all)) {
                unlink($cur_logo_path_all);
            }
            return  res_success();
        }
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
            'brand_name'      => 'required|max:255',
            'site_url'      => 'required|max:255',
            'brand_img'      => 'required|max:255',
        ],[
            'brand_name.required'     => '分类必传',
            'brand_name.max'        => '标题应小于255个字！',
        ]);
        $data = $request->input();
        Brand::create($data);
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
        $brandData = DB::table('brand')->where('id','=',$reqData['id'])->first();
        $brandData = (array)$brandData;
        if (empty($brandData)) {
             return res_fail('非法攻击');
        }
        $ret = [
            'brandData' => $brandData,
        ];
        return view('backend.brand.edit', $ret);
    }

    /**
     * 修改时上传图片
     * @param Request $request
     * @return false|string
     */
    public function editUpload(Request $request)
    {
        $file = $request->file('brand_img');
        $brandId = $request->input('brand_id');
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return res_fail('非法攻击');
        }

        if (empty($file) || !$file->isValid()) {
            return res_fail("请输入正确的文件格式！");
        }
        $ext = $file->getClientOriginalExtension();  // 扩展名
        $uploadDir =  '/uploads/brand';
        $uniqueFileName =  date('YmdHis').'-'.uniqid() . '.'. $ext; // oss文件名
        $path = $file->move(public_path($uploadDir),$uniqueFileName);

        $bigImg = $uploadDir . DIRECTORY_SEPARATOR . $uniqueFileName;
        $brand->brand_img  = $bigImg;
        $brand->save();
        $ret = [
            'logo_file_path' => $bigImg,
        ];

        return res_success($ret);
    }

    /**
     * 修改时删除图片
     * @param Request $request
     * @return false|string
     */
    public function editDeleteImg(Request $request)
    {
        $id = $request->input('id');
        $brand = Brand::find($id);
        if (empty($brand)) {
            return res_fail('非法攻击');
        }
        $logoPath = $brand->brand_img;
        if ($logoPath) {
            $logoPathAll =  public_path($logoPath);
            if(file_exists($logoPathAll)) {
                unlink($logoPathAll);
            }
        }
        $brand->brand_img  = '';
        $brand->save();
        return res_success([],'修改成功');
    }

    /**
     * 修改保存
     * @param ArticleEditStoresRequest $request
     * @return false|string
     */
    public function editStore(Request $request)
    {
        $this->validate($request, [
            'id'            => "required",
            'brand_name'    => 'required|max:255',
            'site_url'      => 'required|max:255',
        ],[
            'brand_name.required'     => '分类必传',
            'brand_name.max'        => '标题应小于255个字！',
        ]);

        $reqData = $request->input();
        $brand = Brand::find($reqData['id']);
        if (empty($brand)) {
            return res_fail('非法攻击');
        }
        $brand->brand_name = $reqData['brand_name'];
        $brand->site_url   = $reqData['site_url'];
        $brand->save();
        return res_success([],'修改成功');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $brand = Brand::find($id);
        if (empty($brand)) {
            return res_fail('非法攻击');
        }
        $brand->is_del = 1;
        $brand->save();
        return res_success();
    }

    public function multiDelete(Request $request)
    {
        $ids = $request->input('ids');
        DB::table('brand')->whereIn('id',explode(',',$ids))->update([
            'is_del' => 1
        ]);
        return res_success();
    }

}
