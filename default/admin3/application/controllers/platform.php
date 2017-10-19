<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->zacl->check_login(true);
		
		$this->_init_layout();
		$this->g_layout->render();
	}
	
	function login()
	{
		$error_message = '';
		if ($this->input->post()) {
			
			$cnt = $this->input->cookie('login_cnt');
			if ($cnt >= 5) $error_message = '登入錯誤超過限制，請稍候再試。';
			else {
				
				$query = $this->DB2->from("admin_users")
					->where("account", $this->input->post("account"))
					->where("password", $this->zacl->encode($this->input->post("password")))->get();
				if ($query->num_rows() > 0) {
					$row = $query->row();
					$_SESSION["admin_uid"] = $row->uid;
					$_SESSION["admin_account"] = $row->account;
					$_SESSION["admin_role"] = $row->role;
					$_SESSION["admin_name"] = $row->name;
									
					$allow_games = array();
					$query = $this->DB2->from("admin_permissions")->where("role", $row->role)->where("resource in (select game_id from games)", null, false)->get();
					foreach($query->result() as $row) {
						$allow_games[] = $row->resource;
					}			
					$_SESSION["admin_allow_games"] = $allow_games;
					
					header("location:".$this->input->post("redirect_url"));
					exit();
				} else if ($this->input->post("password")) {
					
					// connect to ldap server
					$this->config->load('ldap');
					$ldap_connect = $this->config->item('ldap_connect');
						
                    $ldapconn = ldap_connect($ldap_connect['host'], $ldap_connect['port'])  or die("Could not connect to LDAP server.");
                    $set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
					
					$ldap_bd = @ldap_bind($ldapconn, $this->input->post("account").$ldap_connect['domain'], $this->input->post("password"));

					if($ldap_bd) {
                        $result = ldap_search($ldapconn,"dc=".$ldap_connect['dc1'].",dc=".$ldap_connect['dc2'],"(sAMAccountName=".$this->input->post("account").")") or die ("Error in query");

                        $data = ldap_get_entries($ldapconn,$result);
						
				        $query = $this->DB2->from("admin_users")
					        ->where("account", $this->input->post("account"))->get();
							
					    $ldap_title = "";
						foreach ($data[0]["memberof"] as $title) {
							if (strpos($title, "職務_")) {
								$title_explode = explode(',', $title);
								$title_explode2 = explode('_', $title_explode[0]);
								
								$ldap_title = $title_explode2[1];
								break;
							}
						}
							
						$ldap_roles = $this->config->item('ldap_roles');
						
						$ldap_role = ($ldap_roles[$ldap_title])?$ldap_roles[$ldap_title]:"op";
						
						if ($query->num_rows() > 0) {
					        $row = $query->row();
							
					        $_SESSION["admin_uid"] = $row->uid;
					        $_SESSION["admin_account"] = $row->account;
					        $_SESSION["admin_role"] = $row->role;
					        $_SESSION["admin_name"] = $data[0]["name"][0];
							
							if ($row->name <> $data[0]["name"][0] || $row->role <> $ldap_role) {
				                $this->DB1->where("uid", $this->input->post("key"))
					                ->update("admin_users", array(
								         "name" => $data[0]["name"][0],
								         "role" => $ldap_role,
							        ));
							}
						} else {
				            $this->DB1->insert("admin_users", array(
								"account" => $this->input->post("account"),
								"name" => $data[0]["name"][0],
								"role" => $ldap_role,
							));
							
					        $_SESSION["admin_uid"] = $this->DB1->insert_id();
					        $_SESSION["admin_account"] = $row->account;
					        $_SESSION["admin_role"] = $ldap_role;
					        $_SESSION["admin_name"] = $data[0]["name"][0];
						}
									
					    $allow_games = array();
					    $query = $this->DB2->from("admin_permissions")->where("role", $ldap_role)->where("resource in (select game_id from games)", null, false)->get();
					    foreach($query->result() as $row) {
						    $allow_games[] = $row->resource;
					    }			
					    $_SESSION["admin_allow_games"] = $allow_games;
					
					    header("location:".$this->input->post("redirect_url"));
					    exit();
					} else {
					    if (empty($cnt)) $cnt = 1;
					    else $cnt++;
					
					    $this->input->set_cookie('login_cnt', $cnt, '180', '', '/', '');					
					    $error_message = '帳號或密碼錯誤';
					
					    $this->load->model("log_admin_actions");
					    $this->log_admin_actions->insert_log(0, 'login', '', "登入帳號 {$this->input->post("account")} 失敗");
					}
					ldap_close($ldapconn);
				} else {		
					$error_message = '請輸入密碼';
				}
			}
		}
		$redirect_url = urldecode($this->input->get("redirect_url"));
		if (empty($redirect_url)) {
			$redirect_url = @$_SERVER['HTTP_REFERER'];
			if (strpos($redirect_url, "platform/log") !== false || empty($redirect_url)) {
				$redirect_url = site_url("/");
			}
		}
		
		$this->_init_layout()
			->set("error_message", $error_message)
			->set("redirect_url", $redirect_url)
			->render();
	}
	
	function logout() 
	{
		unset($_SESSION["admin_uid"]);
		unset($_SESSION["admin_account"]);
		unset($_SESSION["admin_role"]);
		
		header("location:".site_url("platform/login"));
		exit();
	}
	
	function partner_index($partner)
	{		
		$this->zacl->check_login(true);
		
		$this->_init_layout()
			->set("partner", $partner)
			->render("", "partner");
	}

	function partner_modify_password($partner)
	{
		$this->zacl->check_login(true);
		
		$this->_init_layout();
		
		$uid = $_SESSION["admin_uid"];
		
		if ($this->input->post()) {			
			$this->DB1->where("uid", $uid)
				->update("admin_users", array(
							"password" => $this->zacl->encode($this->input->post("password")),
						));
			$this->g_layout->set("result", $this->DB1->affected_rows()>0);
		}		
		
		$row = $this->DB2->get_where("admin_users", array("uid"=>$uid))->row();
		
		$this->g_layout
			->add_breadcrumb("修改密碼")
			->set("row", $row)
			->set("partner", $partner)
			->render("", "partner");	
	}
	
	
	function modify_password($uid='')
	{
		if (empty($uid)) $uid = $_SESSION["admin_uid"];
		
		if ($uid !== $_SESSION["admin_uid"]) {
			$this->zacl->check("manage_user", "modify");
		}

		$this->_init_layout();
		
		if ($this->input->post()) {			
			$this->DB1->where("uid", $uid)
				->update("admin_users", array(
							"password" => $this->zacl->encode($this->input->post("password")),
						));
			$this->g_layout->set("result", $this->DB1->affected_rows()>0);
		}
		
		$row = $this->DB2->get_where("admin_users", array("uid"=>$uid))->row();
		
		$this->g_layout
			->add_breadcrumb("修改密碼")
			->set("row", $row)
			->render();
	}	
	
	function schedule()
	{		
		$this->zacl->check_login(true);
        $this->zacl->check_acl("event", "read")
		
        $year = $this->input->get_post("year") ? $this->input->get_post("year") : "";
        $month = $this->input->get_post("month") ? $this->input->get_post("month") : "";
        
        if (empty($year) || empty($month)) {
            $year  = date("Y");
            $month = date("m");
            $prev_year  = date("Y", strtotime("-1 month"));
            $prev_month = date("m", strtotime("-1 month"));
            $next_year  = date("Y", strtotime("+1 month"));
            $next_month = date("m", strtotime("+1 month"));
        } else {
            $set_date = strtotime($year.'-'.$month.'-1');
            $prev_year  = date("Y", strtotime("-1 month", $set_date));
            $prev_month = date("m", strtotime("-1 month", $set_date));
            $next_year  = date("Y", strtotime("+1 month", $set_date));
            $next_month = date("m", strtotime("+1 month", $set_date));
        }
        
        ($year==date("Y") && $month==date("m"))?$day=date("d"):$day="";
        
		$bulletin_start = $this->DB2->select("*, DAY(start_time) 'day'")->where("YEAR(start_time)", $year)->where("MONTH(start_time)", $month)->get("bulletins");
        
		$bulletin_end = $this->DB2->select("*, DAY(end_time) 'day'")->where("YEAR(end_time)", $year)->where("MONTH(end_time)", $month)->get("bulletins");
        
		$event_start = $this->DB2->select("*, DAY(begin_time) 'day'")->where("YEAR(begin_time)", $year)->where("MONTH(begin_time)", $month)->get("events");
        
		$event_end = $this->DB2->select("*, DAY(end_time) 'day'")->where("YEAR(end_time)", $year)->where("MONTH(end_time)", $month)->get("events");
        
		$event_fullfill = $this->DB2->select("*, DAY(fulfill_time) 'day'")->where("YEAR(fulfill_time)", $year)->where("MONTH(fulfill_time)", $month)->get("events");
        
		$vip_start = $this->DB2->select("*, DAY(start_date) 'day'")->where("YEAR(start_date)", $year)->where("MONTH(start_date)", $month)->get("vip_events");
        
		$vip_end = $this->DB2->select("*, DAY(end_date) 'day'")->where("YEAR(end_date)", $year)->where("MONTH(end_date)", $month)->get("vip_events");
        
		$this->_init_layout()
			->set("year", $year)
			->set("month", $month)
			->set("day", $day)
			->set("prev_year", $prev_year)
			->set("prev_month", $prev_month)
			->set("next_year", $next_year)
			->set("next_month", $next_month)
			->set("bulletin_start", isset($bulletin_start) ? $bulletin_start : false)
			->set("bulletin_end", isset($bulletin_end) ? $bulletin_end : false)
			->set("event_start", isset($event_start) ? $event_start : false)
			->set("event_end", isset($event_end) ? $event_end : false)
			->set("event_fullfill", isset($event_fullfill) ? $event_fullfill : false)
			->set("vip_start", isset($vip_start) ? $vip_start : false)
			->set("vip_end", isset($vip_end) ? $vip_end : false)
			->add_css_link('schedule')
			->render("");
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */