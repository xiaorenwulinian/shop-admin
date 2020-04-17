<?php

namespace App\Jobs;

use App\Common\Library\Tools\StingTool;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $timeout = 120;

    protected $num;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($num)
    {
        $this->num = $num;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $num = $this->num;
        $data = [
            'name'      => StingTool::randCode(12),
            'num'       => $num,
            'add_time'  => date('Y-m-d H:i:s'),
        ];
        DB::table('test_job')->insert($data);
    }
}
