<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vip extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();				
		$this->zacl->check_login(true);
		$this->zacl->check("vip", "read");

		$this->load->config("vip");
	}
	
	function _init_vip_layout()
	{
		return $this->_init_layout()->add_breadcrumb("VIP活動", "vip");	
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
        $this->load->helper('url');
        redirect('/vip/event_list', 'refresh');
	}
	
	function add_event()
	{		
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
        
		$this->_init_vip_layout()
			->add_breadcrumb("建立VIP活動")
			->add_js_include("vip/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("vip", false)
			->render("vip/event_form");
	}
	
	function add_ticket()
	{		
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
        
		$this->_init_vip_layout()
			->add_breadcrumb("建立VIP訂單")
			->add_js_include("vip/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("vip", false)
			->render("vip/ticket_form");
	}
	
	function edit_event($id)
	{		
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();

		$query = $this->DB2->from("vip_events qt")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');
		
		$vip = $query->row();
		
		$this->_init_vip_layout()
			->add_breadcrumb("編輯VIP活動")
			->add_js_include("vip/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("vip", $vip)
			->render("vip/event_form");
	}
	
	function modify_event_json()
	{
        $this->load->helper('path');
        
		if ($this->zacl->check_acl("vip", "authorize")) {
            $auth_admin_uid = $_SESSION['admin_uid'];
            $auth_time = date('Y-m-d H:i:s');
            $status = 2;
        } else {
            $auth_admin_uid = "";
            $auth_time = "";
            $status = 1;
        }
        
		$vip_id = $this->input->post("vip_id");
		
		$data = array(
			'game_id' => $this->input->post("game"),
			'title' => htmlspecialchars($this->input->post("title")),
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
			'cost' => intval($this->input->post("cost")),
			'type' => intval($this->input->post("type")),
			'admin_uid' => $_SESSION['admin_uid'],
			'auth_admin_uid' => $auth_admin_uid,
			'auth_time' => $auth_time,
			'status' => $status,
			'is_active' => $this->input->post("is_active"),
			'start_date' => $this->input->post("start_date"),
			'end_date' => $this->input->post("end_date"),
		);
        
        if (!is_dir(set_realpath("p/upload/vip_event{$vip_id}"))) {
            mkdir(set_realpath("p/upload/vip_event{$vip_id}"), 0777, TRUE);
        }
        
		$this->load->library('upload');
		$config['upload_path'] = set_realpath("p/upload/vip_event{$vip_id}");
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
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/vip_event{$vip_id}/{$upload_data['file_name']}");					
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
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/vip_event{$vip_id}/{$upload_data['file_name']}");					
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
				$data['file_path'.(++$upload_cnt)] = site_url("p/upload/vip_event{$vip_id}/{$upload_data['file_name']}");					
			}
		}		
		
		if ($vip_id) {			
			$this->DB1
				->where("id", $vip_id)
				->update("vip_events", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("vip_events", $data);	
			$vip_id = $this->DB1->insert_id();			
		}
		
		die(json_message(array("redirect_url"=> base_url("vip/event_view/".$vip_id), "id"=>$vip_id), true));	
	}
	
	function modify_ticket_json()
	{
        $this->load->helper('path');
        
		$ticket_id = $this->input->post("ticket_id");
        
        if ($this->input->post("action") == '2') {
            if (!$this->input->post("billing_time")) die(json_failure("匯款時間未填"));
            if (!$this->input->post("billing_account")) die(json_failure("匯款帳號未填"));
            if (!preg_match('/^[0-9]{5}$/', $this->input->post("billing_account"))) die(json_failure("匯款帳號請填寫末五碼數字"));
            if (!$this->input->post("billing_name")) die(json_failure("匯款戶名未填"));
            $data = array(
                "billing_time" => $this->input->post("billing_time"),
                "billing_account" => $this->input->post("billing_account"),
                "billing_name" => $this->input->post("billing_name"),
                "status" => 2,
            );
        } elseif ($this->input->post("action") == '0') {
            $data = array(
                "status" => 0,
            );
        } elseif ($this->input->post("action") == '3') {
            $data = array(
                "status" => 3,
            );
        } elseif ($this->input->post("action") == '4') {
            $data = array(
                "status" => 4,
            );
        } else {
        
            if ($this->input->post("uid")) $this->DB2->where("uid", $this->input->post("uid"));
            $character = $this->DB2->from("characters")->where("server_id", $this->input->post("server"))->where("name", $this->input->post("character_name"))->get();
            
            if ($character->num_rows()==0) die(json_failure("查無此角色"));
            if ($character->num_rows()>1) die(json_failure("發現重複角色名稱，請輸入uid"));
            
            $row = $character->result();
            $uid = $row[0]->uid;
            $character_id = $row[0]->id;
            
            if ($this->input->post("line")) {
                $this->DB1
                    ->where("uid", $uid)
                    ->update("user_info", array("line" => $this->input->post("line")));
            }
            
            $data = array(
                "uid" => $uid,
                "vip_event_id" => $this->input->post("vip_event_id"),
                "server_id" => $this->input->post("server"),
                "character_id" => $character_id,
                "status" => $this->input->post("status"),
                "cost" => $this->input->post("cost"),
                'admin_uid' => $_SESSION['admin_uid'],
            );
        }
        
        $this->load->library("g_wallet");
            
		if ($ticket_id) {			
			$this->DB1
				->where("id", $ticket_id)
				->update("vip_tickets", $data);
            
            $user_billing = $this->DB2->where("vip_ticket_id", $ticket_id)->where("transaction_type", "vip_billing")->from("user_billing")->get()->row(); 
            $order = $this->g_wallet->get_order($user_billing->id);
            
            switch($this->input->post("action")) {
                case '0':
                    $this->g_wallet->cancel_order($order);
                    break;
                case '2':
                    $this->g_wallet->complete_order($order);
                    break;
                case '3':
                    /*
                    $transfer_id = $this->g_wallet->produce_order($user_billing->uid, "top_up_account", "2", $user_billing->amount, $user_billing->server_id, "", $user_billing->character_id, "", $ticket_id);
                    
                    $transfer_order = $this->g_wallet->get_order($transfer_id);

                    $this->g_wallet->complete_order($transfer_order);
                    */
                    break;
            }
		} else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("vip_tickets", $data);	
			$ticket_id = $this->DB1->insert_id();		
            
            $order_id = $this->g_wallet->produce_order($uid, "vip_billing", "1", $this->input->post("cost"), $this->input->post("server"), "", $character_id, "", $ticket_id);
		}
		
		//die(json_message(array("redirect_url"=> base_url("vip/event_view/".$this->input->post("vip_event_id")), "ticket_status"=>($this->input->post("action"))?$this->input->post("action"):"1"), true));		
		die(json_message(array("redirect_url"=> base_url("vip/event_view/".$this->input->post("vip_event_id")), "ticket_status"=>($this->input->post("action"))?$this->input->post("action"):"1","message"=>"成功"), true));	
	}
		
	function event_list()
	{			
		$this->_init_vip_layout();
        
        header("Cache-Control: private");
        
		$query = $this->DB2->query("
			SELECT 
                t.*, 
                g.name as game_name, 
                u.name, 
                au.name auth_user_name, 
                vt.total as total,
                vt.cancelled_count as cancelled_count,
                vt.pending_count as pending_count,
                vt.complete_count as complete_count,
                vt.delivered_count as delivered_count,
                vt.closed_count as closed_count
            FROM 
                vip_events t
            JOIN admin_users u ON u.uid=t.admin_uid
            LEFT JOIN games g ON g.game_id=t.game_id
            LEFT JOIN admin_users au ON au.uid=t.auth_admin_uid
            LEFT JOIN 
            (
                SELECT 
                    vip_event_id, 
                    SUM(CASE WHEN status>='2' THEN cost ELSE 0 END) 'total', 
                    SUM(CASE WHEN status='0' THEN 1 ELSE 0 END) 'cancelled_count',
                    SUM(CASE WHEN status='1' THEN 1 ELSE 0 END) 'pending_count',
                    SUM(CASE WHEN status='2' THEN 1 ELSE 0 END) 'complete_count',
                    SUM(CASE WHEN status='3' THEN 1 ELSE 0 END) 'delivered_count',
                    SUM(CASE WHEN status='4' THEN 1 ELSE 0 END) 'closed_count'
                FROM vip_tickets
                GROUP BY vip_event_id
            ) AS vt ON vt.vip_event_id=t.id
            WHERE 1=1
                ".(($this->input->get("status")<>'')?" AND t.status ='{$this->input->get("status")}'":"")."
                ".(($this->input->get("game")<>'')?" AND t.game_id ='{$this->input->get("game")}'":"")."
                ".(($this->input->get("is_old")=='old')?" AND t.end_date<now() AND t.end_date!='0000-00-00 00:00:00'":"")."
                ".(($this->input->get("is_old")=='new')?" AND (t.end_date='0000-00-00 00:00:00' OR t.end_date>=now())":"")."
            ORDER BY id DESC
		");   
                    
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
        
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
		
		$this->g_layout
			->add_breadcrumb("查詢")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->set("admin_users", $admin_users)
			->add_js_include("vip/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function event_view($id)
	{
		$this->zacl->check("vip", "read");

		$vip_event = $this->DB2->select("t.*, g.name as game_name, u.name, auth.name as auth_user_name,
                vt.cancelled_count as cancelled_count,
                vt.pending_count as pending_count,
                vt.complete_count as complete_count,
                vt.delivered_count as delivered_count,
                vt.closed_count as closed_count")
			->where("t.id", $id)
			->from("vip_events t")
			->join("games g", "g.game_id=t.game_id", "left")
			->join("admin_users u", "u.uid=t.admin_uid")
			->join("admin_users auth", "auth.uid=t.auth_admin_uid", "left")
			->join("(
                SELECT 
                    vip_event_id, 
                    SUM(CASE WHEN status='0' THEN 1 ELSE 0 END) 'cancelled_count',
                    SUM(CASE WHEN status='1' THEN 1 ELSE 0 END) 'pending_count',
                    SUM(CASE WHEN status='2' THEN 1 ELSE 0 END) 'complete_count',
                    SUM(CASE WHEN status='3' THEN 1 ELSE 0 END) 'delivered_count',
                    SUM(CASE WHEN status='4' THEN 1 ELSE 0 END) 'closed_count'
                FROM vip_tickets
                WHERE vip_event_id = {$id}
                GROUP BY vip_event_id
            ) AS vt", "vt.vip_event_id=t.id", "left")
			->get()->row();
            
		$ticket_status = (null !== $this->input->get_post("ticket_status"))?$this->input->get_post("ticket_status"):1;
        
		$vip_tickets = $this->DB2
			->select("vt.*, au.name as admin_uname, ui.line, s.name as server_name, c.name as character_name, c.in_game_id as in_game_id")
			->from("vip_tickets vt")
			->join("servers s", "s.server_id=vt.server_id", "left")
			->join("characters c", "c.id=vt.character_id", "left")
			->join("admin_users au", "au.uid=vt.admin_uid", "left")
			->join("users u", "u.uid=vt.uid", "left")
			->join("user_info ui", "ui.uid=vt.uid", "left")
			->where("vt.vip_event_id", $id)
			->where("vt.status", $ticket_status)
            ->order_by("vt.id", "desc")->get();
		
        $modify_acl = $this->zacl->check_acl("vip", "modify");
        $authorize_acl = $this->zacl->check_acl("vip", "authorize");
        
		$this->_init_vip_layout()
			->add_breadcrumb("檢視")
			->add_js_include("vip/view")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("vip_event", $vip_event)
			->set("vip_tickets", $vip_tickets)
			->set("servers", $this->DB2->where("game_id", $vip_event->game_id)->from("servers")->get())
			->set("modify_acl", $modify_acl)
			->set("authorize_acl", $authorize_acl)
			->render();
	}	
	
	
	function view($id)
	{
		$this->zacl->check("vip", "modify");

		$vip = $this->DB2->select("t.*, g.name as game_name, u.name, au.name allocate_user_name, ccu.name cc_user_name")
			->where("t.id", $id)
			->from("vips t")
			->join("admin_users u", "u.uid=t.admin_uid")
			->join("games g", "g.game_id=t.game_id", "left")
			->join("admin_users au", "au.uid=t.allocate_admin_uid", "left")
			->join("admin_users ccu", "ccu.uid=t.cc_admin_uid", "left")
			->get()->row();
		
		if ($vip->status == '1') {
			$this->DB2->where("id", $id)->update("vips", array("is_read"=>'1'));
		}
		
		$replies = $this->DB2
			->select("tr.*, au.name as admin_uname")
			->from("vip_replies tr")
			->join("admin_users au", "au.uid=tr.admin_uid", "left")
			->where("vip_id", $id)->order_by("tr.id", "asc")->get();

		$allocate_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();
		
		$this->_init_vip_layout()
			->add_breadcrumb("檢視")
			->add_js_include("vip/view")
			->set("vip", $vip)
			->set("replies", $replies)
			->set("allocate_users", $allocate_users)
			->render();
	}	
	
	function edit_reply($id)
	{
		$row = $this->DB2->where("id", $id)->from("question_replies")->get()->row();
		
		$this->_init_service_layout()
			->add_breadcrumb("編輯回覆")
			->add_js_include("vip/edit_reply")
			->set("row", $row)
			->render();		
	}
	
	function modify_reply_json()
	{
		$vip_id = $this->input->post("vip_id");
		$id = $this->input->post("reply_id");
		
		$data = array(
			"uid" => 0,
			"vip_id" => $vip_id,
			'content' => nl2br($this->input->post("content")),
			'admin_uid' => $_SESSION['admin_uid'],
		);		
		
		if ($id) {			
			$row = $this->DB2->from("vip_replies")->where("id", $id)->get()->row();
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'vip_reply', 'update', 
					"編輯回覆 #{$id} {$row->content} => {$data['content']}");
						
			$this->DB1
				->where("is_official", "1")
				->where("id", $id)
				->update("vip_replies", $data);
		}
		else {
			$this->DB1
				->set("create_time", "now()", false)
				->insert("vip_replies", $data);	
		}
		
		die(json_success());		
	}

	function update_note_json()
	{
		$this->DB1->where("id", $this->input->post("vip_id"))
			->update("vips", array('note' => $this->input->post("note")));
		
		die(json_success());		
	}		
	
	function allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."轉交：".$this->input->post("result")."<br>";		
		$this->DB1->where("id", $this->input->post("vip_id"))
			->set('allocate_date', 'NOW()', false)
			->set('allocate_result', $result)
			->update("vips", array('allocate_admin_uid' => $this->input->post("allocate_admin_uid")));
			
		die(json_success());		
	}
		
	function move_vip_event($id)
	{
		$status = $this->input->get_post("status", TRUE);
        
		if (!$this->zacl->check_acl("vip", "authorize") && $status==2) die(json_failure("無核准權限"));
        
		$this->DB1->set("status", $status)->where("id", $id)->update("vip_events");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());	
		}
		else {
			die(json_failure("異常錯誤"));
		}
	}
		
	function move_vip_ticket($id)
	{
		$status = $this->input->get_post("status", TRUE);
            
		$this->DB1->set("status", $status)->where("id", $id)->update("vip_tickets");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());	
		}
		else {
			die(json_failure("異常錯誤"));
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */