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

Route::group(['middleware' => [],'prefix' => 'stats'], function () {

    Route::get('/', 'StatsController@index');
    Route::post('/generate-teacher', 'StatsController@generateTeacherId');
    Route::get('/student-results', 'StatsController@studentResults');
    Route::get('/student-test-report/{uuid}', 'StatsController@studentTestReport');
    Route::get('/student-exam-result/{uuid}', 'StatsController@studentExamReport');

    Route::get('/test-results', 'StatsController@testResults');
    Route::get('/test-report/{exam_uuid}', 'StatsController@testReport');
    Route::get('/test-question-result/{exam_uuid}', 'StatsController@testQuestionResult');

    Route::get('/question-result', 'StatsController@questionReport');
    
});
