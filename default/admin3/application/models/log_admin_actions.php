<?php

class Log_Admin_Actions extends CI_Model {

	function insert_log($admin_uid, $function='', $action='', $desc='')
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
    		$ip = $_SERVER['REMOTE_ADDR'];

		$this->db->set("create_time", "now()", false)
			->set("admin_uid", $admin_uid)
			->set("function", $function)
			->set("action", $action)
			->set("desc", $desc)
			->set("ip", $ip)
			->insert("log_admin_actions");
	}
	
}