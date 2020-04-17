<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ret = $this->test04();
        dd($ret);
    }

    public function test04()
    {
        for ($i = 0; $i < 10; $i++) {
            $num = 'lcljob_'. ($i + 10);
            TestJob::dispatch($num)->onQueue('testjob')->delay(300);
        }
        return 'ok';
    }

    public function test01()
    {
        $nums = [1,3,4,5];
        $target = 6;
        $len = count($nums);
        $ret = [];
        for ($i = 0; $i < $len - 1; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                if ($nums[$i] + $nums[$j] == $target) {
                    $ret = [$i, $j];
                    break;
                }
            }
        }
        return $ret;
    }
    public function test02()
    {
        $nums = [1,3,4,5];
        $target = 6;
        $len = count($nums);
        $newArr = [];
        $ret = [];
        for ($i = 0; $i < $len - 1; $i++) {
            $adapt = $target - $nums[$i];
            if (in_array($adapt,$nums) && $newArr[$adapt] != $i) {
                $ret = [$i, $newArr[$adapt]];
                break;
            }
        }
        return $ret;
    }

    public function test03()
    {
        $l1 = [2,4,3];
        $l2 = [5,6,4];

        $sumStr = (string)((int)implode('',array_reverse($l1)) + (int)implode('',array_reverse($l2)));
        $len = strlen($sumStr);
        $arr = [];
        for ($i = 0; $i < $len; $i++) {
            array_unshift($arr,$sumStr[$i]);
        }

        return $arr;
    }
}
