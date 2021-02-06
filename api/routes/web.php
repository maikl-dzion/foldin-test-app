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


Route::get('/',
    ['uses' => 'MainController@index']);

Route::get('/v1/go/{rand_str}',
    ['uses' => 'MainController@redirectUrl']);

Route::get('/v1/get/links/{user_id}',
    ['uses' => 'MainController@getLinksByUserId']);

Route::post('/v1/post/user/register',
    ['uses' => 'MainController@userCreate']);

Route::post('/v1/post/auth/login',
    ['uses' => 'MainController@login']);

Route::post('/v1/post/add/link',
    ['uses' => 'MainController@addLink']);

Route::put('/v1/post/update/link/{link_id}',
    ['uses' => 'MainController@updateLink']);

Route::delete('/v1/post/delete/link/{link_id}',
    ['uses' => 'MainController@deleteLink']);

