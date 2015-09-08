<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->zacl->check_login(true);				
	}
	
	function _init_home_layout()
	{
		return $this->_init_layout()->add_breadcrumb("首頁");
	}
	
	function index()
	{
		$this->_init_layout()
			->render();	
	}
		
	function skybar_banner()
	{		
		$query = $this->DB2->from("skybar_banners")->order_by("order")->get();
		$this->_init_home_layout()
			->add_breadcrumb("skybar banner")
			->set("query", $query)
			->render();		
	}
	
	function modify_skybar_banner($id=0)
	{
		if ($id) {
			$row = $this->DB2->from("skybar_banners")->where("id", $id)->get()->row();
		} else $row = false;
		
		$this->_init_layout()
			->set("row", $row)
			->add_js_include("home/form")
			->render();
	}
	
	function modify_skybar_banner_ajax()
	{
		//if ( ! $this->zacl->check_acl("picture", "modify")) die(json_failure("沒有權限"));

		$this->load->library('upload', array("upload_path"=>realpath("p/upload"), "allowed_types"=>"gif|jpg|jpeg|png", 'encrypt_name'=>TRUE));
					
		$p1_src = $this->input->post("p1_src");
		$p2_src = $this->input->post("p2_src");
					
		if ( ! empty($_FILES["p1"])) 
		{				
			if ( ! $this->upload->do_upload("p1"))
			{
				$p1_src = $this->input->post("p1_src");
				echo json_failure($this->upload->display_errors());
				return;
			}
			else
			{
				$data = $this->upload->data();
				$p1_src = site_url("p/upload/{$data['file_name']}");
			}				
		}
		
		if ( ! empty($_FILES["p2"])) 
		{				
			if ( ! $this->upload->do_upload("p2"))
			{
				$p2_src = $this->input->post("p2_src");
				echo json_failure($this->upload->display_errors());
				return;
			}
			else
			{
				$data = $this->upload->data();
				$p2_src = site_url("p/upload/{$data['file_name']}");
			}				
		}
		
		if (empty($p1_src) || empty($p2_src)) die(json_failure("圖檔未設定"));
		
		$data = array(
			'p1_src'			=> $p1_src,
			'p2_src'			=> $p2_src,
			'link'				=> $this->input->post("link"),
			'enable'			=> $this->input->post("enable"),
		);
		
		if ($id = $this->input->post("id")) { //修改
			$this->DB1->where('id', $id)->update('skybar_banners', $data);
		}
		else { //新增
			$insert_id = $this->DB1->insert("skybar_banners", $data);
		}
		
		$back_url = $this->input->post("back_url") ?
						$this->input->post("back_url") : site_url("home/skybar_banner");
		
		echo json_message(array("back_url" => $back_url));
		return;			
	}
	
	function delete_skybar_banner($id)
	{		
// 		if ( ! $this->zacl->check_acl("picture", "delete")) die(json_failure("沒有權限"));
		
// 		$row = $this->DB2->where('id', $id)->get('skybar_banners')->row();
		$this->DB1->where('id', $id)->delete('skybar_banners');		
		
		if ($this->DB1->affected_rows() > 0) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'skybar_banner', 'delete', "刪除skybar_banner #{$id}");
			echo json_success();
		}
		else echo json_failure();
	}	
	
	
}