<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



Route::get('/', 'HomeController@showSummary');

Route::get('logs/{dateStart?}/{dateEnd?}/{minShift?}/{maxShift?}', 'HomeController@showLogs');

Route::post("punch-clock",'HomeController@punchClock');

Route::post("edit","HomeController@editLog");
Route::post("delete-log","HomeController@deleteLog");

Route::get("blade-test","HomeController@bladeTest");