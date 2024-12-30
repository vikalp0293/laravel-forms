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

Route::prefix('user')->group(function() {

    Route::get('/', 'UserController@index');
    Route::get('/new', 'UserController@newUsers');
    Route::get('/create', 'UserController@create');
    Route::post('/create', 'UserController@store');
    Route::post('/bulk-update', 'UserController@bulkUpdate');
    Route::post('/bulk-approve', 'UserController@bulkApprove');
    Route::get('/edit/{user_id}', 'UserController@edit');
    Route::post('/edit/{user_id}', 'UserController@update');
    Route::get('/delete/{user_id}', 'UserController@destroy');
    
    Route::get('/import', 'UserController@import');
    
    Route::get('/activity-logs', 'UserController@activityLogs');
    Route::get('/logs/{user_id}', 'UserController@logs');
    Route::post('/update-user-password', 'UserController@updateUserPassword');

    Route::get('/detail/{user_id}', 'UserController@show');
    Route::post('/check-user', 'UserController@checkUser');    
    
});

Route::get('/profile', 'UserController@profile');
Route::post('/profile', 'UserController@updateProfile');
Route::get('/profile/notification', 'UserController@notification');
Route::get('/profile/get-states/{id}', 'UserController@getStateByCountry');
Route::get('/profile/trigger-verification-email/{email}', 'UserController@sendVerificationEmail');
Route::get('/profile/verify-email/{id}', 'UserController@verifyEmail');
Route::get('/profile/setting', 'UserController@setting');
Route::post('/profile/setting', 'UserController@updatePassword');
