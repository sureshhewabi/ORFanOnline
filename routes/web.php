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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/input', function () {
    return view('input');
});


Route::get('/results', function () {
    return view('results');
});


Route::resource('input','InputController');


Route::post('/saveresult','ResultController@saveResult');

Route::get('/readresult','ResultController@readResultFile');


Route::get('/savedresultslist', function () {
    return view('resultsList');
});



Route::get('/previewsave/{result_id}', 'ResultController@previewResult')->name('savedresult');

Route::resource('clamp', 'ClampController');


