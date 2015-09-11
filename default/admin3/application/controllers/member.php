<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MY_Controller {

	function __construct() 
	{
		parent::__construct();				
		$this->zacl->check_login(true);
		$this->zacl->check("member", "read");		
	}	
	
	function _init_member_layout()
	{
		return $this->_init_layout()
			->add_breadcrumb("會員", "member");
	}
	
	function index()
	{
		$this->_init_member_layout();
		$this->load->helper("output_table");
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private"); 
					
			$this->DB2->start_cache();
			
			$this->DB2->select("u.*, uf.name");
			$this->DB2->from("users u");
			$this->DB2->join("user_info uf", "uf.uid=u.uid", 'left');
			
			$this->input->get("uid") && $this->DB2->where("u.uid", trim($this->input->get("uid")));
			$this->input->get("euid") && $this->DB2->where("u.uid", $this->g_user->decode(trim($this->input->get("euid"))));			
			//$this->input->get("account") && $this->DB2->where("u.account", trim($this->input->get("account")));			
			$this->input->get("name") && $this->DB2->where("u.name", trim($this->input->get("name")));			

			if ($this->input->get("character_name")) {
				$this->DB2->join("characters gsr", "gsr.uid=u.uid")
					->where("gsr.name", trim($this->input->get("character_name")))				
					->where("gsr.id = (select max(id) from characters where uid=gsr.uid and name=gsr.name)", null, false)
					;
			}
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("u.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("u.create_time >= {$start_date}", null, false);
			}
			
			
			if ($channel = $this->input->get("channel")) {				
				if ($channel == 'long_e') $this->DB2->not_like("u.account", "@");
				else $this->DB2->where("u.account like '%@{$channel}'", null, false);
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 
					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(10, $this->input->get("record"))->order_by("u.uid desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);					
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("member?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 10
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;			
					
				case "成長數統計":		
					$this->zacl->check("member", "statistic");
					
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->DB2->select("LEFT(u.create_time, {$len}) title, count(*) cnt, '會員數' name, 'key' `key`", false)
						->group_by("title")
						->order_by("title desc")->get();
					break;
			}
			
			
			$this->DB2->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;				 
		}
		
		$this->g_layout
			->add_breadcrumb("查詢")
			->add_js_include("member/index")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("query", isset($query) ? $query : false)
			->render();
	}
	
	function view($uid) 
	{
		$balance_sql = "
SELECT 
	x.uid,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=1 AND result=1 AND uid=x.uid GROUP BY uid), 0) aq,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=2 AND result=1 AND uid=x.uid GROUP BY uid), 0) amount,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=3 AND result=1 AND uid=x.uid GROUP BY uid), 0) rq,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=4 AND result=1 AND uid=x.uid GROUP BY uid), 0) gq,
        x.balance
