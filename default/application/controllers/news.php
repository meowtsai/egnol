<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
//		$this->load->config("service");
	}

	function index()
	{
		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("server")
			->standard_view();
	}
	
	function get_news()
	{
		
	}
}
