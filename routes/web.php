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

// Authentication Routes...
Route::get('belepes', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('belepes', 'Auth\LoginController@login');
Route::post('kijelentkezes', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function() {
    // Megrendelők kontrolleres
    Route::get('/', 'CustomersController@index');
    Route::get('/megrendelok/uj', 'CustomersController@create');
    Route::get('/megrendelok/{id}', 'CustomersController@show');
    Route::delete('/megrendelok/{id}', 'CustomersController@delete');
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
    Route::delete('/termekek/{id}', 'ItemsController@delete');
    Route::post('/termekek', 'ItemsController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


