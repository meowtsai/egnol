<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		$this->zacl->check_login(true);
		
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}		
	}
	
	function index()
	{
		$this->_init_layout();
		$this->g_layout->render();
	}
		
	function get_list()
	{
		$this->zacl->check("server", "read");
				
		//$this->_chk_game_id();
		
		if (empty($this->game_id)) {
			$query = "";
		} else {
			$query = $this->db->where("game_id", $this->game_id)->from("servers")->order_by("server_id", "desc")->get();
		}
		
		$this->_init_layout();
		
		$this->g_layout
			->add_breadcrumb("伺服器管理")
			->set("query", $query)
			->render();
	}
	
	function add()
	{
		$this->zacl->check("server", "modify");
		
		$this->_chk_game_id();
		
		$this->_init_layout();
		$this->g_layout
			->add_breadcrumb("伺服器管理", "server/get_list?game_id={$this->game_id}")
			->add_breadcrumb("新增伺服器")
			->add_js_include("server/form")
			->set("row", false)
			->render("server/form");
	}
		
	function edit($server_id)
	{
		$this->zacl->check("server", "modify");
		
		$this->_chk_game_id();
		$row = $this->db->where("server_id", $server_id)->from("servers")->get()->row();
		if ($row == false) die("無此記錄");
		
		$this->_init_layout();
		$this->g_layout
			->add_breadcrumb("伺服器管理", "server/get_list?game_id={$this->game_id}")
			->add_breadcrumb("編修項目")
			->set("row", $row)
			->add_js_include("server/form")
			->render("server/form");
	}
	
	function modify()
	{
		$this->zacl->check("server", "modify");
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		
		//$this->form_validation->set_rules('game_id', '伺服器類別', 'required');
		$this->form_validation->set_rules('server_id', '伺服器代碼', 'required');
		$this->form_validation->set_rules('name', '伺服器名稱', 'required');
		//$this->form_validation->set_rules('exchange_rate', '充值匯率', 'required|float');
	
		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{			
			$data = array(
					//'game_id'		=> $this->input->post("game_id"),
					'server_id'		=> $this->input->post("server_id"),
					'name'			=> $this->input->post("name"),
					'address'			=> $this->input->post("address"),
					'server_status'	=> $this->input->post("server_status"),
					'is_transaction_active'	=> $this->input->post("is_transaction_active"),
					'maintaining_msg'	=> $this->input->post("maintaining_msg"),
					//'exchange_rate'		=> $this->input->post("exchange_rate"),
					//'server_performance'=> $this->input->post("server_performance"),
					'is_new_server'		=> $this->input->post("is_new_server"),
			);
				
			if ($id = $this->input->post("id")) { //修改
				$this->db->where("id", $id)->update("servers", $data);
			}
			else { //新增
				$data["game_id"] = $this->input->post("game_id");
				$insert_id = $this->db->insert("servers", $data);
			}
			
			$this->load->library('upload');
			$config['upload_path'] = g_conf("http_document_root").$this->input->post("game_id")."/p/img/server/";
			$config['allowed_types'] = 'png';
			$config['max_size']	= '600';
			$config['max_width'] = '2048';
			$config['max_height'] = '768';		
			$config['overwrite'] = true;
			
			$upload_cnt = 0;
			if ( ! empty($_FILES["file01"]["name"])) {
				$config['file_name'] = $this->input->post("server_id")."n";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file01")) {
					$msg[] = $this->upload->display_errors('', '');
				}
				else {
					$upload_cnt++;
				}
			}
			if ( ! empty($_FILES["file02"]["name"])) {
				$config['file_name'] = $this->input->post("server_id")."_off";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file02")) {
					$msg[] = $this->upload->display_errors('', '');
				}
				else {
					$upload_cnt++;
				}
			}
			if ( ! empty($_FILES["file03"]["name"])) {
				$config['file_name'] = $this->input->post("server_id")."_on";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file03")) {
					$msg[] = $this->upload->display_errors('', '');
				}
				else {
					$upload_cnt++;
				}
			}						
			
			echo json_message(array("back_url" => site_url("server/get_list?game_id={$this->input->post("game_id")}")));
			return;
		}
	}
	
	function set_entry($id)
	{
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		//一次只能有一個新服
		$this->db->where("game_id", $this->game_id)->where("is_entry_server", "1")
			->set("is_entry_server", "0")->update("servers"); //清空
		$this->db->where("game_id", $this->game_id)->where("id", $id)
			->set("is_entry_server", "1")->update("servers"); //設定
		echo json_success();
		return;
	}	
	
	function set_new($id)
	{		
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		//一次只能有一個新服
		$this->db->where("game_id", $this->game_id)->where("is_new_server", "1")
				->set("is_new_server", "0")->update("servers"); //清空
		$this->db->where("game_id", $this->game_id)->where("id", $id)
				->set("is_new_server", "1")->update("servers"); //設定
		echo json_success();
		return;		
	}

	function set_status($id, $status)
	{
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		$this->db->where("game_id", $this->game_id)->where("id", $id)
		->set("server_status", $status)->update("servers");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");
	}
	
	function set_muti_status($status)
	{
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		$ids = $this->input->post("ids");
		$this->db->where("game_id", $this->game_id)->where_in("id", $ids)
		->set("server_status", $status)->update("servers");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");
	}	
	
	function set_tran_status($id, $status)
	{
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		$this->db->where("game_id", $this->game_id)->where("id", $id)
			->set("is_transaction_active", $status?"1":"0")->update("servers");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");		
	}
	
	function set_muti_tran_status($status)
	{
		if ( ! $this->zacl->check_acl("server", "modify")) die(json_failure("沒有權限"));
		
		$ids = $this->input->post("ids");
		$this->db->where("game_id", $this->game_id)->where_in("id", $ids)
			->set("is_transaction_active", $status?"1":"0")->update("servers");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");
	}	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */