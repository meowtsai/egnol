<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();				
		$this->load->model("notices");
		$this->zacl->check_login(true);
		$this->zacl->check("notice", "read");	
	}
	
	function choose($id)
	{
		$this->zacl->check("notice", "modify");
		
		$this->_init_layout();		

		$this->load->library("user_agent");
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("id desc")->get();	
			
		$this->g_layout
			->add_breadcrumb("通知", "notice/get_list")
			->add_breadcrumb("選擇玩家")
			->add_js_include("notice/form")
			->set("id", $id)
			->set("games", $games)
			->set("servers", $servers)
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->render();	
	}
	
	function choose_modify()
	{
		if ( ! $this->zacl->check_acl("notice", "modify")) die(json_failure("沒有權限"));

		$notice_id = $this->input->post("notice_id");
		if (empty($notice_id)) die(json_failure("參數遺失"));
		
		$this->db->where("notice_id", $notice_id)->delete("notice_targets");
				
		/*
		 * 
		 SELECT uid FROM long_e.log_game_logins_backup where create_time >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
group by uid
limit 500000
		 */
		
		if ($this->input->post("action") == "通知這些UID") {			
			$uids = $this->input->post("uid");
			if (empty($uids)) die(json_failure("uid未填"));
			
			$spt = explode("\n", $uids);
			$i = 0; $sql = 'insert into notice_targets (create_time, notice_id, uid) values ';
			foreach($spt as $uid) {
				$sql .= "(NOW(), '{$notice_id}', '{$uid}'),";
			} 
			$this->db->query(substr($sql,0,strlen($sql)-1));
		}
		else if ($this->input->post("action") == "通知三個月內有登入的玩家") {
			$this->db->query("
				insert into notice_targets (notice_id, uid, create_time)  
					select distinct '{$notice_id}', uid, NOW() FROM long_e.log_game_logins where create_time >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
			");
		}
		else {
			$game_id = $this->input->post("game_id");
			$server = $this->input->post("server");
			$channel = $this->input->post("channel");
			$where = " 1=1 ";
			
			$game_id && $where .= " and game_id='{$game_id}'";
			$server && $where .= " and server_id='{$server}'";
			$channel && $where .= " and account like '%@{$channel}'";

			//if ($where == " 1=1 ") die(json_failure("請選擇條件")); 
			
			/*$this->db->query("
				delete from notice_targets where uid not in (select uid from log_game_logins lgl join servers gi on lgl.server_id=gi.id where {$where})
					and notice_id='{$notice_id}'				
			");*/			
			$this->db->query("
				insert into notice_targets (notice_id, uid, create_time)  
					select distinct '{$notice_id}', uid, NOW() from log_game_logins lgl join servers gi on lgl.server_id=gi.id where {$where}
			");

		}
		
		$back_url = $this->input->post("back_url") ?
						$this->input->post("back_url") :
						site_url("notice/get_list");
		
		echo json_message(array("back_url" => $back_url));			
	}

	function get_list()
	{
		$this->_init_layout();	
		
		$this->load->library('pagination');		
		$this->pagination->initialize(array(
					'base_url'	=> site_url("notice/get_list"),
					'total_rows'=> $this->notices->get_notice_count(),
					'per_page'	=> 10
				));
		
		$this->g_layout
			->add_breadcrumb("通知", "notice/get_list")
			->add_js_include("notice/list")
			->set("query", $this->notices->get_notice_data(10, $this->input->get("record")))
			->render();
	}
	
	function get_user($id)
	{
		$this->_init_layout();	
		
		$this->g_layout
			->add_breadcrumb("通知", "notice/get_list")
			->add_breadcrumb("檢視玩家")
			->set("query", $this->db->where("notice_id", $id)->from("notice_targets")->get())
			->render();
	}
	
	function edit($id) 
	{
		$this->zacl->check("notice", "modify");
		
		$this->_init_layout();		

		$this->load->library("user_agent");
		
		$this->g_layout
			->add_breadcrumb("通知", "notice/get_list")
			->add_breadcrumb("修改")
			->add_js_include("notice/form")
			->add_js_include("ckeditor/ckeditor")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("notice", $this->notices->get_notice($id))
			->render("notice/form");		
	}
	
	function add()
	{
		$this->zacl->check("notice", "modify");
		
		$this->_init_layout();
		
		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("通知", "notice/get_list")
			->add_breadcrumb("新增")
			->add_js_include("notice/form")
			->add_js_include("ckeditor/ckeditor")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("notice", false)
			->render("notice/form");
	}
	
	function modify()
	{
		if ( ! $this->zacl->check_acl("notice", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('notice_title', '標題', 'required|min_length[2]');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{				
			$data = array(
				'title'	=> $this->input->post("title"),
				'content'	=> $this->input->post("content"),
				'is_active' => $this->input->post("is_active"),		
				'url' => $this->input->post("url"),
			); 			
			
			if ($notice_id = $this->input->post("notice_id")) { //修改
				$this->notices->update_notice($notice_id, $data);
			}
			else { //新增
				$insert_id = $this->notices->insert_notice($data);
			}
			
			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("notice/get_list");
			
			echo json_message(array("back_url" => $back_url));
			return;			
		}
	}
	
	function delete($id)
	{
		if ( ! $this->zacl->check_acl("notice", "delete")) die(json_failure("沒有權限"));
		
		$row = $this->notices->get_notice($id);
		
		if ($this->notices->delete_notice($id)) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'notice', 'delete', "刪除通知 #{$id} {$row->title}");
			echo json_success();
		}
		else echo json_failure();
	}
	
	function set_is_active($id, $val)
	{
		if ( ! $this->zacl->check_acl("notice", "modify")) die(json_failure("沒有權限"));
		
		$this->db->where("id", $id)->set("is_active", $val)->update("notices");
		echo $this->db->affected_rows()>0 ? json_success() : json_failure("無變更");
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */