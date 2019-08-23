<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

// 127.0.0.1:80/
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'backend'],function ($route) {
    // 127.0.0.1:80/backend/test
    $route->get('test',function () {
        return 'backend test';
    });
    $route->get('login','Backend\LoginController@login');
    $route->group(['prefix'=>'user'],function ($route) {
        $route->get('index',function () {
            return 'mini user index';
        });
    });
});
