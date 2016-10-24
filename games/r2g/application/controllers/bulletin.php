<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends MY_Controller {	
	
	function get_list($category_id=0)
	{		
		
		$this->load->model("g_bulletins");		
		$this->load->library('pagination');
		
		$this->pagination->initialize(array(
				'base_url'	=> site_url("bulletin/get_list/{$category_id}?"),
				'total_rows'=> $this->g_bulletins->get_count($this->game_id, $category_id),
				'per_page'	=> 50
		));		
		
		$this->_init_layout()
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list"))
			->set("query", $this->g_bulletins->get_list($this->game_id, $category_id, 50, $this->input->get("record")))		
			->event_view();
	}
	
	function preview($id)
	{
		
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_preview($this->game_id, $id);
		if ($row == false) {die("無此記錄");}

		$this->_init_layout()
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list", $row->bulletin_title=>""))
			->set("row", $row)
			->event_view("bulletin/detail");		
	}
	
	function detail($id)
	{
		
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_bulletin($this->game_id, $id);
		if ($row == false || $row->priority == 0) {die("無此記錄");}
		
		$this->_init_layout()
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list", $row->bulletin_title=>""))		
			->set("row", $row)
			->event_view("bulletin/detail");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
