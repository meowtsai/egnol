<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class member extends MY_Controller {
	
	function register_json()
	{
		return $this->g_user->register_json($this->game);
	}	
}
