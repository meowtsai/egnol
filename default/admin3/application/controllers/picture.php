<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picture extends MY_Controller {
		
	function __construct() 
	{
		parent::__construct();				
		$this->load->model("pictures");		
		$this->zacl->check_login(true);
		$this->zacl->check("picture", "read");
		
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}			
	}
	
	function index() 
	{
		$this->_init_layout();
		//$this->g_layout->set("path", "picture/get_list")->render("server/menu");
	}

	function get_list($category_id=0)
	{
		$this->_chk_game_id();
		$this->_init_layout();	
		
		$this->load->helper("g_picture_helper");
		$this->load->library('pagination');		
		$this->pagination->initialize(array(
					'base_url'	=> site_url("picture/get_list/{$category_id}?game_id={$this->game_id}"),
					'total_rows'=> $this->pictures->get_picture_count($this->game_id, $category_id),
					'per_page'	=> 5
				));
		
		$this->g_layout
			->add_breadcrumb("圖片管理")
			->add_js_include("picture/list")
			->set("category_id", $category_id)
			->set("category_query", $this->DB2->where("game_id", $this->game_id)->get("picture_categories"))
			->set("query", $this->pictures->get_picture_data($this->game_id, $category_id, 5, $this->input->get("record")))
			->render();
	}
	
	function edit($id) 
	{
		$this->zacl->check("picture", "modify");
		
		$this->_chk_game_id();
		$this->_init_layout();		
		
		$this->load->helper("g_picture_helper");
		$picture = $this->DB2->where("id", $id)->get("pictures")->row();
		
		$this->load->library("user_agent");
		$this->g_layout->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "");
		
		$this->g_layout
			->add_breadcrumb("圖片管理", "picture/get_list?game_id={$this->game_id}")
			->add_breadcrumb("修改")
			->add_js_include("picture/form")
			->add_js_include("ckeditor/ckeditor")
			->set("picture", $picture)
			->set("category_query", $this->DB2->where("game_id", $this->game_id)->get("picture_categories"))
			->render("picture/form");		
	}
	
	function add()
	{
		$this->_chk_game_id();
		$this->_init_layout();
		
		$this->load->helper("g_picture_helper");
		$this->load->library("user_agent");
		$this->g_layout->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "");

		$this->g_layout
			->add_breadcrumb("圖片管理", "picture/get_list?game_id={$this->game_id}")
			->add_breadcrumb("新增")
			->add_js_include("picture/form")
			->add_js_include("ckeditor/ckeditor")
			->set("picture", false)
			->set("category_query", $this->DB2->where("game_id", $this->game_id)->get("picture_categories"))
			->render("picture/form");
	}
	
	function modify()
	{
		if ( ! $this->zacl->check_acl("picture", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('category_id', '分類', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{
			if ( ! empty($_FILES["userfile"]['name'])) 
			{				
				$this->load->library('upload', array("upload_path"=>realpath("p/upload"), "allowed_types"=>"gif|jpg|jpeg|png|swf", 'encrypt_name'=>TRUE));

				if ( ! $this->upload->do_upload())
				{
					echo json_failure($this->upload->display_errors());
					return;
				}
				else
				{
					rsync_to_slave();
					$data = $this->upload->data();
					$src = site_url("p/upload/{$data['file_name']}");
				}				
			}
			else {
				$src = $this->input->post("src");
			}
			
			$data = array(
				'category_id'		=> $this->input->post("category_id"),
				'src'				=> $src,
				'link'				=> $this->input->post("link"),
				'title'				=> $this->input->post("title"),
				'width'				=> $this->input->post("width") ? $this->input->post("width") : null,
				'height'			=> $this->input->post("height") ? $this->input->post("height") : null,
				'is_active'			=> $this->input->post("is_active"),
			);
			
			if ($picture_id = $this->input->post("picture_id")) { //修改
				$this->DB1->where('id', $picture_id)->update('pictures', $data);
			}
			else { //新增
				$insert_id = $this->pictures->insert_picture($data);
			}
			
			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("picture/get_list?game_id={$this->input->post("game_id")}");
			
			echo json_message(array("back_url" => $back_url));
			return;			
		}
	}
	
	function delete($id)
	{		
		if ( ! $this->zacl->check_acl("picture", "delete")) die(json_failure("沒有權限"));
		
		$row = $this->DB2->where('id', $id)->get('pictures')->row();
		$this->DB1->where('id', $id)->delete('pictures');		
		
		if ($this->DB1->affected_rows() > 0) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'picture', 'delete', "刪除圖片 #{$id}");
			echo json_success();
		}
		else echo json_failure();
	}
	
	function _chk_game_id()
	{
		if (empty($this->game_id)) {
			redirect("picture");
		}
	}	

	function category()
	{		
		$this->_init_layout();
		$this->g_layout
			->add_breadcrumb("圖片管理", "picture/get_list?game_id={$this->game_id}")
			->add_breadcrumb("分類")
			->add_js_include("picture/category")
			->set("query", $this->pictures->get_category_data($this->game_id))
			->render();
	}
	
	function edit_category($id)
	{
		$this->zacl->check("picture", "modify");
		
		$this->_chk_game_id();
		$this->_init_layout();
		$row = $this->DB2->where("id", $id)->get("picture_categories")->row();
	
		$this->g_layout
			->add_breadcrumb("圖片管理", "picture/get_list?game_id={$this->game_id}")
			->add_breadcrumb("分類", "picture/category?game_id={$this->game_id}")
			->add_breadcrumb("修改")
			->add_js_include("picture/category_form")
			->set("row", $row)
			->render("picture/category_form");
	}
	
	function add_category()
	{
		$this->zacl->check("picture", "modify");
		
		$this->_chk_game_id();
		$this->_init_layout();
	
		$this->g_layout
			->add_breadcrumb("圖片管理", "picture/get_list?game_id={$this->game_id}")
			->add_breadcrumb("分類", "picture/category?game_id={$this->game_id}")
			->add_breadcrumb("新增")
			->add_js_include("picture/category_form")
			->set("row", false)
			->render("picture/category_form");
	}
	
	function modify_category()
	{
		if ( ! $this->zacl->check_acl("picture", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('category', '分類名稱', 'required|min_length[2]');
	
		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{
			$data = array('category' => $this->input->post("category"),);
			$json = array("back_url" => site_url("picture/category?game_id={$this->game_id}"));
				
			if ($category_id = $this->input->post("category_id")) { //修改
				$reVar = $this->pictures->update_picture_category($category_id, $data);
				$reVar OR $json['message'] = '無變更';
			}
			else { //新增
				$insert_id = $this->pictures->insert_picture_category($this->game_id, $data);
				$reVar = $insert_id ? true : false;
			}
	
			echo json_message($json, $reVar);
			return;
		}
	}
	
	function delete_category($id)
	{
		if ( ! $this->zacl->check_acl("picture", "delete")) die(json_failure("沒有權限"));
		
		echo $this->pictures->delete_picture_category($id) ? json_success() : json_failure();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */