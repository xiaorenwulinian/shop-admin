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

Route::group(['prefix'=>'backend','namespace'=>'Backend'],function ($route) {
    // 127.0.0.1:80/backend/test

    Route::get('mainContent','IndexController@mainContent');
    Route::get('test','TestController@test');

    Route::get('login','LoginController@login');
    Route::post('loginSubmit','LoginController@loginSubmit');

    Route::group(['middleware' => 'backend_login'], function ($route) {
        Route::get('/','IndexController@index');
        Route::group(['prefix'=>'category'],function ($route) {
            Route::get('lst','CategoryController@lst');              //列表
            Route::get('add','CategoryController@add');              // 添加显示
            Route::post('addStore','CategoryController@addStore');   // 添加保存
            Route::get('edit','CategoryController@edit');            // 修改显示
            Route::post('editStore','CategoryController@editStore'); // 修改保存
            Route::post('delete','CategoryController@delete');       // 删除
        });

        Route::group(['prefix'=>'article'],function ($route) {
            Route::get('lst','ArticleController@lst');              // 列表
            Route::get('add','ArticleController@add');              // 添加显示
            Route::post('addStore','ArticleController@addStore');   // 添加保存
            Route::get('edit','ArticleController@edit');            // 修改显示
            Route::post('editStore','ArticleController@editStore'); // 修改保存
            Route::post('delete','ArticleController@delete');       // 删除单个
            Route::post('multiDelete','ArticleController@multiDelete');       // 批量删除
        });

        /**
         * 商品分类
         */
        Route::group(['prefix'=>'goodsCategory'],function ($route) {
            Route::get('lst','GoodsCategoryController@lst');              //列表
            Route::get('add','GoodsCategoryController@add');              // 添加显示
            Route::post('addStore','GoodsCategoryController@addStore');   // 添加保存
            Route::get('edit','GoodsCategoryController@edit');            // 修改显示
            Route::post('editStore','GoodsCategoryController@editStore'); // 修改保存
            Route::post('delete','GoodsCategoryController@delete');       // 删除
        });

        /**
         * 商品属性分类
         */
        Route::group(['prefix'=>'type'],function ($route) {
            Route::get('lst','TypeController@lst');              //列表
            Route::get('add','TypeController@add');              // 添加显示
            Route::post('addStore','TypeController@addStore');   // 添加保存
            Route::get('edit','TypeController@edit');            // 修改显示
            Route::post('editStore','TypeController@editStore'); // 修改保存
            Route::any('delete','TypeController@delete');       // 删除
            Route::any('multiDelete','TypeController@multiDelete');       // 批量删除
        });

        Route::group(['prefix'=>'user'],function ($route) {
            Route::get('index',function () {
                return 'mini user index';
            });
        });
    });


});
