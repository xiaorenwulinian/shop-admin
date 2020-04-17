<?php

namespace App\Common\LclFacades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LclLogFacades
 * @package App\Common\LclFacades
 * @method static info
 */
class LclLogFacades extends Facade {
   protected static function getFacadeAccessor()
   {
       return 'LclLog'; // 定义门面别名
   }
}
