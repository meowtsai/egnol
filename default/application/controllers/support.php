<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}


}
