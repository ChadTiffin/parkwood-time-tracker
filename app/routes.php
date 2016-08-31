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

//////////////
//PAGES
//////////////

Route::get("/login/{status?}","HomeController@showLogin");
Route::get("/logout/{status?}","HomeController@logout");

Route::group(array("before" => "auth"), function(){
	Route::get('/', 'HomeController@showSummary');

	Route::get('logs/{userId?}/{dateStart?}/{dateEnd?}/{minShift?}/{maxShift?}', 'HomeController@showLogs');
	Route::get('settings', 'SettingsController@showSettings');

	///////////////
	// REQUESTS
	///////////////

	Route::post("punch-clock", 'HomeController@punchClock');
	Route::post("edit", "HomeController@editLog");
	Route::post("delete-log", "HomeController@deleteLog");

	Route::post("send-email-report", "HomeController@sendEmailReport");

	Route::post("settings/save-settings", "SettingsController@saveSettings");
	Route::post("settings/change-password", "SettingsController@changePassword");

});

Route::post("/login/process","HomeController@processLogin");