<?php

namespace App\Http\Controllers\Test;

use App\Common\LclFacades\LclLogFacades;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {

        $ret = LclLogFacades::info();
        return $ret;
    }
}
