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

Route::group(['prefix'=>'backend'],function ($route) {
    // 127.0.0.1:80/backend/test
    $route->get('/','Backend\IndexController@index');
    $route->get('mainContent','Backend\IndexController@mainContent');
    $route->get('test','Backend\TestController@test');

    $route->get('login','Backend\LoginController@login');
    $route->group(['prefix'=>'category','namespace'=>'Backend'],function ($route) {
        $route->get('lst','CategoryController@lst');              //列表
        $route->get('add','CategoryController@add');              // 添加显示
        $route->post('addStore','CategoryController@addStore');   // 添加保存
        $route->get('edit','CategoryController@edit');            // 修改显示
        $route->post('editStore','CategoryController@editStore'); // 修改保存
        $route->post('delete','CategoryController@delete');       // 删除
    });

    $route->group(['prefix'=>'article','namespace'=>'Backend'],function ($route) {
        $route->get('lst','ArticleController@lst');              //列表
        $route->get('add','ArticleController@add');              // 添加显示
        $route->post('addStore','ArticleController@addStore');   // 添加保存
        $route->get('edit','ArticleController@edit');            // 修改显示
        $route->post('editStore','ArticleController@editStore'); // 修改保存
        $route->post('delete','ArticleController@delete');       // 删除
    });
    $route->group(['prefix'=>'user'],function ($route) {
        $route->get('index',function () {
            return 'mini user index';
        });
    });
});