FROM users x
WHERE x.uid={$uid}";
		
		$user = $this->DB2
					->select("u.*, lgl.create_time as last_login_date, ui.ident, ui.ban_reason, ui.ban_date")
					->from("users u")->where("u.uid", $uid)
					->join("log_game_logins lgl", "u.uid=lgl.uid and is_recent='1'", "left")
					->join("user_info ui", "u.uid=ui.uid", "left")
					->order_by("lgl.create_time desc")
					->get()->row();
		$balance = $this->DB2->query($balance_sql)->row();
		
		
		$role = $this->DB2->select("gsr.*, g.name as game_name, gi.name as server_name")
			->from("characters gsr")
			->join("servers gi", "gi.server_id=gsr.server_id")
			->join("games g", "g.game_id=gi.game_id")
			->where("gsr.account", $user->account)->order_by("gsr.create_time desc")->get();
		
		$bind = $this->DB2->from("users")->where("bind_uid", $uid)->get()->row();
		
		$this->_init_member_layout()
			->add_breadcrumb("查看")
			->set("user", $user)
			->set("bind", $bind)
			->set("balance", $balance)
			->set("role", $role)
			->render();
	}
	
	function testaccounts()
	{
		$this->zacl->check("testaccounts", "read");
		
		$query = $this->DB2->from("testaccounts")->order_by("id desc")->get();
		
		$this->_init_member_layout()
			->add_breadcrumb("測試帳號")
			->set("query", $query)
			->render();
	}
	
	function modify_testaccounts($id='')
	{
		$this->zacl->check("testaccounts", "modify");
		
		$this->_init_member_layout();
		
		if ($this->input->post())
		{
			if ($this->input->post("id")) {
				$this->DB1->where("id", $this->input->post("id"))
					->update("testaccounts", array(
								"note" => $this->input->post("note"),
								//"update_time" => now(),
							));
				$this->g_layout->set("result", $this->DB1->affected_rows()>0);
			}
			else {
				$this->DB1->insert("testaccounts", array(
								"uid" => $this->input->post("uid"),
								"account" => $this->input->post("account"),
								"note" => $this->input->post("note"),
								//"create_time" => now(),
							));		
				header("location:".site_url("member/testaccounts"));
				exit();
			}	
		}			
		
		if ($id) {
			$row = $this->DB2->get_where("testaccounts", array("id"=>$id))->row();
		} else $row = false;
		
		$this->g_layout
			->add_breadcrumb("測試帳號", "member/testaccounts")
			->add_breadcrumb("編修")
			->set("row", $row)
			->render();	
	}
	
	function delete_testaccounts($id)
	{
		if ( ! $this->zacl->check_acl("testaccounts", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->delete("testaccounts", array("id"=>$id));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}
	
	function set_right($uid, $right)
	{
		$right = intval($right); 
		if ( ! $this->zacl->check_acl("member", "lock")) die(json_failure("沒有權限"));
		
		$this->DB1->where("uid", $uid)
			->update("users", array("is_banned" => $right));
		
		$this->DB1->where("uid", $uid)
			->set("ban_date", "NOW()", false)
			->update("user_info", array("ban_reason" => $this->input->post("cause")));
		
		if ($this->DB1->affected_rows()>0) {
			$this->load->model("log_admin_actions");
			$action = ($right == 1 ? '停權' : '解除停權');
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'member', 'lock', "{$action} #{$uid}");
			echo json_success();	
		}
		else echo json_failure();
	}
	
	function switch_account($uid)
	{
		$this->zacl->check("member", "switch");
		
		if ($this->g_user->switch_uid($uid)) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'member', 'switch', "登入玩家 #{$uid} 帳號");	
			
			header("location: ".base_url());		
		}
		else {
			die('帳號不存在');
		}
	}
	
	function no_balance()
	{
		$this->zacl->check("member", "no_balance");
		
		$result = array();		
		if ($this->input->get("action"))
		{
			$limit = 20000;
			$offset = $cnt = 0;
			
			do {
				$sql = "
	SELECT 
		x.uid,
	        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=1 AND result=1 AND uid=x.uid GROUP BY uid), 0) aq,
	        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=2 AND result=1 AND uid=x.uid AND transaction_type in ('top_up_account', 'co25055109oz', 'co25055109oz') GROUP BY uid), 0) amount,
	        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=3 AND result=1 AND uid=x.uid GROUP BY uid), 0) rq,
	        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=4 AND result=1 AND uid=x.uid GROUP BY uid), 0) gq,
	        balance,
	        ta.uid test
	FROM users x
	join user_billing ub on ub.uid=x.uid
		and not exists (select 1 from user_billing where uid=ub.uid and id>ub.id)
	left join testaccounts ta on ta.uid=x.uid
	where x.uid between {$offset} and ".($offset+$limit)." 
	;				
				";
				$query = $this->DB2->query($sql);
				$result = array_merge($result, $query->result());
				//var_dump($query);
				
				$cnt = $query->num_rows();
				$offset += $limit;			
				
				usleep(50000); //500毫秒
			}
			while ($cnt > 0);
			
			foreach ($result as $k => $row) {
				if ( ($row->aq + $row->rq + $row->gq) - ($row->amount + $row->balance) == 0) {
					unset($result[$k]);
				}
			}
		}
		
		$this->_init_member_layout()
			->add_breadcrumb("不平衡帳號")
			->set("result", $result)
			->render();		
	}		
	
	function is_banned()
	{
		$this->zacl->check("member", "is_banned");
		
		$query = $this->DB2->select("u.*, ui.ban_date, ui.ban_reason")
		    ->from("users u")
			->join("user_info ui", "u.uid=ui.uid")
			->where("is_banned", 1)->get();
		
		$this->_init_member_layout()
			->add_breadcrumb("停權帳號")
			->set("query", $query)
			->render();		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */