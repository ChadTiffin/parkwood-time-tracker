<?php

function format_datetime($date,$format = "date")
{
	if (is_numeric($date)) {
		$timestamp = $date;
	}
	else {
		$timestamp = strtotime($date);
	}

	if ($format == "time") {
		return date("H:i A", $timestamp);
	}
	else {
		return date("M j, Y l", $timestamp);
	}
	
}