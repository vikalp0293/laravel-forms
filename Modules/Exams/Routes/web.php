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

Route::group(['middleware' => [],'prefix' => 'exams'], function () {

    

    Route::get('/', 'ExamsController@index');
    Route::get('/create', 'ExamsController@create');
    Route::post('/create', 'ExamsController@store');

    Route::get('/edit/{exam_id}', 'ExamsController@edit');
    Route::post('/edit/{exam_id}', 'ExamsController@update');

    Route::get('/delete/{exam_id}', 'ExamsController@destroy');
    
    Route::get('/get-topics-by-subject/{id}', 'ExamsController@getTopicBySubject');
    Route::get('/get-subtopics-by-topic/{id}', 'ExamsController@getSubtopicByTopic');
    
});
