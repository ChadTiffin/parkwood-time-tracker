<?php

class HomeController extends BaseController {

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

	public function showSummary()
	{

		$q_base = "SELECT SUM(ABS(TIMESTAMPDIFF(MINUTE, clocked_out, clocked_in))) as sum FROM time_logs WHERE ";

		/////////////////////////////
		// SUMMARY FOR THIS WEEK
		////////////////////////////////

		//find total hours for each day of week
		$last_sunday = date("Y-m-d",strtotime('last sunday'));

		//loop through next 7 days and totalize times for each day
		$daily_totals = array();
		$weekly_total = 0;

		for ($i=0; $i < 7; $i++) { 
			$current_iterated_day = new DateTime($last_sunday);
			$current_iterated_day->add(new DateInterval("P".$i."D"));

			$q = "$q_base DATE(clocked_in) = ?";
			$days_logs = DB::select($q,array($current_iterated_day->format("Y-m-d")));

			$daily_total = round($days_logs[0]->sum/60,1);

			if ($daily_total == 0) {
				$daily_total = "-";
			}
			else {
				$daily_total .= " hrs";
			}

			$daily_totals[] = $daily_total;
			$weekly_total += $daily_total;
		}

		$last_week_total = 0;

		$data['daily_totals'] = $daily_totals;
		$data['weekly_total'] = $weekly_total;

		/////////////////////////////
		// MONTHLY SUMMARY
		////////////////////////////////

		$current_year = date("Y");
		$monthly_totals = array();
		$monthly_total = 0;
		$year_total = 0;

		for ($i=0; $i < 12; $i++) { 
			$current_iterated_month = new DateTime("$current_year-01-01");
			$current_iterated_month->add(new DateInterval("P".$i."M"));

			$q = "$q_base MONTH(clocked_in) = ?";
			$months_logs = DB::select($q,array($current_iterated_month->format("m")));

			$monthly_total = round($months_logs[0]->sum/60,1);

			if ($monthly_total == 0) {
				$monthly_total = "-";
			}
			else {
				$monthly_total .= " hrs";
			}

			$monthly_totals[] = $monthly_total;
			$year_total += $monthly_total;
		}

		$data['monthly_totals'] = $monthly_totals;
		$data['year_total'] = $year_total;

		/////////////////////////////
		// TODAY'S LOGS
		/////////////////////////////

		$q = "SELECT clocked_in, clocked_out, ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out)) as shift_total FROM time_logs WHERE DATE(NOW()) = DATE(clocked_in)";
		$todays_logs = DB::select($q);

		$data['today_logs'] = $todays_logs;
		$data['yr'] = date("Y");

		$data['header_data'] = $this->compileHeaderData();
 
		return View::make('pages.summary',$data);
	}

	public function showLogs($dateStart = "2000-01-01", $dateEnd = "2100-01-01", $minShift = 0, $maxShift = 9999)
	{

		$timeLogObj = new TimeLog();
		$result = $timeLogObj->getFilteredLogs($dateStart,$dateEnd,$minShift,$maxShift);

		$data['header_data'] = $this->compileHeaderData();

		$data['logs'] = $result['results'];

		$data['query_total'] = round($result['total']/60,2);

		return View::make('pages.log_list',$data);
	}

//////////////////////////
// AJAX REQUESTS
//////////////////////////

	public function punchClock()
	{

		//check if there is an open log
		$time_log = $this->clockedInTime();

		$current_time = date("Y-m-d H:i:s");

		if (!$time_log) {
			//Punch user IN

			$time_log = new TimeLog;
			$time_log->clocked_in = $current_time;

			$time_log->save();

			echo format_datetime($current_time,"time");
		}
		else {
			//Punch user out

			$time_log = TimeLog::where("clocked_out","=", null)->take(1)->get();
			$id = $time_log[0]->id;

			$time_log = TimeLog::find($id);

			$time_log->clocked_out = $current_time;
			$time_log->save();

			echo "clocked out";
		}
	}

	public function editLog()
	{
		$cl_in = Input::get("edit-clocked-in");
		$cl_out = Input::get("edit-clocked-out");
		$log_id = Input::get("id");

		$time_log = TimeLog::find($log_id);

		if ($cl_out == "") {
			$cl_out = null;
		}

		$time_log->clocked_in = $cl_in;
		$time_log->clocked_out = $cl_out;

		if ($cl_out == null) {
			$cl_out = "[in progress]";
			$total_hrs = "...";
		}
		else {
			$total_hrs = round((strtotime($time_log->clocked_out) - strtotime($time_log->clocked_in))/60/60,2);
			$cl_out = format_datetime($cl_out,'time');
		}

		$time_log->save();

		$log = array(
			'date' => format_datetime($time_log->clocked_in),
			'clocked_in' => format_datetime($cl_in,"time"), 
			'clocked_out' => $cl_out,
			"total" => $total_hrs
		);

		echo json_encode($log);
	}

	public function deleteLog()
	{
		$log_id = Input::get('id');

		$log = TimeLog::find($log_id);
		$log->delete();
	}

	public function sendEmailReport()
	{
		$fields = Input::all();

		file_put_contents("test.txt", json_encode($fields));

		$timeLogObj = new TimeLog();
		$result = $timeLogObj->getFilteredLogs($fields['from-date'],$fields['to-date'],0,9999);

		$total_hrs = $result['total'];

		$date_range_text = date("F j",strtotime($fields['from-date']))." to ".date("F j",strtotime($fields['to-date']));

		$body = "<p>Hi,</p>  
			<p>My total hours from $date_range_text is ".round($total_hrs/60,1)." hrs.</p>
			<p>Cheers,</p>
			<p>Chad Tiffin</p>";

		if ($fields['report-type'] == "hrs only") {
			
		}
		elseif ($fields['report-type'] == "full") {

		}

		Mail::send("emails.plaintext",array("msg" => $body),function($message){
			$message->to("chad@chadtiffin.com")
				->from("chad@chadtiffin.com")
				->subject("Hours for Chad");
		});		

	}

/////////////////////////////
// PRIVATE FUNCTIONS
////////////////////////////

	/**
	 * Checks if user is clocked in or not. If clocked in, return clock-in time, else return false
	 *
	 * @return datetime/boolean
	 */
	private function clockedInTime()
	{
		$open_logs = TimeLog::where("clocked_out","=", null)->take(1)->get();

		if (count($open_logs) != 0) {
			return $open_logs[0]->clocked_in;
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
	private function compileHeaderData()
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
