<?php

namespace App\Http\Controllers\backend;

use App\Common\Library\Tools\ArrayTool;
use App\Common\Library\Tools\FileTool;
use App\Common\Logic\BrandLogic;
use App\Common\Logic\GoodsLogic;
use App\Http\Requests\ArticleEditStoresRequest;
use App\Model\Attribute;
use App\Model\AttrSaleValue;
use App\Model\Brand;
use App\Model\Goods;
use App\Model\GoodsSaleAttr;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    /**
     * 添加时单文件上传
     * @param Request $request
     * @return false|string
     */
    public function addUploadOne(Request $request)
    {
        $file = $request->file('goods_img');
        if (empty($file) || !$file->isValid()) {
            return res_fail("请输入正确的文件格式！");
        }
        $originalName = $file->getClientOriginalName(); // 文件原名
        $ext = $file->getClientOriginalExtension();  // 扩展名
        $curDay = date('Ymd');
        $uploadDir =  '/uploads/goods/'.$curDay;
        $unique = date('YmdHis').'-'.uniqid();
        $fileName =   $unique . '.'. $ext;
        $fileThumbName =  $unique . '_thumb.'. $ext;
        $file->move(public_path($uploadDir),$fileName);
        $bigImg = $uploadDir . '/' . $fileName; //原图
        $thumbImg = $uploadDir . '/' . $fileThumbName; //缩略图
        Image::make(public_path($bigImg))->resize(200,200)->save(public_path($thumbImg));
        $ret = [
            'logo_file_path'       => $bigImg,
            'logo_file_path_thumb' => $thumbImg,
        ];
        return res_success($ret);
    }

    /**
     * 添加时上传相册集
     * @param Request $request
     * @return false|string
     */
    public function addUploadMulti(Request $request)
    {

        $file = $request->file('photo_multi');
        if (empty($file) || !$file->isValid()) {
            return res_fail("请输入正确的文件格式！");
        }
        $originalName = $file->getClientOriginalName(); // 文件原名
        $ext = $file->getClientOriginalExtension();  // 扩展名
        $curDay = date('Ymd');
        $uploadDir =  '/uploads/goodsAlbum/'.$curDay;
        $unique = date('YmdHis').'-'.uniqid();
        $fileName =   $unique . '.'. $ext;
        $fileThumbName =  $unique . '_thumb.'. $ext;
        $file->move(public_path($uploadDir),$fileName);
        $bigImg = $uploadDir . '/' . $fileName; //原图
        $thumbImg = $uploadDir . '/' . $fileThumbName; //缩略图
        Image::make(public_path($bigImg))->resize(200,200)->save(public_path($thumbImg));
        $ret = [
            'logo_file_path'       => $bigImg,
            'logo_file_path_thumb' => $thumbImg,
        ];
        return res_success($ret);
    }

    /**
     * 添加时删除本地文件
     * @param Request $request
     * @return false|string
     */
    public function addDeleteImg(Request $request)
    {
        $cur_logo_path = $request->input('cur_logo_path');
        $cur_logo_path_thumb = $request->input('cur_logo_path_thumb');
        if($cur_logo_path && $cur_logo_path_thumb) {
            $cur_logo_path_all =  public_path($cur_logo_path);
            if(file_exists($cur_logo_path_all)) {
                unlink($cur_logo_path_all);
            }
            $cur_logo_path_thumb_all =  public_path($cur_logo_path_thumb);
            if(file_exists($cur_logo_path_thumb_all)) {
                unlink($cur_logo_path_thumb_all);
            }
            return  res_success();
        }
        return res_fail('no image ');
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
            'goods_name'        => 'required|max:255',
            'category_id'       => 'required|integer',
            'brand_id'          => 'required|integer',
            'type_id'           => 'required|integer',
            'goods_img'         => 'required',
            'goods_thumb_img'   => 'required',
            'shop_price'        => 'required',
        ],[
            'goods_name.required'   => '分类必传',
            'goods_name.max'        => '标题应小于255个字！',
        ]);

        $req_data = $request->input();
