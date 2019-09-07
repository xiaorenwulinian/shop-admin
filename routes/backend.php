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
        /**
         * 商品属性
         */
        Route::group(['prefix'=>'attribute'],function ($route) {
            Route::get('lst','AttributeController@lst');              //列表
            Route::get('add','AttributeController@add');              // 添加显示
            Route::post('addStore','AttributeController@addStore');   // 添加保存
            Route::get('edit','AttributeController@edit');            // 修改显示
            Route::post('editStore','AttributeController@editStore'); // 修改保存
            Route::any('delete','AttributeController@delete');       // 删除
            Route::any('multiDelete','AttributeController@multiDelete');       // 批量删除
        });
        /**
         * 会员级别
         */
        Route::group(['prefix'=>'memberLevel'],function ($route) {
            Route::get('lst','MemberLevelController@lst');              //列表
            Route::get('add','MemberLevelController@add');              // 添加显示
            Route::post('addStore','MemberLevelController@addStore');   // 添加保存
            Route::get('edit','MemberLevelController@edit');            // 修改显示
            Route::post('editStore','MemberLevelController@editStore'); // 修改保存
            Route::any('delete','MemberLevelController@delete');       // 删除
            Route::any('multiDelete','MemberLevelController@multiDelete');       // 批量删除
        });
        /**
         * 商品品牌
         */
        Route::group(['prefix'=>'brand'],function ($route) {
            Route::get('lst','BrandController@lst');              //列表
            Route::get('add','BrandController@add');              // 添加显示
            Route::post('addStore','BrandController@addStore');   // 添加保存
            Route::get('edit','BrandController@edit');            // 修改显示
            Route::post('editStore','BrandController@editStore'); // 修改保存
            Route::any('delete','BrandController@delete');        // 删除
            Route::any('multiDelete','BrandController@multiDelete');   // 批量删除
            Route::any('addUpload','BrandController@addUpload');       // 添加时上传图片
            Route::any('addDeleteImg','BrandController@addDeleteImg'); // 添加时删除图片
            Route::any('editUpload','BrandController@editUpload');       // 修改时上传图片
            Route::any('editDeleteImg','BrandController@editDeleteImg'); // 修改时删除图片
        });


        /**
         * 商品
         */
        Route::group(['prefix'=>'goods'],function ($route) {
            Route::get('lst','GoodsController@lst');              //列表
            Route::get('add','GoodsController@add');              // 添加显示
            Route::post('addStore','GoodsController@addStore');   // 添加保存
            Route::get('edit','GoodsController@edit');            // 修改显示
            Route::post('editStore','GoodsController@editStore'); // 修改保存
            Route::any('delete','GoodsController@delete');        // 删除
            Route::any('multiDelete','GoodsController@multiDelete');   // 批量删除
            Route::any('addUploadOne','GoodsController@addUploadOne');   // 批量删除
            Route::any('addUploadMulti','GoodsController@addUploadMulti');   // 批量删除
            Route::any('addDeleteImg','GoodsController@addDeleteImg');   // 批量删除

        });

        Route::group(['prefix'=>'user'],function ($route) {
            Route::get('index',function () {
                return 'mini user index';
            });
        });


        # 用户管理
        Route::group(['prefix'=>'admin'],function ($route) {
            Route::get('lst', 'AdminController@lst')->name('admin');              //列表
            Route::match(['get', 'post'], 'add', 'AdminController@add');                // 新增
            Route::match(['get', 'post'], 'edit/{id}', 'AdminController@edit');         // 编辑
            Route::get('delete/{id}', 'AdminController@delete');                        // 删除
            Route::post('changeStatus', 'AdminController@changeStatus');                // 修改状态
        });

        # 角色管理
        Route::group(['prefix'=>'role'],function ($route) {

        });

        # 权限管理
        Route::group(['prefix'=>'privilege'],function ($route) {

        });

    });


});
