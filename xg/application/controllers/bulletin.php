<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends MY_Controller {	
	
	function get_list($category_id=0)
	{		
		$this->_init_layout();
		
		$this->load->model("g_bulletins");		
		$this->load->library('pagination');
		
		$this->pagination->initialize(array(
				'base_url'	=> site_url("bulletin/get_list/{$category_id}?"),
				'total_rows'=> $this->g_bulletins->get_count($this->game, $category_id),
				'per_page'	=> 50
		));		
		
		$this->g_layout
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list"))
			->set("query", $this->g_bulletins->get_list($this->game, $category_id, 50, $this->input->get("record")))		
			->render();
	}
	
	function preview($id)
	{
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_preview($this->game, $id);
		if ($row == false) {die("無此記錄");}

		$this->g_layout
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list", $row->bulletin_title=>""))
			->set("row", $row)
			->render("bulletin/detail");		
	}
	
	function detail($id)
	{
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_bulletin($this->game, $id);
		if ($row == false || $row->priority == 0) {die("無此記錄");}
		
		$this->g_layout
			->set_breadcrumb(array("最新消息"=>"bulletin/get_list", $row->bulletin_title=>""))		
			->set("row", $row)
			->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */