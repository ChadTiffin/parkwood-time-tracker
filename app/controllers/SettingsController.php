<?php

class SettingsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

///////////////////
// 'VIEW' LOADERS
///////////////////

	public function showSettings()
	{

		$data['settings'] = DB::table("setting_values")
			->join("settings","settings.id","=","setting_values.setting_id")->get();

		$data['header_data'] = $this->compileHeaderData();

		return View::make('pages.settings',$data);
	}

///////////////
// REQUESTS
///////////////

	public function saveSettings()
	{
		$settings = Input::all();

		foreach ($settings as $key => $setting) {

			if ($key != "_token") {
				//file_put_contents("test.txt", $key."\n\n",FILE_APPEND);

				$fetched_setting = SettingValue::find($key);

				$fetched_setting->value = $setting;
				$fetched_setting->save();
			}
		}
	}

	public function changePassword()
	{
		$user = Auth::user();

		$user->password = Hash::make(Input::get("new-psw"));

		$user->save();
	}

}
