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

Route::get('login', 'UserController@check');
Route::post('login', 'UserController@login');

Route::group(['middleware'=>['jwt.verify']], function () {
    Route::get('user_detail', 'UserController@userDetail');
});

// 404 error in json
Route::get('/{blob}', function ($blob) {
    return returnMessage(false, 'Request error!');
});