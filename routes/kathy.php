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

Route::group(['prefix'=>'kathy', 'namespace' => 'Kathy'],function ($router) {
    $router->any('index','AdminController@index');
    Route::group(['prefix' => 'admin'], function($router){
        // 用户
        $router->get('index', 'AdminController@index');
        $router->post('create', 'AdminController@store');
        $router->get('show/{id}', 'AdminController@show');
        $router->patch('resume/{id}', 'AdminController@resume');
        $router->patch('forbid/{id}', 'AdminController@forbid');
        $router->put('update/{id}', 'AdminController@update');
        $router->delete('delete/{id}', 'AdminController@destroy');

        //角色管理
        $router->get('role', 'AdminController@role');
        $router->post('role', 'AdminController@storeRole');
        $router->put('role/{id}', 'AdminController@updateRole');
        $router->delete('role/{id}', 'AdminController@deleteRole');

        //权限管理
        $router->get('permission', 'AdminController@permission');
        $router->post('permission', 'AdminController@storePermission');
        $router->put('permission/{id}', 'AdminController@updatePermission');
        $router->delete('permission/{id}', 'AdminController@deletePermission');
    });
});
