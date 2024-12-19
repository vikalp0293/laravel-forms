<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes(['verify' => true]);

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/', '\Modules\Dashboard\Http\Controllers\DashboardController@index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);


Route::get('/terms', [App\Http\Controllers\HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [App\Http\Controllers\HomeController::class, 'privacy'])->name('privacy');
Route::get('/help', [App\Http\Controllers\HomeController::class, 'help'])->name('help');

Route::get('/get-states-by-country/{id}', 'App\Http\Controllers\Auth\RegisterController@getStateByCountry');
Route::post('/registeruser', 'App\Http\Controllers\Auth\RegisterController@registerUser');


Route::get('/verify-account/{id}', 'App\Http\Controllers\Auth\RegisterController@verifyUser');

Route::post('post-login', 'App\Http\Controllers\Auth\LoginController@postLogin'); 
Route::group([ 'prefix' => 'password' ], function () {
    Route::post('send-link', 'App\Http\Controllers\Auth\ResetPasswordController@sendPasswordLink');
    Route::post('reset-password', 'App\Http\Controllers\Auth\ResetPasswordController@reset_password');
});
Route::get('forgot-password/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@find');



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/error', function (){
    return view('error/403');
});



Route::get('clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});