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
    // Megrendelők kontrolleres
    Route::get('/', 'CustomersController@index');
    Route::get('/megrendelok/uj', 'CustomersController@create');
    Route::get('/megrendelok/{id}', 'CustomersController@show');
    Route::post('/megrendelok', 'CustomersController@store');

    // Megjegyzések kontrolleres
    Route::post('/megjegyzesek/uj', 'CommentsController@store');

    // Megrendelő termékei kontrolleres
    Route::post('/termekek/megrendelo/', 'CustomerItemsController@store');
    Route::post('/termekek/megrendelo/betoltes', 'CustomerItemsController@loadNew');
    Route::delete('/termekek/megrendelo/{id}', 'CustomerItemsController@delete');

    // Termék kontrolleres
    Route::get('/termekek', 'ItemsController@index');
    Route::get('/termekek/uj', 'ItemsController@create');
    Route::get('/termekek/{id}', 'ItemsController@show');
    Route::post('/termekek', 'ItemsController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