//        $form_param = $request->input('form_param');
//        $form_data = parse_query($form_param);
        $goods_name          = $req_data['goods_name'];
        $brand_id            = $req_data['brand_id'];
        $category_id         = $req_data['category_id'];
        $ext_cat_id          = $req_data['ext_cat_id'] ?? '';
        $goods_img           = $req_data['goods_img'];
        $goods_thumb_img     = $req_data['goods_thumb_img'];
        $shop_price          = $req_data['shop_price'];
        $jifen               = $req_data['jifen'];
        $jifen_price         = $req_data['jifen_price'];
        $jyz                 = $req_data['jifen'];
        $is_hot              = $req_data['is_hot'];
        $is_promote          = $req_data['is_promote'];
        $promote_price       = $req_data['promote_price'];
        $promote_start_time  = $req_data['promote_start_time'];
        $promote_end_time    = $req_data['promote_end_time'];
        $is_new              = $req_data['is_new'];
        $is_best             = $req_data['is_best'];
        $is_on_sale          = $req_data['is_on_sale'];
        $seo_keyword         = $req_data['seo_keyword'] ?? '';
        $seo_description     = $req_data['seo_description'] ?? '';
        $type_id             = $req_data['type_id'];
        $goods_desc          = $req_data['goods_desc'] ?? '';

        $insert_arr = [
            'goods_name'      => $goods_name,
            'goods_number'    => uniqid(),
            'category_id'     => $category_id,
            'brand_id'        => $brand_id,
            'goods_img'       => $goods_img,
            'goods_thumb_img' => $goods_thumb_img,
            'shop_price'      => $shop_price,
            'jifen'           => $jifen,
            'jyz'             => $jyz,
            'jifen_price'     => $jifen_price,
            'is_promote'      => $is_promote,
            'is_hot'          => $is_hot,
            'is_new'          => $is_new,
            'is_best'         => $is_best,
            'is_on_sale'      => $is_on_sale,
            'seo_keyword'     => $seo_keyword,
            'seo_description' => $seo_description,
            'type_id'         => $type_id,
            'goods_desc'      => $goods_desc,
            'addtime'         => time(),

        ];

        if ($is_promote == 1) {
            $insert_arr['promote_price']      = $promote_price;
            $insert_arr['promote_start_time'] = strtotime($promote_start_time . ' 00:00:00');
            $insert_arr['promote_end_time']   = strtotime($promote_end_time . ' 23:59:59');
        }
        DB::beginTransaction();
        try {
            $new_goods_id = DB::table('goods')->insertGetId($insert_arr);
            /**
             * 会员价
             */
            $member_price = $req_data['member_price'] ?? [];
            if (!empty($market_price)) {
                $mp_insert_arr = [];
                foreach ($member_price as $k => $v) {
                    $temp = [
                        'goods_id' => $new_goods_id,
                        'level_id' => $k,
                        'price'    => $v,
                    ];
                    array_push($mp_insert_arr,$temp);
                }
                DB::table('member_price')->insert($mp_insert_arr);
            }
            /**
             * 商品拓展分类
             */
            $ext_cat_id_arr = [];
            if (!empty($ext_cat_id)) {
                $ext_cat_id_arr = explode(',',$ext_cat_id);
            }
            if (!empty($ext_cat_id_arr)) {
                $ext_cat_insert_arr = [];
                foreach ($ext_cat_id_arr as $k => $v) {
                    $temp = [
                        'goods_id'            => $new_goods_id,
                        'category_id'         => $v,
                    ];
                    array_push($ext_cat_insert_arr,$temp);
                }
                DB::table('goods_ext_category')->insert($ext_cat_insert_arr);
            }
            /**
             * 商品属性
             */
            // 销售属性
            $sale_attr = $request->input('goods_sale_attr_arr');

            if (!empty($sale_attr)) {
                $sale_attr_ids = explode(',',$sale_attr);
                $sale_attr_id_arr = array_unique($sale_attr_ids);
                $attr_sale_value_data = DB::table('attr_sale_value')->whereIn('id', $sale_attr_id_arr)->get();
                $goods_sale_attr_insert = [];
                foreach ($attr_sale_value_data as  $v) {
                    $temp = [
                        'goods_id'        => $new_goods_id,
                        'attr_id'         => $v->attribute_id,
                        'attr_sale_id'    => $v->id,
                        'attr_sale_value' => $v->attr_name_value,
                        'type_id'         => $type_id,
                    ];
                    array_push($goods_sale_attr_insert, $temp);

                }
                DB::table('goods_sale_attr')->insert($goods_sale_attr_insert);
            }
            // 产品规格
            $goods_desc_attr = $request->input('goods_desc_attr');
            if (!empty($goods_desc_attr)) {
                $goods_desc_attr_arr = json_decode($goods_desc_attr, true);
                $goods_desc_attr_insert = [];
                foreach ($goods_desc_attr_arr as  $attr_id => $attr_value) {
                    $temp = [
                        'goods_id'   => $new_goods_id,
                        'attr_id'    => $attr_id,
                        'attr_value' => $attr_value,
                        'type_id'    => $type_id,
                    ];
                    array_push($goods_desc_attr_insert, $temp);
                }
                DB::table('goods_desc_attr')->insert($goods_desc_attr_insert);
            }
            /**
             * 相册
             */
            $album_path = $req_data['album_path'] ?? '';
            $album_path_arr = [];
            if (!empty($album_path)) {
                $album_path_arr = json_decode($album_path, true);
            }
            $album_thumb_path = $req_data['album_thumb_path'] ?? '';
            $album_thumb_path_arr = [];
            if (!empty($album_thumb_path)) {
                $album_thumb_path_arr = json_decode($album_thumb_path, true);
            }
            if (!empty($album_path_arr)) {
                $goods_ext_img_insert_arr = [];
                foreach ($album_path_arr as $k => $v) {
                    $temp = [
                        'goods_id'            => $new_goods_id,
                        'goods_ext_img'       => $v,
                        'goods_ext_thumb_img' => $album_thumb_path_arr[$k] ?? '',
                    ];
                    array_push($goods_ext_img_insert_arr,$temp);
                }
                DB::table('goods_ext_img')->insert($goods_ext_img_insert_arr);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return res_fail($exception->getMessage());
        }
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
        $goodsId =  $request->input('id');
        $goodsData = DB::table('goods')->where('id','=', $goodsId)->first();
        $goodsData = (array)$goodsData;
        if (empty($goodsData)) {
             return res_fail('非法攻击');
        }
        $brandData = DB::table('brand')->where('is_del','=',0)->get();
        $typeData = DB::table('type')->where('is_del','=',0)->get();
        $categoryData = DB::table('goods_category')->where('is_del','=',0)->get();
        $arrayTool = new ArrayTool();
        $categoryData = $arrayTool->getTreeRec($categoryData);
        $memberLevelData = DB::table('member_level')->where('is_del','=',0)->get();
        // 拓展分类
        $goodsExtCate = DB::table('goods_ext_category')->where('goods_id','=',$goodsId)->get();
        $goodsExtCateIds = [];
        foreach ($goodsExtCate as $v) {
            if (!in_array($v->category_id, $goodsExtCateIds)) {
                $goodsExtCateIds[] = $v->category_id;
            }
        }
        // 会员价
        $memberPrice = DB::table('member_price')->where('goods_id','=',$goodsId)->get();
        $memberPriceData = [];
        foreach ($memberPrice as $k => $v) {
            $memberPriceData[$v->level_id] = $v->price;
        }
        // 商品销售属性

        $saleAttrData =  Attribute::where([
                                    ['is_del', '=', 0],
                                    ['attr_type', '=', 2],
                                    ['type_id', '=', $goodsData['type_id']],
                                ])
                                ->with('attrSaleValue')
                                ->get()
                                ->toArray();
        $hasSelectSaleAttr = DB::table('goods_sale_attr AS gsa')
            ->leftJoin('attribute','attribute.id','=','gsa.attr_id')
            ->select('gsa.*','attribute.attr_name')
            ->where('gsa.goods_id', $goodsId)
            ->orderBy('gsa.attr_id')->get()
            ->toArray();
        $hasSelectSaleAttr1 = GoodsSaleAttr::from('goods_sale_attr AS gsa')
            ->leftJoin('attribute','attribute.id','=','gsa.attr_id')
//            ->select('gsa.*','attribute.attr_name')
            ->where('gsa.goods_id', $goodsId)
            ->orderBy('gsa.attr_id')->get()
            ->toArray();

        $selectSaleAttr = [];
        foreach ($hasSelectSaleAttr as $v) {
            $v = (array)$v;
            $v['attr_sale_value'] = AttrSaleValue::where('attribute_id',$v['attr_id'])->pluck('attr_name_value','id')->toArray();

            $selectSaleAttr[$v['attr_id']][] = $v;

        }
        $selectSaleAttrIds = array_keys($selectSaleAttr);
        $notSelectSaleAttr = [];
        foreach ($saleAttrData as $v) {
            if (!in_array($v['id'], $selectSaleAttrIds)) {
                $notSelectSaleAttr[] = $v;
            }
        }

        /*
         // 商品属性
        $goodsAttr = DB::table('goods_attr AS ga')
            ->select(['ga.id','ga.attr_id','ga.attr_value','a.attr_name','a.attr_type','a.attr_option_values'])
            ->leftJoin('attribute AS a','a.id','=','ga.attr_id')
            ->where('ga.goods_id','=',$goodsId)
            ->orderBy('ga.attr_id')
            ->get();
        $attrIdArr = [];
        $goodsAttrArr = [];
        foreach ($goodsAttr as $v) {
            if (!in_array($v->attr_id, $attrIdArr)) {
                $attrIdArr[] = $v->attr_id;
            }
            $goodsAttrArr[] = (array)$v;
        }
        // 其他属性，1.添加该商品后，该属性分类又增添新的属性，2.添加时未选择的属性
        $otherAttrData = DB::table('attribute')
            ->select(['id AS attr_id','attr_name','attr_type','attr_option_values'])
            ->where('type_id','=',$goodsData['type_id'])
            ->where('is_del','=',0)
            ->whereNotIn('id',$attrIdArr)
            ->get();

        $otherAttrArr = [];
        foreach ($otherAttrData as $v) {
            $otherAttrArr[] = (array)$v;
        }
        if ($otherAttrData) {
            $goodsAttrArr = array_merge($goodsAttrArr,$otherAttrArr);
            usort($goodsAttrArr,function ($a, $b) {
                if ($a['attr_id'] == $b['attr_id']) {
                    return 0 ;
                }
                return $a['attr_id'] >  $b['attr_id'] ? 1 : -1;
            });
        }*/
        // 相册
        $goodsExtImg = DB::table('goods_ext_img')->where('goods_id','=',$goodsId)->get();
        $goodsExtImgData = [];
        foreach ($goodsExtImg as $k => $v) {
            $goodsExtImgData[] = (array)$v;
        }
        $ret = [
            'goodsData'        => $goodsData,
            'brandData'        => $brandData,
            'typeData'         => $typeData,
            'categoryData'     => $categoryData,
            'memberLevelData'  => $memberLevelData,
            'goodsExtCateIds'  => $goodsExtCateIds,
            'memberPriceData'  => $memberPriceData,
            'selectSaleAttr'   => $selectSaleAttr,
            'goodsExtImgData'  => $goodsExtImgData,
        ];

        return view('backend.goods.edit', $ret);
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
            'id'                => "required",
            'goods_name'        => 'required|max:255',
            'category_id'       => 'required|integer',
            'brand_id'          => 'required|integer',
            'type_id'           => 'required|integer',
            'shop_price'        => 'required',
        ],[
            'goods_name.required'   => '分类必传',
            'goods_name.max'        => '标题应小于255个字！',
        ]);

        $req_data = $request->input();
        $form_param = $request->input('form_param');
        $form_data = parse_query($form_param);
        $goods_id            = $req_data['id'];
        $goods_name          = $req_data['goods_name'];
        $brand_id            = $req_data['brand_id'];
        $category_id         = $req_data['category_id'];
        $ext_cat_id          = $req_data['ext_cat_id'] ?? '';
        $shop_price          = $req_data['shop_price'];
        $jifen               = $req_data['jifen'];
        $jifen_price         = $req_data['jifen_price'];
        $jyz                 = $req_data['jifen'];
        $is_hot              = $req_data['is_hot'];
        $is_promote          = $req_data['is_promote'];
        $is_new              = $req_data['is_new'];
        $is_best             = $req_data['is_best'];
        $is_on_sale          = $req_data['is_on_sale'];
        $seo_keyword         = $req_data['seo_keyword'] ?? '';
        $seo_description     = $req_data['seo_description'] ?? '';
        $type_id             = $req_data['type_id'];
        $goods_desc          = $req_data['goods_desc'] ?? '';

        $goods = Goods::find($goods_id);

        if (empty($goods)) {
            return res_fail('非法攻击');
        }
        DB::beginTransaction();
        try {

            $update_arr = [
                'goods_name'      => $goods_name,
                'category_id'     => $category_id,
                'brand_id'        => $brand_id,
                'shop_price'      => $shop_price,
                'jifen'           => $jifen,
                'jyz'             => $jyz,
                'jifen_price'     => $jifen_price,
                'is_promote'      => $is_promote,
                'is_hot'          => $is_hot,
                'is_new'          => $is_new,
                'is_best'         => $is_best,
                'is_on_sale'      => $is_on_sale,
                'seo_keyword'     => $seo_keyword,
                'seo_description' => $seo_description,
                'type_id'         => $type_id,
                'goods_desc'      => $goods_desc,

            ];

            // 商品属性改变
            if ($type_id != $goods->type_id) {
                $b = 3;
//                DB::table('goods_attr')->where('goods_id', $goods_id)->delete();
            }

            // 商品属性
            $goods_attribute_arr = $request->input('goods_attribute_arr'); // 属性类型
            $attribute_price_arr = $request->input('attribute_price_arr'); // 属性价格
            if (!empty($goods_attribute_arr)) {
                $goods_attr_insert_arr = [];
                foreach ($goods_attribute_arr as $attr_id => $attr_value) {
                    foreach ($attr_value as $k1 => $v1) {
                        if (empty($v1)) {
                            continue;
                        }
                        $price = isset($attribute_price_arr[$attr_id][$k1]) ? $attribute_price_arr[$attr_id][$k1] : 0;
                        $temp = [
                            'goods_id'   => $goods_id,
                            'attr_id'    => $attr_id,
                            'attr_value' => $v1,
                            'attr_price' => $price,
                        ];
                        array_push($goods_attr_insert_arr,$temp);
                    }
                }
//                DB::table('goods_attr')->insert($goods_attr_insert_arr);
            }

            $old_goods_attribute_arr = $request->input('old_goods_attribute_arr'); // 属性类型
            $old_attribute_price_arr = $request->input('old_attribute_price_arr'); // 属性价格

            foreach ($old_goods_attribute_arr as $attr_id => $attr_value) {
                foreach ($attr_value as $k1 => $v1) {
                    // 要修改的字段
                    $old_field = ['attr_value' => $v1];
                    if (isset($old_attribute_price_arr[$attr_id])) {
                        $old_field['attr_price'] = $old_attribute_price_arr[$attr_id][$k1];
                    }
                    $c = $old_field;
//                    DB::table('goods_attr')->where('id', $k1)->update($attr_value);
                }
            }


            if ($is_promote == 1) {
                $update_arr['promote_price']      = $req_data['promote_price'];
                $update_arr['promote_start_time'] = strtotime($req_data['promote_start_time'] . ' 00:00:00');
                $update_arr['promote_end_time']   = strtotime($req_data['promote_end_time'] . ' 23:59:59');
            }

            DB::table('goods')->where('id', $goods_id)->update($update_arr);

            DB::table('goods_ext_category')->where('goods_id', $goods_id)->delete();
            if (!empty($ext_cat_id)) {
                $ecrData = [];
                foreach (explode(',' ,$ext_cat_id) as $eci) {
                    $ecrData[] = [
                        'goods_id'    => $goods_id,
                        'category_id' => $eci,
                    ];
                }
                DB::table('goods_ext_category')->insert($ecrData);
            }

            //会员价
            $member_price = $req_data['member_price'] ?? [];
            DB::table('member_price')->where('goods_id', $goods_id)->delete();
            if (!empty($market_price)) {
                $mp_insert_arr = [];
                foreach ($member_price as $k => $v) {
                    $temp = [
                        'goods_id' => $goods_id,
                        'level_id' => $k,
                        'price'    => $v,
                    ];
                    array_push($mp_insert_arr,$temp);
                }
                DB::table('member_price')->insert($mp_insert_arr);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


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


    /**
     * 商品属性
     * @param Request $request
     * @return false|string
     */
    public function ajaxGetAttr(Request $request)
    {
        $typeId = $request->input('type_id');
        /**
         * 销售属性，与价格和搜索有关系
         */
       /*
       $attrData = DB::table('attribute')
            ->where('is_del','=',0)
            ->where('attr_type','=',2)
            ->where('type_id','=',$typeId)
            ->orderBy('id')
            ->get();
       */

        $attrData =  Attribute::where([
            ['is_del', '=', 0],
            ['attr_type', '=', 2],
            ['type_id', '=', $typeId],
        ])->with('attrSaleValue')->get()->toArray();
        /**
         * 商品规格
         */
        $attrDescData = DB::table('attribute')
            ->where('attr_type','=',1)
            ->where('type_id','=',$typeId)
            ->orderBy('id')
            ->get();
        $ret = [
            'attrData' => $attrData,
            'attrDescData' => $attrDescData
        ];
        return res_success($ret);
    }
}
