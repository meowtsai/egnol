<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guide extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
		$this->load->helper("guide");
		$this->load->model("guides");
		
		$this->zacl->check_login(true);
		$this->zacl->check("guide", "read");

		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}		
	}
	
	function index() 
	{
		$this->_init_layout();
		$this->g_layout->set("path", "guide/get_list")->render("server/menu");
	}
	
	function get_list($parent_id="")
	{
		$this->_chk_game_id();
		$this->_init_layout();
	
		$this->g_layout
			->add_breadcrumb("遊戲資料")
			->add_js_include("guide/list")
			->set("parent_id", $parent_id)
			->set("query", $this->guides->get_guide_list($this->game_id, $parent_id))
			->render();
	}
	
	function edit($id) 
	{
		$this->zacl->check("guide", "modify");
		
		$this->_chk_game_id();
		$this->_init_layout();		
		$guide = $this->guides->get_guide($id);
		
		$this->g_layout
			->add_breadcrumb("遊戲資料", "guide/get_list?game_id={$this->game_id}")
			->add_breadcrumb("修改")
			->add_js_include("guide/form")
			->add_js_include("ckeditor/ckeditor")
			->set("guide", $guide)
			->set("parent_id", $guide->parent_id)->render("guide/form");		
	}
	
	function add($parent_id="")
	{
		$this->zacl->check("guide", "modify");
		
		$this->_chk_game_id();
		$this->_init_layout();

		$this->g_layout
			->add_breadcrumb("遊戲資料", "guide/get_list?game_id={$this->game_id}")
			->add_breadcrumb("新增")
			->add_js_include("guide/form")
			->add_js_include("ckeditor/ckeditor")
			->set("guide", false)
			->set("parent_id", $parent_id)->render("guide/form");
	}
	
	function modify()
	{
		if ( ! $this->zacl->check_acl("guide", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('guide_title', '標題', 'required|min_length[2]');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{
			$data = array(
				'guide_title'	=> $this->input->post("guide_title"),
				'guide_link'	=> $this->input->post("guide_link"),
				'guide_content'	=> $this->input->post("guide_content"),
				'enable'		=> $this->input->post("enable"),
				'parent_id'		=> $this->input->post("parent_id"),
				'game_id'		=> $this->input->post("game_id")
			); 
			
			if ($guide_id = $this->input->post("guide_id")) { //修改
				$this->guides->update_guide($guide_id, $data);
			}
			else { //新增
				$insert_id = $this->guides->insert_guide($data);
			}
			
			echo json_message(array("back_url" => site_url("guide/get_list/{$data['parent_id']}?game_id={$data['game_id']}")));
			return;			
		}
	}
	
	function delete($id)
	{
		if ( ! $this->zacl->check_acl("guide", "delete")) die(json_failure("沒有權限"));
		
		$this->guides->delete_guide($id);
		echo $this->db->affected_rows()>0 ? json_success() : json_failure();
	}
	
	function _chk_game_id()
	{
		if (empty($this->game_id)) {
			redirect("guide");
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */