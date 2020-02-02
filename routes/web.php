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

Auth::routes(['register' => false]);

Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('auth');

//Route::get('/users', "UsersController@index")->middleware('auth')->name('users.index');
//Route::get('/users/create', "UsersController@index")->middleware('auth')->name('users.create');
//Route::delete('/users', "UsersController@destroy")->middleware('auth')->name('users.destroy');

Route::resource("users", "UsersController")->middleware('auth');
