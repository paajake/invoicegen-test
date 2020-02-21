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

Route::resource("timesheets", "TimesheetController")->middleware('auth');
Route::resource("lawyers", "LawyerController")->middleware('auth');
Route::resource("ranks", "RankController")->middleware('auth');
Route::resource("clients", "ClientController")->middleware('auth');
Route::resource("users", "UsersController")->middleware('auth');
