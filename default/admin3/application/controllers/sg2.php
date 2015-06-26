<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sg2 extends MY_Controller {
	
	var $game;
	var $game_url;
	
	function __construct() 
	{
		parent::__construct();
		
		$this->game = 'sg2';
		$this->game_url = 'http://sg2.longeplay.com.tw/';	
		
		$this->load->database($this->game);
		$this->load->model($this->game.'model', 'model');
		
	}
	
	function index() 
	{
		$this->_init_layout();
		$this->g_layout->render();
	}
	
	function edit_guide_form($id) 
	{
		$this->_init_layout();
		$this->load->helper("guide");
		
		$guide = $this->model->get_guide($id);
		
		$this->g_layout
			->add_js_include("{$this->game}/guide_form")
			->add_js_include("ckeditor/ckeditor")
			->set("guide", $guide)
			->set("parent_id", $guide->parent_id)->render("{$this->game}/guide_form");		
	}
	
	function add_guide_form($parent_id="")
	{
		$this->_init_layout();
		$this->load->helper("guide");

		$this->g_layout
			->add_js_include("{$this->game}/guide_form")
			->set("guide", false)
			->set("parent_id", $parent_id)->render("{$this->game}/guide_form");
	}
	
	function guide($parent_id="")
	{
		$this->load->library("layout");	
		$this->load->helper("guide");
		
		$this->g_layout
			->add_js_include("{$this->game}/guide")
			->set("parent_id", $parent_id)
			->set("query", $this->model->get_guide_list($parent_id))
			->render();
	}
	
	function modify_guide()
	{
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'layout'));
		$this->form_validation->set_rules('guide_title', '標題', 'required|min_length[3]');

		if ($this->form_validation->run() == FALSE)
		{
			$this->g_layout->render("validation_errors");
		}
		else
		{
			$data = array(
				'guide_title'	=> $this->input->post("guide_title"),
				'guide_link'	=> $this->input->post("guide_link"),
				'guide_content'	=> $this->input->post("guide_content"),
				'enable'		=> $this->input->post("enable"),
				'parent_id'		=> $this->input->post("parent_id")
			); 
			
			if ($guide_id = $this->input->post("guide_id")) { //修改
				$this->model->update_guide($guide_id, $data);
			}
			else { //新增
				$insert_id = $this->model->insert_guide($data);
			}
			
			redirect("{$this->game}/guide/{$data['parent_id']}");
		}
	}
	
	function delete_guide($id)
	{
		$this->model->delete_guide($id);
		echo $this->db->affected_rows()>0 ? json_success() : json_failure();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */