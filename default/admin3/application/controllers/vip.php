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
		return $this->_init_layout()->add_breadcrumb("VIP", "vip");
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
			'product_id' => $this->input->post("product_id"),
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
				die((array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
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
            $new_status = 2;
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
        } elseif ($this->input->post("action") == 'cancelled') {
            $new_status = 0;
            $data = array(
                "status" => 0,
            );
        } elseif ($this->input->post("action") == '3') {
            $new_status = 3;
            $data = array(
                "status" => 3,
                'auth_admin_uid' => $_SESSION['admin_uid'],
            );
        } elseif ($this->input->post("action") == '4') {
            $new_status = 4;
            $data = array(
                "status" => 4,
            );
        } else {
            $new_status = 1;

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

			$cost = ($this->input->post("cost"))?$this->input->post("cost"):0;
			$product_id = ($this->input->post("product_id"))?$this->input->post("product_id"):"";

            $data = array(
                "uid" => $uid,
                "vip_event_id" => $this->input->post("vip_event_id"),
                "server_id" => $this->input->post("server"),
                "character_id" => $character_id,
                "status" => $this->input->post("status"),
                "cost" => $this->input->post("cost"),
                "product_id" => $this->input->post("product_id"),
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
                case 'cancelled':
					$transfer_order = $this->DB2->where("vip_ticket_id", $ticket_id)->where("transaction_type", "top_up_account")->from("user_billing")->get()->row();

                    $this->g_wallet->cancel_order($order, $this->input->post("note"));
                    $this->g_wallet->cancel_order($transfer_order, $this->input->post("note"));
                    break;
                case '2':
                    $this->g_wallet->complete_order($order);
                    break;
                case '3':
                    $transfer_id = $this->g_wallet->produce_order($user_billing->uid, "top_up_account", "2", $user_billing->amount, $user_billing->server_id, "", $user_billing->character_id, "", $ticket_id, $this->input->post("product_id"));

                    $transfer_order = $this->g_wallet->get_order($transfer_id);
                    $this->g_wallet->complete_order($transfer_order);

					if ($this->input->post("product_id")) {
						$server = $this->DB2->from("servers")->where("server_id", $user_billing->server_id)->get()->row();
						$this->load->library("game_api/{$server->game_id}");

						$game = $this->DB2->from("games")->where("game_id", $server->game_id)->get()->row();
						$res = $this->{$server->game_id}->transfer($user_billing->server_id, $transfer_order, $user_billing->amount, $game->exchange_rate, $this->input->post("product_id"));
					}
                    break;
            }
		} else {
			$this->DB1
				->set("create_time", "now()", false)
				->set("update_time", "now()", false)
				->insert("vip_tickets", $data);
			$ticket_id = $this->DB1->insert_id();

            $order_id = $this->g_wallet->produce_order($uid, "vip_billing", "1", $this->input->post("cost"), $this->input->post("server"), "", $character_id, "", $ticket_id, $this->input->post("product_id"));
		}

		//die(json_message(array("redirect_url"=> base_url("vip/event_view/".$this->input->post("vip_event_id")), "ticket_status"=>($this->input->post("action"))?$this->input->post("action"):"1"), true));
		die(json_message(array("redirect_url"=> base_url("vip/event_view/".$this->input->post("vip_event_id")."?ticket_status=".$new_status."#tickets"), "message"=>"成功"), true));
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
            LEFT JOIN admin_users u ON u.uid=t.admin_uid
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
                ".(($this->input->get("is_old")=='new' || ($this->input->get("is_old")==''))?" AND (t.end_date='0000-00-00 00:00:00' OR t.end_date>=now())":"")."
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
			->join("admin_users u", "u.uid=t.admin_uid", "left")
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
			->select("vt.*, au.name as admin_uname, aau.name as auth_admin_uname, ui.line, s.name as server_name, c.name as character_name, c.in_game_id as in_game_id, ub.note as note")
			->from("vip_tickets vt")
			->join("user_billing ub", "ub.vip_ticket_id=vt.id and ub.transaction_type='vip_billing'", "left")
			->join("servers s", "s.server_id=vt.server_id", "left")
			->join("characters c", "c.id=vt.character_id", "left")
			->join("admin_users au", "vt.admin_uid=au.uid", "left")
			->join("admin_users aau", "vt.auth_admin_uid=aau.uid", "left")
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
			->join("admin_users u", "u.uid=t.admin_uid", "left")
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

function user_dashboard($game_id)
{
	$role_id =$this->input->get("user");
	$this->zacl->check("vip", "modify");

	$vip = $this->DB2->select("uid,char_name,char_in_game_id,server_name,ip,country,vip_ranking,site,line_id,mobile")
		->select("DATE_FORMAT(line_date, '%Y-%m-%d') 'line_date'",false)
		->where("site", $game_id)
		->where("char_in_game_id", $role_id)
		->from("whale_users")
		->get()->row();

	$admins = $this->DB2->select("t.admin_uid,u.name")
		->where("role_id", $role_id)
		->from('vip_requests t')
		->join("admin_users u", "u.uid=t.admin_uid", "left")
		->group_by(array("admin_uid", "name"))
		->get();

	$this->_init_layout()
		->add_breadcrumb("鯨魚用戶","user_statistics/whale_users?game_id={$game_id}&orderby=deposit_total+desc&action=鯨魚用戶統計")
		->add_breadcrumb("用戶資料檢視")
		->add_js_include("vip/dashboard")
		->add_js_include("jquery-ui-timepicker-addon")
		->add_js_include("fontawesome/js/fontawesome-all")
		->set("vip", $vip)
		->set("admins", $admins)
		->render();
}


function update_vip_info()
{
	$vip_uid = $this->input->post("vip_uid");
	$game_id = $this->input->post("game_id");
	$line_id = $this->input->post("line_id");
	$line_date = $this->input->post("line_date");
	$data = array(
		'line_id'	=> $line_id,
		'line_date' => $line_date,
	);

	//die(json_success($data));
	if (empty($vip_uid) || empty($game_id) || (empty($line_id) && empty($line_date))  )
	{
		die(json_failure("資料不完整".$data));
	}

	$this->DB2->where("uid", $vip_uid)->where("site", $game_id)
		->update("whale_users", $data);

	if ($this->DB2->affected_rows() > 0) {
		die(json_success());
	}
	else {
		die(json_failure("資料未變更"));
	}

}


function add_vip_request()
{
	$role_id = $this->input->post("role_id");
	$game_id = $this->input->post("game_id");
	$service_type = $this->input->post("service_type");
	$request_code = $this->input->post("request_code");
	$note = $this->input->post("note");

	$data = array(
		'game_id'	=> $game_id,
		'role_id'	=> $role_id,
		'service_type'	=> $service_type,
		'request_code' => $request_code,
		'note' => $note,
		'admin_uid' => $_SESSION['admin_uid'],
	);

	//die(json_success($data));
	//game_id=h35naxx1hmt&role_id=390709&service_type=0&request_code=0&note=邀請加入
	if (empty($role_id) || empty($game_id) || (empty($service_type) && empty($request_code))  )
	{
		die(json_failure("資料不完整".$data));
	}

	$this->DB2->insert("vip_requests", $data);
	$request_id = $this->DB2->insert_id();
	if ($request_id > 0) {
		$record = $this->DB2->select("t.id,t.request_code,t.note,t.create_time,u.name")
			->where("t.id",$request_id)
			->from('vip_requests t')
			->join("admin_users u", "u.uid=t.admin_uid", "left")->get()->result();


		die(json_success($record[0]));
	}
	else {
		die(json_failure("資料未變更"));
	}

}
//傳入遊戲和角色就得到該角色的服務歷程
function vip_request_list($game_id,$type,$page_num)
{
	$role_id = $this->input->get("role_id");
	switch ($type) {
		case '1':
			$service_request = $this->config->item('h35vip_service_request');
			break;
		case '2':
			$service_request = $this->config->item('h35vip_service_feedback');
			break;
		case '3':
			$service_request = array("3" => "邀請加入",);
			break;
		default:
		$service_request = $this->config->item('h35vip_service_request');
		break;
	}

	//select id,game_id,role_id,service_type, request_code, note, create_time, admin_uid from vip_requests
	//"request_code=" + request_code +"&note=" + note;
	$this->DB2->start_cache();
	$this->DB2->select("t.id,t.request_code,t.note,t.create_time,u.name")
		->where("role_id", $role_id)
		->where("game_id", $game_id)
		->where("service_type", $type)
		->from('vip_requests t')
		->join("admin_users u", "u.uid=t.admin_uid", "left");

		if ($request_code = $this->input->get("request_code")) {
			$this->DB2->where("t.request_code = '{$request_code}'");
		}

		if ($note = $this->input->get("note")) {
			$this->DB2->where("t.note like '%{$note}%'");
		}

		if ($admin_uid = $this->input->get("admin_uid")) {
			$this->DB2->where("t.admin_uid = '{$admin_uid}'");
		}



		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date")." 23:59:59" : date("Y-m-d");
		if (!empty($this->input->get("start_date")))  {
			$this->DB2->where("t.create_time between '{$start_date}' and '{$end_date}'");
		}

		//die("t.create_time between '{$start_date}' and '{$end_date}'");



		$this->DB2->stop_cache();
		$total_rows = $this->DB2->count_all_results();
		$records =  $this->DB2->limit(10, ($page_num-1)*10)->order_by("t.create_time desc")->get();

		$data = array();
		foreach($records->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'request_code' => $row->request_code,
				'request_text' => $service_request[$row->request_code],
				'note' => $row->note,
				'create_time' =>  $row->create_time,
				'admin_name' =>  $row->name,
			);
		}

		$result_obj = new stdClass();

		$result_obj->page_count = ($total_rows % 10) == 0 ? $total_rows/10: ceil($total_rows/10) ;
		$result_obj->logs = $data ;


		//header('Access-Control-Allow-Origin: *');
		die(json_encode($result_obj));
}

