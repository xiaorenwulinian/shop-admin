<?php

namespace App\Http\Controllers\Backend;

use App\Common\Library\Tools\ExcelTool;
use App\Common\Library\Tools\StingTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test(Request $request)
    {
//        $this->String();
        $this->excel();
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
        ExcelTool::excelExport($data,$header,$field,$file_name);
    }
}
