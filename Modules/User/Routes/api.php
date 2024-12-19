<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});*/

Route::group(['prefix' => 'v1'], function () {
	Route::get('get-language/{lang}', 'API\V1\UserController@getLanguage');
	Route::post('login', 'API\V1\AuthController@login');
	Route::post('register', 'API\V1\UserController@register');
	Route::post('social-login', 'API\V1\UserController@socialLogin');
	Route::post('verify-account', 'API\V1\UserController@verifyAccount');
	Route::group(['middleware' => 'auth:api'], function(){
		Route::get('me', 'API\V1\UserController@organizationUserInformation');
		Route::post('changePassword','API\V1\PasswordResetController@changePassword');
	    Route::put('my-profile', 'API\V1\UserController@myProfile');
	    Route::post('update-fcm-token', 'API\V1\UserController@updateFcmToken');
	});

	Route::group([ 'prefix' => 'password' ], function () {
        Route::post('forget-password', 'API\V1\PasswordResetController@create');
        Route::get('find/{token}', 'API\V1\PasswordResetController@find');
        Route::post('reset', 'API\V1\PasswordResetController@reset');
    });
});
