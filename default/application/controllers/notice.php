<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends MY_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->model("g_notices");
	}
	
	function get_list()
	{		
		$this->_require_login();
		$this->_init_layout();		
				
		$this->load->library('pagination');
		
		$this->pagination->initialize(array(
				'base_url'	=> site_url("bulletin/get_list/?"),
				'total_rows'=> $this->g_notices->get_count($this->g_user->uid),
				'per_page'	=> 20
		));		
		
		$query = $this->g_notices->get_list($this->g_user->uid, 20, $this->input->get("record"));
		$this->g_notices->set_read($this->g_user->uid);

		$this->g_layout
			->set_breadcrumb(array("通知"=>""))
			->set("subtitle", "通知")
			->set("query", $query);
			
		if (check_mobile()) {
			$this->g_layout->render("", "mobile");
		}
		else {
			$this->g_layout->render("", "inner2");
		}
					
	}
	
	function preview($id)
	{
		$this->_init_layout();
		$row = $this->g_notices->get_preview($id);
		if ($row == false) {die("無此記錄");}

		$this->g_layout
			->set_breadcrumb(array("通知"=>""))
			->set("subtitle", "通知")		
			->set("row", $row)
			->render("notice/detail", "inner2");		
	}
	
	function detail($id=0)
	{
		$this->_require_login();
		
		$this->_init_layout();
		$row = $this->g_notices->get_notice($this->g_user->uid, $id);
		if ($row == false) {die("無此記錄");}
		
		if ( ! empty($row->url)) {header("location: {$row->url}"); exit();}

		$this->g_layout
			->set_breadcrumb(array("通知"=>""))
			->set("subtitle", "通知")		
			->set("row", $row);			

		if (check_mobile()) {
			$this->g_layout->render("notice/detail", "mobile");
		}
		else {
			$this->g_layout->render("notice/detail", "inner2");
		}	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */