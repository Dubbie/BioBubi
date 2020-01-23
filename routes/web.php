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

// Authentikátor linkjei
Route::get('belepes', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('belepes', 'Auth\LoginController@login');
Route::post('kijelentkezes', 'Auth\LoginController@logout')->name('logout');

// Regisztrációs link... óvatosan
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

Route::group(['middleware' => 'auth'], function() {
    // Megrendelők kontrolleres
    Route::get('/', 'CustomersController@index');
    Route::get('/megrendelok/uj', 'CustomersController@create');
    Route::get('/megrendelok/{id}', 'CustomersController@show');
    Route::get('/megrendelok/{id}/szerkesztes', 'CustomersController@edit');
    Route::put('/megrendelok/{id}', 'CustomersController@update');
    Route::delete('/megrendelok/{id}', 'CustomersController@delete');
    Route::post('/megrendelok', 'CustomersController@store');

    // Megjegyzések kontrolleres
    Route::post('/megjegyzesek/uj', 'CommentsController@store');
    Route::put('/megjegyzesek/szerkesztes', 'CommentsController@update');
    Route::delete('/megjegyzesek/{id}', 'CommentsController@delete');

    // Megrendelő termékei kontrolleres
    Route::post('/termekek/megrendelo/', 'CustomerItemsController@store');
    Route::post('/termekek/megrendelo/betoltes', 'CustomerItemsController@loadNew');
    Route::delete('/termekek/megrendelo/{id}', 'CustomerItemsController@delete');
    Route::post('/termekek/megrendelo/{id}/teljesit', 'CustomerItemsController@complete');

    // Termék kontrolleres
    Route::get('/termekek', 'ItemsController@index');
    Route::get('/termekek/uj', 'ItemsController@create');
    Route::get('/termekek/{id}', 'ItemsController@show');
    Route::delete('/termekek/{id}', 'ItemsController@delete');
    Route::put('/termekek', 'ItemsController@update');
    Route::post('/termekek', 'ItemsController@store');

    // Teendők kontrolleres
    Route::post('/teendo/uj', 'AlertsController@store');
    Route::put('/teendo/uj', 'AlertsController@update');
    Route::post('/teendo/{id}/teljesit', 'AlertsController@complete');
    Route::delete('/teendo/{id}', 'AlertsController@delete');

    Route::get('/home', 'HomeController@index')->name('home');
});


