<?php

class Setting extends Eloquent {
	
	public static function getSetting($setting_name)
	{
		return Setting::where("setting_name", "=", $setting_name)->firstOrFail();
	}
}