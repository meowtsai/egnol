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
	
	function index()
	{
        $admin_uid = $_SESSION['admin_uid'];
            
		$requester = $this->DB2->query("
			SELECT t.*, g.name as game_name, u.name, au.name allocate_user_name, ccu.name cc_user_name FROM 
                tickets t
            JOIN admin_users u ON u.uid=t.admin_uid
            LEFT JOIN games g ON g.game_id=t.game_id
            LEFT JOIN admin_users au ON au.uid=t.allocate_admin_uid
            LEFT JOIN admin_users ccu ON ccu.uid=t.cc_admin_uid
            WHERE t.admin_uid='$admin_uid'
                AND t.status IN (\"1\", \"2\", \"3\")
		");	
        
		$allocated = $this->DB2->query("
			SELECT t.*, g.name as game_name, u.name, au.name allocate_user_name, ccu.name cc_user_name FROM 
                tickets t
            JOIN admin_users u ON u.uid=t.admin_uid
            LEFT JOIN games g ON g.game_id=t.game_id
            LEFT JOIN admin_users au ON au.uid=t.allocate_admin_uid
            LEFT JOIN admin_users ccu ON ccu.uid=t.cc_admin_uid
            WHERE (t.allocate_admin_uid='$admin_uid' OR t.cc_admin_uid='$admin_uid')
                AND t.status IN (\"1\", \"2\", \"3\")
		");
		
		$this->_init_ticket_layout()
			->set("requester", $requester)
			->set("allocated", $allocated)	
			->render();
	}
	
	function add()
	{		
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
        
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
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();

		$query = $this->DB2->from("tickets qt")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');
		
		$ticket = $query->row();
		
		$this->_init_ticket_layout()
			->add_breadcrumb("編輯工作申請&回報單")
			->add_js_include("ticket/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("ticket", $ticket)
			->render("ticket/form");
	}
	
	function modify_json()
	{
        $this->load->helper('path');
        
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
        
        if (!is_dir(set_realpath("p/upload/ticket{$ticket_id}"))) {
            mkdir(set_realpath("p/upload/ticket{$ticket_id}"), 0777, TRUE);
        }
        
		$this->load->library('upload');
		$config['upload_path'] = set_realpath("p/upload/ticket{$ticket_id}");
		$config['allowed_types'] = '*';
		$config['max_size']	= '10240'; //10MB
		$config['encrypt_name'] = false;
		
		$upload_cnt = 0;
		if ( ! empty($_FILES["file01"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file01"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/ticket{$ticket_id}/{$upload_data['file_name']}");					
			}
		}
		
		if ( ! empty($_FILES["file02"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file02"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/ticket{$ticket_id}/{$upload_data['file_name']}");					
			}
		}
		if ( ! empty($_FILES["file03"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file03"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/ticket{$ticket_id}/{$upload_data['file_name']}");					
			}
		}		
		
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
		
		die(json_message(array("redirect_url"=> base_url("ticket/view/".$ticket_id), "id"=>$ticket_id), true));		
	}
		
	function get_list()
	{			
		$this->_init_ticket_layout();
		
		if ($this->input->get("action")) 
		{			
			header("Cache-Control: private");

			$this->DB2->start_cache();
			
			$this->input->get("ticket_id") && $this->DB2->where("t.id", $this->input->get("ticket_id"));
			$this->input->get("admin") && $this->DB2->where("t.admin_uid", $this->input->get("admin"));
			$this->input->get("allocate_admin") && $this->DB2->where("t.allocate_admin_uid", $this->input->get("allocate_admin"));
			$this->input->get("cc_admin") && $this->DB2->where("t.cc_admin_uid", $this->input->get("cc_admin"));
			$this->input->get("status")<>'' && $this->DB2->where("t.status", $this->input->get("status"));
			$this->input->get("type") && $this->DB2->where("t.type", $this->input->get("type"));
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("urgency") && $this->DB2->where("t.urgency", $this->input->get("urgency"));			
			$this->input->get("title") && $this->DB2->like("t.title", $this->input->get("title"));
			$this->input->get("content") && $this->DB2->like("t.content", $this->input->get("content"));
					
			$this->input->get("todo") && $this->DB2->where("(t.status<>\"0\" and t.status<>\"4\")", null, false);
			
			$this->DB2
				->select("t.*, g.name as game_name, u.name, au.name allocate_user_name, ccu.name cc_user_name")
				->from("tickets t")
                ->join("admin_users u", "u.uid=t.admin_uid")
                ->join("games g", "g.game_id=t.game_id", "left")
                ->join("admin_users au", "au.uid=t.allocate_admin_uid", "left")
                ->join("admin_users ccu", "ccu.uid=t.cc_admin_uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("t.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("t.create_time >= {$start_date}", null, false);
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
					
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
        
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
		
		$this->g_layout
			->add_breadcrumb("查詢")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->set("admin_users", $admin_users)
			->add_js_include("ticket/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function view($id)
	{
		$this->zacl->check("ticket", "modify");

		$ticket = $this->DB2->select("t.*, g.name as game_name, u.name, au.name allocate_user_name, ccu.name cc_user_name")
			->where("t.id", $id)
			->from("tickets t")
			->join("admin_users u", "u.uid=t.admin_uid")
			->join("games g", "g.game_id=t.game_id", "left")
			->join("admin_users au", "au.uid=t.allocate_admin_uid", "left")
			->join("admin_users ccu", "ccu.uid=t.cc_admin_uid", "left")
			->get()->row();
		
		if ($ticket->status == '1') {
			$this->DB2->where("id", $id)->update("tickets", array("is_read"=>'1'));
		}
		
		$replies = $this->DB2
			->select("tr.*, au.name as admin_uname")
			->from("ticket_replies tr")
			->join("admin_users au", "au.uid=tr.admin_uid", "left")
			->where("ticket_id", $id)->order_by("tr.id", "asc")->get();

		$allocate_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
		
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
		}
		
		die(json_success());		
	}

	function update_note_json()
	{
		$this->DB1->where("id", $this->input->post("ticket_id"))
			->update("tickets", array('note' => $this->input->post("note")));
		
		die(json_success());		
	}		
	
	function allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."轉交：".$this->input->post("result")."<br>";		
		$this->DB1->where("id", $this->input->post("ticket_id"))
			->set('allocate_date', 'NOW()', false)
			->set('allocate_result', $result)
			->update("tickets", array('allocate_admin_uid' => $this->input->post("allocate_admin_uid")));
			
		die(json_success());		
	}
		
	function move_ticket($id)
	{
		$status = $this->input->get_post("status", TRUE);
        
		$this->DB1->set("status", $status)->where("id", $id)->update("tickets");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());	
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */