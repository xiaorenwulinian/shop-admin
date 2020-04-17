<?php

namespace App\Http\Controllers\Backend;

use App\Common\Library\Tools\FirebaseJwtToken;
use App\Common\Library\Tools\StingTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request)
    {
//        $this->partitionKey();
//        $this->String();
//        $this->excel();
    }

    public function fillData()
    {
        // 销售属性
        $saleAttr = DB::table('attribute')->where('attr_type','=',1)->get();
        foreach ($saleAttr as $v) {

        }
    }

    public function arrayTools()
    {
        $needle = '';
        $haystack = "jav_java_orjavascript_or_ja";
//        $haystack
    }

    /**
     * 测试 性能
     */
    private function partitionKey()
    {
        set_time_limit(0);
        $begin = time();

        for ($i = 0 ; $i < 50000; $i++) {
            $insert = [];
            for ($j = 0 ; $j < 1000 ; $j++ ) {
                $temp = [
                    'age' => rand(0,100),
                    'title' => StingTool::randCode(8,false),
                ];
                array_push($insert, $temp);
            }
            DB::table('test_partition_key_02')->insert($insert);
        }


        /*
        $data= DB::table('test_partition_key_01')
            ->whereBetween('age',[50,80])
            ->limit(100)
            ->get();
        */

        $end = time();
        $gap = $end - $begin ;
        // 1000 , key_01 === 379s
        // 1000 , key_02 === 400s
//        $inset= DB::table('test_partition_key_01')->first();
        dd($gap);
    }
    private function String()
    {
        //随机字符串
        $randString = StingTool::randCode(6,true);
        dd($randString);
    }

    private function excel()
    {

        // 导出的数据集，二维数组
        $data = [
            ['id'=>1,'art_title'=>'aaa','add_time'=>'2019-05-11'],
            ['id'=>2,'art_title'=>'bbb','add_time'=>'2019-05-12'],
            ['id'=>3,'art_title'=>'ccc','add_time'=>'2019-05-13'],
            ['id'=>4,'art_title'=>'ddd','add_time'=>'2019-05-14'],
        ];
        //第一行标题头
        $header = [
            'id值','标题','添加时间'
        ];
        // 数组的每一列标题对应的字段
        $field = [
            'id', 'art_title','add_time'
        ];
        //导出的文件名称
        $file_name = '文章列表'.date('Y-m-d-H-i-s');
        FirebaseJwtToken::excelExport($data,$header,$field,$file_name);
    }
}
