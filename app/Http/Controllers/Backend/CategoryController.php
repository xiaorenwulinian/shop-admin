<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends BackendBaseController
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
        $category = DB::table('category')->get()->toArray();
        $cateData = $this->getTreeRec($category);
        $ret = [
            'cateData' => $cateData
        ];
        return view('backend.category.lst', $ret);
    }

    private function getTreeRec($data, $parent_id = 0, $level = 0, $isClear = TRUE)
    {
        static $ret = array();
        if ($isClear) {
            $ret = array();
        }
        foreach ($data as $k => $v) {
            $v = (array)$v;
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                $this->getTreeRec($data, $v['id'], $level + 1, FALSE);
            }
        }
        return $ret;
    }

}
