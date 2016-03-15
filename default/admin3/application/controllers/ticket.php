<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticket extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();				
		$this->zacl->check_login(true);
		$this->zacl->check("ticket", "read");

		$this->load->config("ticket");
	}
	
	function _init_ticket_layout()
	{
		return $this->_init_layout()->add_breadcrumb("工作申請&回報單", "ticket");	
	}

	function todo()
	{
		$_GET['action'] = '查詢';
		$_GET['todo'] = '1';
		
		header("refresh:30");
		$this->router->method = 'get_list';
		$this->get_list();
	}
	
	function my()
	{
		$_GET['action'] = '查詢';
		$_GET['allocate_status'] = '1';
		$_GET['allocate_auid'] = $this->input->get("allocate_auid") ? $this->input->get("allocate_auid") : $_SESSION['admin_uid'];		
		
		$this->router->method = 'get_list';
		$this->get_list();
	}
	
	function assign($type='')
	{
		$this->_init_ticket_layout();
		
		header("Cache-Control: private");

		$this->DB2->start_cache();		
		
		$this->DB2
			->from("ticket_assigns dlv")
			->join("admin_users au", "au.uid=dlv.admin_uid", "left");
		
		if ($type == 'not') {
			$this->DB2->where("id in (select ticket_assign_id from ticket_assignees where admin_uid=".$_SESSION['admin_uid']." and `is_read`=0)", null, false);
		}	
					
		$this->input->get("source") && $this->DB2->where("dlv.source", $this->input->get("source"));
		$this->input->get("admin_uid") && $this->DB2->where("dlv.admin_uid", $this->input->get("admin_uid"));
		$this->input->get("desc") && $this->DB2->like("dlv.desc", $this->input->get("desc"));
		$this->input->get("result") && $this->DB2->like("dlv.result", $this->input->get("result"));
		$this->input->get("status") && $this->DB2->like("dlv.status", $this->input->get("status"));		
		
		if ($date = $this->input->get("date")) {
			$this->DB2->where("dlv.create_time between '{$date} 00:00:00' and '{$date} 23:59:59'", null, false);	
		}
					
		$this->DB2->stop_cache();

		$total_rows = $this->DB2->count_all_results();
		
		$query = $this->DB2->limit(10, $this->input->get("record"))->get();					

		$get = $this->input->get();	
		$query_string = '';			
		if ($get) {	
			unset($get["record"]);
			$query_string = http_build_query($get);
		}
		
		$this->load->library('pagination');
		$this->pagination->initialize(array(
				'base_url'	=> site_url("ticket/assign?".$query_string),
				'total_rows'=> $total_rows,
				'per_page'	=> 10
			));				
		
		$this->g_layout->set("total_rows", $total_rows);
				
		$this->DB2->flush_cache();
					
		$cs_user = $this->DB2->from("admin_users")->like("role", "cs", "after")->get();
		
		$this->g_layout
			->add_breadcrumb("交接項目")	
			->set("query", isset($query) ? $query : false)
			->set("type", $type)
			->set("cs_user", $cs_user)			
			->add_js_include("ticket/assign")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function modify_assign($id=0)
	{		
		$this->load->library("user_agent");
		
		$ticket_assigns = false; 
		$target_arr = array();
		if ($id) {
			$ticket_assigns = $this->DB2->from("ticket_assigns")->where("id", $id)->get()->row();
			$targets = $this->DB2->from("ticket_assignees")->where("ticket_assign_id", $id)->get();
			foreach($targets->result() as $row) {
				$target_arr[] = $row->admin_uid; 
			}
		}
		 
		$users = $this->DB2->like("role", "cs", "after")->from("admin_users")->get();
		
		$this->_init_ticket_layout()
			->add_breadcrumb(($id?"修改":"新增")."交接事項")
			->add_js_include("ticket/modify_assign")
			->set("ticket_assigns", $ticket_assigns)
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("target_arr", $target_arr)
			->set("users", $users)
			->render();
	}
	
	function modify_assign_json()
	{
		$id = $this->input->post("id");
		
		$data = array(
			"source" => $this->input->post("source"),
			'desc' => nl2br(htmlspecialchars($this->input->post("desc"))),
			'result' => nl2br(htmlspecialchars($this->input->post("result"))),
			'admin_uid' => $_SESSION['admin_uid'],
			"status" => $this->input->post("status"),
		);				
		
		if ($id) {			
			$this->DB1
				->where("id", $id)
				->update("ticket_assigns", $data);
			
			if ($this->input->post('targets')) $this->DB1->where("ticket_assign_id", $id)->delete("ticket_assignees");
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("ticket_assigns", $data);	
			$id = $this->DB1->insert_id();			
		}
		if ($this->input->post('targets')) {
			foreach($this->input->post('targets') as $uid) {
				$this->DB1->insert("ticket_assignees", array("ticket_assign_id" => $id, "admin_uid" => $uid));
			}
		}
		
		die(json_message(array("id"=>$id, "back_url"=>$this->input->post("back_url")), true));		
	}
	
	function delete_assign_json()
	{
		$id = $this->input->get("id");	
				
		$this->DB1
			->where("id", $id)
			->where("admin_uid", $_SESSION['admin_uid'])
			->delete("ticket_assigns");
			
		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}
	
	function read_assign_json()
	{
		$id = $this->input->get("id");
		
		$this->DB1
			->set("read", "1")
			->where("id", $id)
			->where("admin_uid", $_SESSION['admin_uid'])
			->update("ticket_assignees");	
		
		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure();
	}
	
	function index()
	{
        $admin_uid = $_SESSION['admin_uid'];
		
		$requester = $this->DB2->query("
			SELECT * FROM 
                tickets
            WHERE admin_uid='$admin_uid'
                AND status IN (\"1\", \"2\", \"3\")
		");	
        
		$allocated = $this->DB2->query("
			SELECT * FROM 
                tickets
            WHERE (allocate_admin_uid='$admin_uid' OR cc_admin_uid='$admin_uid')
                AND status IN (\"1\", \"2\", \"3\")
		");
		
		$this->_init_ticket_layout()
			->set("requester", $requester)
			->set("allocated", $allocated)	
			->render();
	}
	
	function add()
	{		
		$games = $this->DB2->from("games")->where("is_active", "1")->get();
		$admin_users = $this->DB2->from("admin_users")->get();
		//$servers = $this->DB2->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");	
		
		$this->_init_ticket_layout()
			->add_breadcrumb("新增工作申請&回報單")
			->add_js_include("ticket/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("ticket", false)
			->render("ticket/form");
	}
	
	function edit($id)
	{		
		$games = $this->DB2->from("games")->where("is_active", "1")->get();
		$servers = $this->DB2->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");

		$query = $this->DB2->from("tickets qt")
			->join("servers gi", "qt.server_id=gi.server_id")
			->where("type", "9")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');
		
		$ticket = $query->row();
		
		$this->_init_ticket_layout()
			->add_breadcrumb("編輯電話案件")
			->add_js_include("ticket/form")
			->set("games", $games)
			->set("servers", $servers)
			->set("ticket", $ticket)
			->render("ticket/form");
	}
	
	function modify_json()
	{
		$ticket_id = $this->input->post("ticket_id");
		
		$data = array(
			"game_id" => $this->input->post("game"),
			"type" => $this->input->post("type"),
			"urgency" => $this->input->post("urgency"),
			"type" => $this->input->post("type"),
			'title' => htmlspecialchars($this->input->post("title")),
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
			'admin_uid' => $_SESSION['admin_uid'],
			'allocate_admin_uid' => $this->input->post("allocate_admin"),
			'cc_admin_uid' => $this->input->post("cc_admin"),
		);				
		
		if ($ticket_id) {			
			$this->DB1
				->where("id", $ticket_id)
				->update("tickets", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("tickets", $data);	
			$ticket_id = $this->DB1->insert_id();			
		}
		
		die(json_message(array("id"=>$ticket_id), true));		
	}
		
	function get_list()
	{			
		$this->_init_ticket_layout();
		
		if ($this->input->get("action")) 
		{			
			header("Cache-Control: private");

			$this->DB2->start_cache();
			
			$this->input->get("ticket_id") && $this->DB2->where("q.id", $this->input->get("ticket_id"));
			$this->input->get("uid") && $this->DB2->where("q.uid", $this->input->get("uid"));
			$this->input->get("status")<>'' && $this->DB2->where("q.status", $this->input->get("status"));
			$this->input->get("type") && $this->DB2->where("q.type", $this->input->get("type"));
			$this->input->get("game") && $this->DB2->where("gi.game_id", $this->input->get("game"));
			$this->input->get("character_name") && $this->DB2->where("q.character_name", $this->input->get("character_name"));			
			$this->input->get("email") && $this->DB2->where("u.email", $this->input->get("email"));
			$this->input->get("content") && $this->DB2->like("q.content", $this->input->get("content"));
			
			$this->input->get("allocate_auid") && $this->DB2->where("q.allocate_admin_uid", $this->input->get("allocate_auid"));
			$this->input->get("allocate_status") && $this->DB2->where("q.allocate_status", $this->input->get("allocate_status"));
					
			$this->input->get("todo") && $this->DB2->where("(q.status=1 or q.allocate_status=2 and q.status<>4)", null, false);
			
			$this->DB2
				->select("q.*, g.name as game_name, au.name as admin_uname")
				->select("(select sum(amount) from user_billing where uid=q.uid and billing_type=2 and result=1) as expense")
				->from("tickets q")
				->join("servers gi", "gi.server_id=q.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("users u", "u.uid=q.uid", "left")
				->join("admin_users au", "au.uid=q.admin_uid", "left");
						
			if ($this->input->get("account")) {
				$this->DB2->join("users u", "u.uid=q.uid", "left")
					->where("u.email", $this->input->get("account"))
					->or_where("u.mobile", $this->input->get("account"));
			}
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("q.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("q.create_time >= {$start_date}", null, false);
			}
		
			switch ($this->input->get("action"))
			{										
				case "查詢":					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$sort = $this->input->get("sort") ? $this->input->get("sort") : 'id';
					
					$query = $this->DB2->limit(10, $this->input->get("record"))
								->order_by("{$sort} desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("ticket/get_list?".$query_string),
							'total_rows'=> $total_rows,
							'per_page'	=> 10
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
			}
						
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;			
		}
					
		$games = $this->DB2->from("games")->get();
		
		$this->g_layout
			->add_breadcrumb("查詢")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->add_js_include("ticket/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function view($id)
	{
		$this->zacl->check("ticket", "modify");

		$ticket = $this->DB2->select("t.*, g.name as game_name, u.name, au.name allocate_user_name")
			->where("t.id", $id)
			->from("tickets t")
			->join("games g", "g.game_id=t.game_id")
			->join("admin_users u", "u.uid=t.admin_uid")
			->join("admin_users au", "au.uid=t.allocate_admin_uid", "left")
			->get()->row();
		
		if ($ticket->status == '1') {
			$this->DB2->where("id", $id)->update("tickets", array("is_read"=>'1'));
		}
		
		$replies = $this->DB2
			->select("tr.*, au.name as admin_uname")
			->from("ticket_replies tr")
			->join("admin_users au", "au.uid=tr.admin_uid", "left")
			->where("ticket_id", $id)->order_by("tr.id", "asc")->get();

		$allocate_users = $this->DB2->from('admin_users')->where_in('role', array('pm', 'admin', 'cs_master', 'pd_chang', 'RC'))->order_by("role")->get();
		
		$this->_init_ticket_layout()
			->add_breadcrumb("檢視")
			->add_js_include("ticket/view")
			->set("ticket", $ticket)
			->set("replies", $replies)
			->set("allocate_users", $allocate_users)
			->render();
	}	
	
	function edit_reply($id)
	{
		$row = $this->DB2->where("id", $id)->from("question_replies")->get()->row();
		
		$this->_init_service_layout()
			->add_breadcrumb("編輯回覆")
			->add_js_include("ticket/edit_reply")
			->set("row", $row)
			->render();		
	}
	
	function modify_reply_json()
	{
		$ticket_id = $this->input->post("ticket_id");
		$id = $this->input->post("reply_id");
		
		$data = array(
			"uid" => 0,
			"ticket_id" => $ticket_id,
			'content' => nl2br($this->input->post("content")),
			'admin_uid' => $_SESSION['admin_uid'],
		);		
		
		if ($id) {			
			$row = $this->DB2->from("ticket_replies")->where("id", $id)->get()->row();
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'ticket_reply', 'update', 
					"編輯回覆 #{$id} {$row->content} => {$data['content']}");
						
			$this->DB1
				->where("is_official", "1")
				->where("id", $id)
				->update("ticket_replies", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->insert("ticket_replies", $data);	
		
			$this->DB1->set("update_time", "now()", false)
				->where("id", $ticket_id)->update("tickets", 
					array("is_read"=>'0', "status"=>'2', 'admin_uid'=>$_SESSION['admin_uid']));			
		}
		
		die(json_success());		
	}

	function update_note_json()
	{
		$this->DB1->where("id", $this->input->post("question_id"))
			->update("questions", array('note' => $this->input->post("note")));
		
		die(json_success());		
	}		
	
	function allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."：".$this->input->post("result")."<br>";		
		$this->DB1->where("id", $this->input->post("question_id"))
			->set('allocate_date', 'NOW()', false)
			->set('allocate_status', '1')
			->set('allocate_result', $result)
			->update("questions", array('allocate_admin_uid' => $this->input->post("allocate_admin_uid")));
			
		die(json_success());		
	}
	
	function finish_allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."：".$this->input->post("result")."<br>";
		$this->DB1->where("id", $this->input->post("question_id"))
			->set('allocate_finish_date', 'NOW()', false)
			->set('allocate_status', '2')
			->set('allocate_result', $result)
			->set('allocate_admin_uid', $_SESSION['admin_uid'])
			->update("questions");
		
		die(json_success());		
	}
		
	function close_question($id)
	{				
		if ( ! $this->zacl->check_acl("ticket", "modify")) die(json_failure("沒有權限"));
		
		$this->DB1->set("status", "4")->where("admin_uid is not null", null, false)->where("id", $id)->update("questions");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());	
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}
	
		
	function hide_question($id)
	{				
		if ( ! $this->zacl->check_acl("ticket", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->set("update_time", "now()", false)->where("id", $id)->update("questions", array("status"=>"0", 'admin_uid'=>$_SESSION['admin_uid']));
		if ($this->DB1->affected_rows()>0) 
		{
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'question', 'hide', "隱藏提問 #{$id}");
			die(json_success());
		}
		else die(json_failure());
	}
	
	function show_question($id)
	{				
		if ( ! $this->zacl->check_acl("ticket", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->set("update_time", "now()", false)->where("id", $id)->update("questions", array("status"=>"1", 'admin_uid'=>$_SESSION['admin_uid']));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */