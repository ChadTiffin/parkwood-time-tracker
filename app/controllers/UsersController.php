<?php

class UsersController extends BaseController {

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

	public function showUsers()
	{

		$data['users'] = User::all();

		$data['header_data'] = $this->compileHeaderData();

		return View::make('pages.users',$data);
	}

	public function editUser()
	{
		$input = Input::all();

		$edit_type = "edit";
		if ($input['id'] != "") {
			$user = User::find($input['id']);
		}
		else {
			//create new user

			$user = new User;

			$user->password = Hash::make("password");

			$edit_type = "new";
		}

		$user->first_name = $input['edit-first-name'];
		$user->last_name = $input['edit-last-name'];
		$user->email = $input['edit-email'];

		$user->save();
		return $edit_type;
	}

}
