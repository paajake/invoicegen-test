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

Auth::routes(["register" => false]);

Route::middleware("auth")->group( function () {
    Route::get("/", "DashboardController@index")->name("dashboard");

    Route::resource("invoices", "InvoiceController");
    Route::post("timesheets/import", "TimesheetController@import")->name("timesheets.import");
    Route::get("timesheets/upload", "TimesheetController@upload")->name("timesheets.upload");
    Route::resource("timesheets", "TimesheetController");
    Route::resource("lawyers", "LawyerController");
    Route::resource("ranks", "RankController");
    Route::resource("clients", "ClientController");
    Route::resource("users", "UsersController");
});
