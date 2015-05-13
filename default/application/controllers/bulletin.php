<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends MY_Controller {	
	
	var $game = 'long_e';
	
	function get_list($type=0)
	{		
		$this->_init_layout();
		
		$this->load->model("g_bulletins");		
		$this->load->library('pagination');
		
		$this->pagination->initialize(array(
				'base_url'	=> site_url("bulletin/get_list/{$type}?"),
				'total_rows'=> $this->g_bulletins->get_count($this->game, $type),
				'per_page'	=> 20
		));		
		
		$this->g_layout
			->set_breadcrumb(array("新聞公告"=>"bulletin/get_list"))
			->set("subtitle", "新聞公告列表")
			->set("query", $this->g_bulletins->get_list($this->game, $type, 20, $this->input->get("record")))		
			->render("", "inner2");
	}
	
	function preview($id)
	{
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$row = $this->db->where("id", $id)->get("bulletins")->row();
		if ($row == false) {die("無此記錄");}

		$pre_bulletin = $this->db->query("SELECT id, title FROM bulletins
					where game_id='{$this->game}' and id < ".$this->db->escape($id)." and priority>0 order by id desc limit 1")->row();

		$next_bulletin = $this->db->query("SELECT id, title FROM bulletins
					where game_id='{$this->game}' and id > ".$this->db->escape($id)." and priority>0 order by id limit 1")->row();

		$this->g_layout
			->set_breadcrumb(array("新聞公告"=>"bulletin/get_list"))
			->set("subtitle", "新聞公告")		
			->set("row", $row)
			->set("pre_bulletin", $pre_bulletin)
			->set("next_bulletin", $next_bulletin)			
			->render("bulletin/detail", "inner2");		
	}
	
	function detail($id=0)
	{
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_row($id);
		if ($row == false || $row->priority == 0) {die("無此記錄");}
		
		$pre_bulletin = $this->db->query("SELECT id, title FROM bulletins
					where game_id='{$this->game}' and id < ".$this->db->escape($id)." and priority>0 order by id desc limit 1")->row();

		$next_bulletin = $this->db->query("SELECT id, title FROM bulletins
					where game_id='{$this->game}' and id > ".$this->db->escape($id)." and priority>0 order by id limit 1")->row();
		
		$this->g_layout
			->set_breadcrumb(array("新聞公告"=>"bulletin/get_list"))
			->set("subtitle", "新聞公告")		
			->set("row", $row)
			->set("pre_bulletin", $pre_bulletin)
			->set("next_bulletin", $next_bulletin)
			->render("", "inner2");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */