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
				}
				else {
					
					if (empty($cnt)) $cnt = 1;
					else $cnt++;
					
					$this->input->set_cookie('login_cnt', $cnt, '180', '', '/', '');					
					$error_message = '帳號或密碼錯誤';
					
					$this->load->model("log_admin_actions");
					$this->log_admin_actions->insert_log(0, 'login', '', "登入帳號 {$this->input->post("account")} 失敗");
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */