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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('predict', ['as' => 'predict.index', 'uses' => 'App\Http\Controllers\PredictController@index']);  
	Route::post('predict.file', ['as' => 'predict.file', 'uses' => 'App\Http\Controllers\PredictController@predict_file']);
	Route::get('shield', ['as' => 'shield.index', 'uses' => 'App\Http\Controllers\ShieldController@index']);  
	Route::post('shield.file', ['as' => 'shield.file', 'uses' => 'App\Http\Controllers\ShieldController@predict_file']); 
	// Route::post('predict.webcam', ['as' => 'predict.webcam', 'uses' => 'App\Http\Controllers\PredictController@predict_webcam']); 
	Route::post('predict.file', ['as' => 'predict.file', 'uses' => 'App\Http\Controllers\PredictController@predict_file']); 
	Route::resource('dataset', App\Http\Controllers\DatasetController::class);
	Route::resource('result', App\Http\Controllers\ResultController::class);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	Route::get('map', function () {return view('pages.maps');})->name('map');
	Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

