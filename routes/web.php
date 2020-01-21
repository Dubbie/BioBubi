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

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'CustomersController@index');
    Route::get('/megrendelo/uj', 'CustomersController@create');
    Route::get('/megrendelo/{id}', 'CustomersController@show');
    Route::post('/megrendelo', 'CustomersController@store');

    Route::post('/megjegyzesek/uj', 'CommentsController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


