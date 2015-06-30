<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
		
	function index()
	{		
		$this->_init_layout()->standard_view();
	}
}