function del_vip_request()
{
	$request_id = $this->input->post("record_id");

	if (empty($request_id))
	{
		die(json_failure("資料不完整"));
	}

	$this->DB2->delete("vip_requests", array('id' => $request_id));

	if ($this->DB2->affected_rows() > 0) {
		die(json_success());
	}
	else {
		die(json_failure("異常錯誤"));
	}


}

function requests_report($game_id)
{
	$this->zacl->check("vip", "modify");


	$this->_init_layout()
		->set("game_id", $game_id)
		->add_breadcrumb("鯨魚用戶","user_statistics/whale_users?game_id={$game_id}&orderby=deposit_total+desc&action=鯨魚用戶統計")
		->add_breadcrumb("服務記錄列表")
		->add_js_include("vip/report")
		->render();
}

//傳入遊戲和角色就得到該角色的服務歷程
function requests_report_data($game_id)
{

	$service_type = $this->input->get("service_type");
	switch ($service_type) {
		case '1':
			$service_request = $this->config->item('h35vip_service_request');
			break;
		case '2':
			$service_request = $this->config->item('h35vip_service_feedback');
			break;
		default:
		$service_request = $this->config->item('h35vip_service_request');
		break;
	}
	//select id,game_id,role_id,service_type, request_code, note, create_time, admin_uid from vip_requests
	//"request_code=" + request_code +"&note=" + note;
	$this->DB2->start_cache();
	$this->DB2->select("t.id,t.role_id,w.char_name ,t.request_code,t.note,t.create_time,u.name")
		->where("t.game_id", $game_id)
		->where("t.service_type", $service_type)
		->from('vip_requests t')
		->join("admin_users u", "u.uid=t.admin_uid", "left")
		->join("whale_users w", "w.char_in_game_id= t.role_id", "left");


		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date")." 23:59:59" : date("Y-m-d");
		if (!empty($this->input->get("start_date")))  {
			$this->DB2->where("t.create_time between '{$start_date}' and '{$end_date}'");
		}
		$this->DB2->stop_cache();
		$records =  $this->DB2->order_by("t.create_time desc")->get();

		$data = array();
		foreach($records->result() as $row) {


			$data[] = array(
				'id' => $row->id,
				'role_id' => $row->role_id,
				'role_name' => $row->char_name,
				'request_code' => $row->request_code,
				'request_text' => $service_request[$row->request_code],
				'note' => $row->note,
				'create_time' =>  $row->create_time,
				'admin_name' =>  $row->name,
			);
		}

		$result_obj = new stdClass();



		//header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
}


