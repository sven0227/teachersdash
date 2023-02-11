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

Route::prefix('coupons')->as("coupons.")->group(function() {
    Route::get('/', 'CouponsController@index')->name("index");
    Route::get("{id}", "CouponsController@show")->name("show");
    Route::post("/", "CouponsController@store")->name("store");
    Route::put("{id}", "CouponsController@update")->name("update");
    Route::delete("{id}", "CouponsController@destroy")->name("destory");
});
