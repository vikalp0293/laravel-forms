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

Route::group(['middleware' => [],'prefix' => 'masters'], function () {
    Route::group(['prefix' => 'subject'], function () {
        Route::get('/', 'SubjectController@index');
        Route::post('/add', 'SubjectController@store');
        Route::get('/get-subject', 'SubjectController@getSubject');
        Route::get('/delete', 'SubjectController@destroy');
        Route::post('/mass-update', 'SubjectController@massUpdate');
    });

    Route::group(['prefix' => 'grade'], function () {
        Route::get('/', 'GradeController@index');
        Route::post('/add', 'GradeController@store');
        Route::get('/get-grade', 'GradeController@getGrade');
        Route::get('/delete', 'GradeController@destroy');
        Route::post('/mass-update', 'GradeController@massUpdate');
    });

    Route::group(['prefix' => 'country'], function () {
        Route::get('/', 'CountryController@index');
        Route::post('/add', 'CountryController@store');
        Route::get('/get-country', 'CountryController@getCountry');
        Route::get('/delete', 'CountryController@destroy');
        Route::post('/mass-update', 'CountryController@massUpdate');
    });

    Route::group(['prefix' => 'state'], function () {
        Route::get('/', 'StateController@index');
        Route::post('/add', 'StateController@store');
        Route::get('/get-state', 'StateController@getState');
        Route::get('/delete', 'StateController@destroy');
        Route::post('/mass-update', 'StateController@massUpdate');
    });

    
});
