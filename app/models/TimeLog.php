<?php

class TimeLog extends Eloquent {
	public $timestamps = false;

	/**
	* Return a filtered list of time logs
	* @return array 
	*
	*
	*
	*/
	public function getFilteredLogs($dateStart,$dateEnd,$minShift,$maxShift)
	{
		$q = "SELECT id, clocked_in, clocked_out, ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out)) as shift_total FROM time_logs WHERE
				DATE(clocked_in) >= ? AND
				DATE(clocked_in) <= ? AND
				(ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out))/60 > ? AND
				ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out))/60 < ?)
				ORDER BY clocked_in ASC";

		$logs = DB::select($q,array($dateStart,$dateEnd,$minShift,$maxShift));

		$query_total = 0;
		foreach ($logs as $log) {
			$query_total += $log->shift_total;
		}

		return array("total" => $query_total, "results" => $logs);
	}
}