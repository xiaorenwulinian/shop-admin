<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'test','namespace'=>'Test'],function ($route) {
    // 127.0.0.1:80/miniProgram/test
    $route->get('/',function () {
        return 'test test';
    });
    $route->group(['prefix'=>'user'],function ($route) {
        $route->get('index',function () {
            return 'mini user index';
        });
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
