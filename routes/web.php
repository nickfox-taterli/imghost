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

// 使用CloudFlare进行最大缓存(永久,最大优先级.)
Route::get('/', function () {
    return view('welcome');
});

// 使用CloudFlare设置不可缓存
Route::post('/store', 'FileController@store');

// 使用CloudFlare进行最大缓存
Route::get('/i/{id}', 'FileController@get');