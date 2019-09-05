<?php

namespace App\Common\Library\Tools;

class ArrayTool
{
    /**
     * 递归分级
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    public function getTreeRec($data, $parent_id = 0, $level = 0, $isClear = TRUE)
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

    /**
     * 获取所有子级
     * @param $data
     * @param int $parent_id
     * @param bool $isClear
     * @return array
     */
    public function getChildTreeRec($data, $parent_id = 0, $isClear = TRUE)
    {
        static $ret = array();
        if ($isClear) {
            $ret = array();
        }
        foreach ($data as $k => $v) {
            $v = (array)$v;
            if ($v['parent_id'] == $parent_id) {
                $ret[] = $v['id'];
                $this->getTreeRec($data, $v['id'],  $v['id'], FALSE);
            }
        }
        return $ret;
    }

}
