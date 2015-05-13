<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends MY_Controller {
		
	function __construct() 
	{
		parent::__construct();	
	}	
	
	function _init_log_layout()
	{
		return $this->_init_layout()->add_breadcrumb("記錄", "log");	
	}
	
	function index()
	{
		$this->_init_log_layout()->render();
	}
	
	function login()
	{
		$this->zacl->check_login(true);		
		
		$this->zacl->check("login", "read");
		
		$this->_init_log_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db
				->from("log_logins ll")
				->join("games g", "ll.site=g.game_id", "left");
			
			$this->input->get("site") && $this->db->where("ll.site", $this->input->get("site"));
			$this->input->get("uid") && $this->db->where("ll.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("ll.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("account") && $this->db->like("ll.account", trim($this->input->get("account")));
			$this->input->get("ip") && $this->db->where("ll.ip", trim($this->input->get("ip")));
			$this->input->get("ad_channel") && $this->db->where("ll.ad", $this->input->get("ad_channel"));											
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("site")) {				 
					$this->zacl->check($this->input->get("site"), "read");
				}
				else $this->db->where_in("ll.site", $_SESSION["admin_allow_games"]);
			}						
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ll.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ll.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->db->not_like("ll.account", "@");
				else $this->db->where("ll.account like '%@{$channel}'", null, false);
			}
			
			if ($this->input->get("distinct")) 
			{
				$this->db->stop_cache();	
				
				$this->db->select_max("ll.id")->group_by("ll.uid")->get();
				$sql = $this->db->last_query();
				
				$this->db->start_cache();
				$this->db->join("({$sql}) tmp", "tmp.id=ll.id", "inner");	
			}
														
			switch ($this->input->get("action"))
			{
				case "查詢":										
					$this->db->select("ll.*, IFNULL(IFNULL(g.name, ll.site), '')  as site_name", false)->stop_cache();

					$total_rows = $this->db->count_all_results();

					$query = $this->db->select("ll.*")
						->limit(100, $this->input->get("record"))
						->order_by("create_time desc")->get();

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("log/login?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;

				case "通路統計":				
					$query = $this->db->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, SUBSTRING(ll.account, INSTR(ll.account, '@'), 20 ) title, count(*) cnt", false)
						->group_by("title, site")
						->order_by("cnt desc, site")->get();					
					break;
					
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, LEFT(ll.create_time, {$len}) title, count(*) cnt", false)
						->group_by("title, site")
						->order_by("title desc, site")->get();
					break;					

				case "廣告統計":				
					$query = $this->db->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, ll.ad title, count(*) cnt", false)
						->where('ad <>', '')
						->group_by("title, site")
						->order_by("cnt desc, site")->get();					
					break;								
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;
		}
			
		$this->g_layout
			->add_breadcrumb("平台登入")	
			->set("query", isset($query) ? $query : false)
			->add_js_include("log/game_login")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
	function game_login()
	{			
		$this->zacl->check_login(true);		
		
		$this->zacl->check("game_login", "read");
		
		$this->_init_log_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->input->get("uid") && $this->db->where("lgl.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("lgl.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("account") && $this->db->like("lgl.account", trim($this->input->get("account")));
			$this->input->get("ip") && $this->db->where("lgl.ip", trim($this->input->get("ip")));
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("gi.id", $this->input->get("server"));
			$this->input->get("ad_channel") && $this->db->where("lgl.ad", $this->input->get("ad_channel"));		
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->db->from("log_game_logins lgl")
				->join("servers gi", "gi.id=lgl.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("lgl.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("lgl.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->db->not_like("lgl.account", "@");
				else $this->db->where("lgl.account like '%@{$channel}'", null, false);
			}
		
			if ($this->input->get("role_exist")) {
				$rule = ($this->input->get("role_exist") == '1' ? 'EXISTS' : 'NOT EXISTS');
				$this->db->where(" {$rule} (select * from characters where uid=lgl.uid and server_id=lgl.server_id)", null, false);
			}											
						
			if ($this->input->get("distinct")) 
			{
				$this->db->stop_cache();	
				
				if ($this->input->get("server") || $this->input->get("display_game") == "server") 
					$this->db->group_by("lgl.uid, lgl.server_id");
				else $this->db->group_by("lgl.uid");
				
				if ($this->input->get("action") == '時段統計') {
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}					
					$this->db->_protect_identifiers = FALSE;
					$this->db->group_by("LEFT(lgl.create_time, {$len})");
					$this->db->_protect_identifiers = TRUE;
				}				
				
				$this->db->select_max("lgl.id")->get();
				$sql = $this->db->last_query();
				
				$this->db->start_cache();
				$this->db->join("({$sql}) tmp", "tmp.id=lgl.id", "inner");	
			}			
					
			if ($this->input->get("action") <> "查詢") {
								
				if ($this->input->get("display_game") == "server") {
					$this->db->select("gi.id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.id";
				}
				else {
					$this->db->select("g.name as name");
					$game_key = "g.game_id";
				}
				$this->db->select("{$game_key} as `key`", false);				
			}			
			
			switch ($this->input->get("action"))
			{
				case "查詢":								
					$this->db->stop_cache();	
							
					$total_rows = $this->db->count_all_results();
										
					$query = $this->db->select("lgl.*, g.abbr as game_name, gi.name as server_name, lgl.uid")
						->limit(100, $this->input->get("record"))
						->order_by("create_time desc")->get();

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("log/game_login?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");
					
					$query = $this->db->select("lgl.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name")->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "會員ID\t會員帳號\tIP位址\t建檔時間\t廣告\t登入遊戲\t\n";
					foreach($query->result() as $row) {						
						$content .= "{$row->uid}\t=\"{$row->account}\"\t{$row->ip}\t{$row->create_time}\t{$row->ad}\t{$row->game_name}_{$row->server_name}\t\n";
					}
					//echo $content;
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();
						
					break;						
					
				case "通路統計":					
					$query = $this->db->select("SUBSTRING(lgl.account, INSTR(lgl.account, '@'), 20 ) title, count(*) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;
					
				case "廣告統計":					
					$query = $this->db->select("lgl.ad title, count(*) cnt", false)
						->where('ad <>', '')
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;					

				case "IP統計":
					if ($this->input->get("distinct")) distinct("LEFT(lgl.create_time, {$len})");					
					$query = $this->db->select("lgl.ip title, count(*) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;		
					
				case "時段統計":							
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}					
					$query = $this->db->select("LEFT(lgl.create_time, {$len}) title, count(*) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("title desc, {$game_key}")->get();
					break;
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;
		}
		
		$games = $this->db->get("games");
		$servers = $this->db->order_by("id")->get("servers");		
			
		$this->g_layout
			->add_breadcrumb("遊戲登入")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			->add_js_include("log/game_login")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
	function online_user()
	{
		$this->zacl->check_login(true);		
		
		$this->zacl->check("online_user", "read");
		
		$this->_init_log_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("gi.id", $this->input->get("server"));		
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->db->from("log_online_users lou")
				->join("servers gi", "gi.id=lou.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("online_date > date_sub(now(), interval 15 minute)", null, false);
			
			switch ($this->input->get("action"))
			{
				case "查詢":								
					$this->db->stop_cache();	
							
					$total_rows = $this->db->count_all_results();
										
					$query = $this->db->select("lou.*, g.abbr as game_name, gi.name as server_name, lou.uid")
						->limit(100, $this->input->get("record"))
						->order_by("online_date desc")->get();

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("log/game_login?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "人數統計":					
					
					if ($this->input->get("display_game") == "server") {
						$this->db->select("gi.id, concat('(', g.abbr, ')', gi.name) as title", false);
						$game_key = "gi.id";
					}
					else {
						$this->db->select("g.name as title");
						$game_key = "g.game_id";
					}					
					
					$query = $this->db->select("count(*) cnt", false)
						->group_by("{$game_key}")
						->order_by("cnt desc")->get();					
					break;		
					
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'display_game' => 'game',
			);
			if ($this->input->get("game_id")) {
				$default_value['display_game'] = 'server';
			} 
			$_GET = $default_value;
		}
		
		$games = $this->db->get("games");
		$servers = $this->db->order_by("id")->get("servers");		
			
		$this->g_layout
			->add_breadcrumb("線上會員")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			//->add_js_include("log/game_login")
			//->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}	
	
	function admin_action()
	{		
		$this->zacl->check_login(true);		
		
		$this->zacl->check("log/admin", "read");
		
		$this->_init_log_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->input->get("ip") && $this->db->where("laa.ip", trim($this->input->get("ip")));
			$this->input->get("desc") && $this->db->like("laa.desc", trim($this->input->get("desc")));
						
			$this->db->from("log_admin_actions laa")
				->join("admin_users au", "laa.admin_uid=au.uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("laa.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("laa.create_time >= {$start_date}", null, false);
			}
			
			switch ($this->input->get("action"))
			{
				case "查詢":	
					$this->db->stop_cache();
					$total_rows = $this->db->count_all_results();

					$query = $this->db
						->limit(100, $this->input->get("record"))
						->order_by("id desc")->get();

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("log/admin_action?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;
		}					
				
		$this->g_layout
			->add_breadcrumb("後台動作")	
			->set("query", isset($query) ? $query : false)
			->add_js_include("log/game_login")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();		
	}
	
	function backup_log_game_logins()
	{				
		set_time_limit(180);		
		
		log_message("error", "backup_log_game_logins: start...");
		echo "backup_log_game_logins: start...<br>";
		
		do {
			$this->db->reconnect();
			
			$row = $this->db->select("id")
				->from("log_game_logins_backup")
				->order_by("id desc")->limit(1)->get()->row();
			
			$now_id = $row->id;
			echo "backup_log_game_logins: backup from {$now_id}<br>";
			
			$this->db->query("insert into log_game_logins_backup
						select * from log_game_logins where id > {$now_id} order by id limit 4000");
					
			$cnt = $this->db->affected_rows();
			
			/*
			if ($cnt > 0) {
				$this->db->query("delete from log_game_logins_offline 
								where create_time <= date_sub(date(now()), interval 1 SECOND) order by id limit 2");
			}
			*/
			
			log_message("error", "backup_log_game_logins: count: {$cnt}");
			echo "backup_log_game_logins: count: {$cnt}<br>";

			sleep(1);
		}
		while ($cnt > 0);
		
		do {
			$this->db->reconnect();			

			$this->db
				->where("create_time <", "2013-11-01")
				->where("is_recent", "0")
				->limit(1500)
				->delete("log_game_logins");		
					
			$cnt = $this->db->affected_rows();
			
			log_message("error", "delete_log_game_logins: count: {$cnt}");
			echo "delete_log_game_logins: count: {$cnt}<br>";

			sleep(1);
		}
		while ($cnt > 0);
		
		log_message("error", "backup_log_game_logins: success.<br>");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */