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

Route::middleware("can:admin")->prefix("settings")->name("settings.")->group(function() {
    Route::group(["prefix" => "notifications", "as" => "notifications."], function() {
        Route::get('/', 'NotificationsController@index')->name("index");
        Route::get("/{id}", "NotificationsController@show")->name("show");
        Route::get("/{id}/{status}", "NotificationsController@update_status")->name("update_status");
        Route::post("/", "NotificationsController@store")->name("store");
        Route::put("/{id}", "NotificationsController@update")->name("update");
        Route::delete("/{id}", "NotificationsController@destroy")->name("destroy");
    });
});