function inactive_users($game_id)
{
	$this->zacl->check("vip", "modify");

	$this->_init_layout()
		->set("game_id", $game_id)
		->add_breadcrumb("鯨魚用戶","user_statistics/whale_users?game_id={$game_id}&orderby=deposit_total+desc&action=鯨魚用戶統計")
		->add_breadcrumb("流失用戶列表")
		->add_js_include("vip/report")
		->render();
}

function inactive_users_report($game_id)
{
	$vip_ranking = $this->config->item('vip_ranking');

	$date_column = $this->input->get("date_column");

	$this->DB2->start_cache();
	$this->DB2->select("t.uid,t.char_name,t.char_in_game_id ,t.vip_ranking,t.last_login,t.latest_topup_date,t.inactive_confirm_date")
		->where("site", $game_id)
		->where("last_login is not null")
		->from('whale_users t');

		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date")." 23:59:59" : date("Y-m-d");
		if (!empty($this->input->get("start_date")))  {
			$this->DB2->where("t.{$date_column} between '{$start_date}' and '{$end_date}'");
		}
		$this->DB2->stop_cache();
		$records =  $this->DB2->order_by("t.{$date_column} desc")->get();

		$data = array();
		foreach($records->result() as $row) {
			$vip_ranking_text = "";
			if ($row->vip_ranking)
			{
				$vip_ranking_text = $vip_ranking[$row->vip_ranking];
			}

			$data[] = array(
				'account' => $row->uid,
				'role_id' => $row->char_in_game_id,
				'role_name' => $row->char_name,
				'vip_ranking' =>  $vip_ranking_text,
				'latest_topup_date' => $row->latest_topup_date,
				'last_login' => $row->last_login,
				'inactive_confirm_date' =>  $row->inactive_confirm_date,

			);
		}

		$result_obj = new stdClass();



		//header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
