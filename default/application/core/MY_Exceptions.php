<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {
	
	function log_exception($severity, $message, $filepath, $line)
	{
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

		log_message('error', '('.$_SERVER["REMOTE_ADDR"].') '.$_SERVER['REQUEST_URI'].' --> Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line, TRUE);
	}
	
	function show_404($page = '', $log_error = TRUE)
	{
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";

		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			//log_message('error', '('.$_SERVER["REMOTE_ADDR"].') '.$_SERVER['REQUEST_URI'].' --> 404 Page Not Found');
		}

		echo $this->show_error($heading, $message, 'error_404', 404);
		exit;
	}
	
	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		$ip = empty($_SERVER["REMOTE_ADDR"]) ? '' :  $_SERVER["REMOTE_ADDR"];
		
		if ( ! in_array($ip, array('::1'))) {
			if ($status_code == 404) {
				log_message('error', "({$ip}) {$_SERVER['REQUEST_URI']} --> {$heading}");
			}
			else {
				log_message('error', "({$ip}) {$_SERVER['REQUEST_URI']} {$status_code} --> {$heading} : {$message}");
			}
		}
		
		set_status_header($status_code);

		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include(APPPATH.'errors/'.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

}