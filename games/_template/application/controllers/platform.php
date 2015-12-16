<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller
{
	function index()
	{		
		$this->_init_layout()->standard_view("index");
	}
}
