<?php

namespace App\Algorithm\Queue;


class QueueArray
{
    // 队列的容量 如银行柜台业务员，办理业务，窗口只有五个，那么队列到容量只有5
    private $maxCapacity;
    private $queueContainer = [];

    public function __construct(int $maxCapacity)
    {
        $this->maxCapacity = $maxCapacity;
    }


    /**
     * 加入队列
     * @param $one
     * @return array
     */
    public function addQueue($one)
    {
        $ret = [
            'code' => 0,
            'msg' => "添加成功",
        ];
        if (count($this->queueContainer) == $this->maxCapacity) {
            $ret = [
                'code' => 1,
                'msg' => "以达到容量上限，不能添加到队列",
            ];
            return $ret;
        } else {
            array_push($this->queueContainer, $one);
            return $ret;
        }
    }

    public function removeQueue()
    {
        if (count($this->queueContainer) == 0) {

        } else {
            array_shift($this->queueContainer);
        }
    }


}