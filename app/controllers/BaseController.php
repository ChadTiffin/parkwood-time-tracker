<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}


	/**
	 * Checks if user is clocked in or not. If clocked in, return clock-in time, else return false
	 *
	 * @return datetime/boolean
	 */
	public function clockedInTime()
	{
		if (Auth::check()) {
			$open_logs = TimeLog::where("clocked_out","=", null)->where("user_id","=",Auth::user()->id)->take(1)->get();

			if (count($open_logs) != 0) {
				return $open_logs[0]->clocked_in;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	/**
	* Set all data common to all views
	* @return array
	*
	*
	*/
	public function compileHeaderData()
	{
		$clock_time = $this->clockedInTime();

		if (!$clock_time) {
			$data['clock_direction'] = "IN";
			$data['status'] = "Clocked out.";
			$data['clock_btn_type'] = "btn-success";
		}
		else {
			
			$data['clock_direction'] = "OUT";
			$data['status'] = "Clocked in at ".date("h:i A",strtotime($clock_time));
			$data['clock_btn_type'] = "btn-warning";
		}

		return $data;
	}

}
