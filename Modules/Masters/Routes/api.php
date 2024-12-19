<?php

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
Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'masters'], function () {

        Route::get('/countries', 'API\V1\MastersController@countries');
        Route::get('/currencies', 'API\V1\MastersController@currencies');
        Route::get('/banners', 'API\V1\MastersController@banners');

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'API\V1\MastersController@categories');
            Route::get('/{category_id?}', 'API\V1\MastersController@categoryDetails');
        });

        Route::group(['prefix' => 'destinations'], function () {
            Route::get('/', 'API\V1\MastersController@destinations');
            Route::get('/{destination_id?}', 'API\V1\MastersController@destinationDetails');
        });
    });
});
