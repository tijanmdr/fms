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
    // User routes
    Route::get('user_detail', 'UserController@userDetail');
    Route::post('add_user', 'UserController@addUser');
    Route::patch('update_user', 'UserController@updateUser');
    Route::patch('update_user_pwd', 'UserController@updateUserPassword');
    Route::get('list_users', 'UserController@listUsers');

    // Food categories routes
    Route::post('create_category', 'FoodCategorySauceController@createCategory');
    Route::patch('update_category', 'FoodCategorySauceController@updateCategory');
    Route::delete('delete_category/{id}', 'FoodCategorySauceController@deleteCategory');
    Route::get('list_categories', 'FoodCategorySauceController@listCategories');

    // Food sauces routes
    Route::post('create_sauce', 'FoodCategorySauceController@createSauce');
    Route::patch('update_sauce', 'FoodCategorySauceController@updateSauce');
    Route::delete('delete_sauce/{id}', 'FoodCategorySauceController@deleteSauce');
    Route::get('list_sauces', 'FoodCategorySauceController@listSauces');

    // Food menu routes
    Route::post('create_food', 'FoodBeverageController@createFood');
    Route::patch('update_food', 'FoodBeverageController@updateFood');
    Route::delete('delete_food/{id}', 'FoodBeverageController@deleteFood');
    Route::get('list_foods', 'FoodBeverageController@listFoods');

    // Food beverages routes
    Route::post('create_beverage', 'FoodBeverageController@createBeverage');
    Route::patch('update_beverage', 'FoodBeverageController@updateBeverage');
    Route::delete('delete_beverage/{id}', 'FoodBeverageController@deleteBeverage');
    Route::get('list_beverages', 'FoodBeverageController@listBeverages');
});

// 404 error in json
Route::get('/{blob}', function ($blob) {
    return returnMessage(false, 'The url that you requested could not be found!');
});