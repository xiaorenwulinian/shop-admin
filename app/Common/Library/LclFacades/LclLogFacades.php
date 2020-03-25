<?php

namespace App\Common\Library\LclFacades;

use Illuminate\Support\Facades\Facade;

class LclLogFacades extends Facade {
   protected static function getFacadeAccessor()
   {
       return 'LclLog';
   }
}
