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
					
			$this->DB2->start_cache();
			
			$this->DB2
				->from("log_logins ll")
				->join("games g", "ll.site=g.game_id", "left")
				->join("users u", "ll.uid=u.uid", "left");
			
			$this->input->get("site") && $this->DB2->where("ll.site", $this->input->get("site"));
			$this->input->get("uid") && $this->DB2->where("ll.uid", $this->input->get("uid"));		
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}			
			$this->input->get("euid") && $this->DB2->where("ll.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("ip") && $this->DB2->where("ll.ip", trim($this->input->get("ip")));
			$this->input->get("ad_channel") && $this->DB2->where("ll.ad", $this->input->get("ad_channel"));											
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("site")) {				 
					$this->zacl->check($this->input->get("site"), "read");
				}
				else $this->DB2->where_in("ll.site", $_SESSION["admin_allow_games"]);
			}						
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ll.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ll.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->DB2->where("u.external_id IS NULL", null, false);
				else $this->DB2->where("u.external_id like '%@{$channel}'", null, false);
			}
			
			if ($this->input->get("distinct")) 
			{
				$this->DB2->stop_cache();	
				
				$this->DB2->select_max("ll.id")->group_by("ll.uid")->get();
				$sql = $this->DB2->last_query();
				
				$this->DB2->start_cache();
				$this->DB2->join("({$sql}) tmp", "tmp.id=ll.id", "inner");	
			}
														
			switch ($this->input->get("action"))
			{
				case "查詢":										
					$this->DB2->select("ll.*, IFNULL(IFNULL(g.name, ll.site), '')  as site_name", false)->stop_cache();

					$total_rows = $this->DB2->count_all_results();

					$query = $this->DB2->select("ll.* ,u.mobile, u.email")
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
					$query = $this->DB2->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, SUBSTRING(u.external_id, INSTR(u.external_id, '@'), 20 ) title, count(*) cnt", false)
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
					$query = $this->DB2->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, LEFT(ll.create_time, {$len}) title, count(*) cnt", false)
						->group_by("title, site")
						->order_by("title desc, site")->get();
					break;					

				case "廣告統計":				
					$query = $this->DB2->select("site as `key`, IFNULL(IFNULL(g.name, ll.site), '')  as name, ll.ad title, count(*) cnt", false)
						->where('ad <>', '')
						->group_by("title, site")
						->order_by("cnt desc, site")->get();					
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
			
		$this->g_layout
			->add_breadcrumb("平台登入")	
			->set("query", isset($query) ? $query : false)
			->set("game_list", $this->DB2->order_by("game_id")->get("games"))
			->add_js_include("log/game_login")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
    function game_events()
    {	
		$this->zacl->check_login(true);		
		
		$this->zacl->check("game_login", "read");
		
		$this->_init_log_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action") && $this->input->get("game_event")) 
		{
			header("Cache-Control: private");	
            $this->load->library(array("Mongo_db"));
            
            $this->mongo_log = new Mongo_db(array("activate" => "default"));

            $game_events = $this->config->item("game_events");
            
            $include = array();
			foreach($game_events[$this->input->get("game_event")]['fields'] as $key => $val) {
                $include[] = $key;
            }
            
			$start_date = ($this->input->get("start_date"))?strtotime($this->input->get("start_date")):0;
			$end_date = ($this->input->get("end_date"))?strtotime($this->input->get("end_date")):time();
            
            $query = $this->mongo_log->where(array("game_id" => $this->input->get("game")))
                ->where_gte('latest_update_time', $start_date)
                ->where_lte('latest_update_time', $end_date)
                ->select($include)->get($this->input->get("game_event"));
					
            $this->load->library('pagination');
            $this->pagination->initialize(array(
                    'base_url'	=> site_url("log/game_events"),
                    'total_rows'=> count($query),
                    'per_page'	=> 100
                ));			
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
		
		$games = $this->DB2->get("games");
		$servers = $this->DB2->order_by("server_id")->get("servers");		
			
		$this->g_layout
			->add_breadcrumb("遊戲事件")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			->add_js_include("log/game_events")
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
					
			$this->DB2->start_cache();
			
			$this->input->get("uid") && $this->DB2->where("lgl.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("lgl.uid", $this->g_user->decode($this->input->get("euid")));		
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}			
			$this->input->get("ip") && $this->DB2->where("lgl.ip", trim($this->input->get("ip")));
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->DB2->where("gi.server_id", $this->input->get("server"));
			$this->input->get("ad_channel") && $this->DB2->where("lgl.ad", $this->input->get("ad_channel"));		
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->DB2->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->DB2->from("log_game_logins lgl")
				->join("users u", "u.uid=lgl.uid", "left")
				->join("servers gi", "gi.server_id=lgl.server_id", "left")
				//->join("characters ch", "ch.server_id=lgl.server_id and ch.uid=lgl.uid", "left")
				->join("games g", "g.game_id=gi.game_id", "left");
			
			if ($this->input->get("show_character")) {
				$this->DB2->join("characters ch", "ch.server_id=lgl.server_id and ch.uid=lgl.uid", "left");
			}
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("lgl.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("lgl.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->DB2->where("u.external_id IS NULL", null, false);
				else $this->DB2->where("u.external_id like '%@{$channel}'", null, false);
			}
		
			if ($this->input->get("character_exist")) {
				$rule = ($this->input->get("character_exist") == '1' ? 'EXISTS' : 'NOT EXISTS');
				$this->DB2->where(" {$rule} (select * from characters where uid=lgl.uid and server_id=lgl.server_id)", null, false);
			}											
						
			if ($this->input->get("distinct")) 
			{
				$this->DB2->stop_cache();	
				
				if ($this->input->get("server") || $this->input->get("display_game") == "server") 
					$this->DB2->group_by("lgl.uid, lgl.server_id");
				else $this->DB2->group_by("lgl.uid");
				
				if ($this->input->get("action") == '時段統計') {
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}					
					$this->DB2->_protect_identifiers = FALSE;
					$this->DB2->group_by("LEFT(lgl.create_time, {$len})");
					$this->DB2->_protect_identifiers = TRUE;
				}				
				
				$this->DB2->select_max("lgl.id")->get();
				$sql = $this->DB2->last_query();
				
				$this->DB2->start_cache();
				$this->DB2->join("({$sql}) tmp", "tmp.id=lgl.id", "inner");	
			}			
					
			if ($this->input->get("action") <> "查詢") {
								
				if ($this->input->get("display_game") == "server") {
					$this->DB2->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.server_id";
				}
				else {
					$this->DB2->select("g.name as name");
					$game_key = "g.game_id";
				}
				$this->DB2->select("{$game_key} as `key`", false);				
			}			
			
			switch ($this->input->get("action"))
			{
				case "查詢":								
					$this->DB2->stop_cache();	
							
					$total_rows = $this->DB2->count_all_results();
					
					$select_str;
					if ($this->input->get("show_character")) {
						$select_str="lgl.*, g.abbr as game_name, gi.name as server_name, lgl.uid, u.mobile, u.email, ch.name character_name";
					} else {
						$select_str="lgl.*, g.abbr as game_name, gi.name as server_name, lgl.uid, u.mobile, u.email";
					}
										
					$query = $this->DB2->select($select_str)
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
					
					$select_str;
					if ($this->input->get("show_character")) {
						$content = "會員ID\t會員手機\t會員信箱\tIP位址\t建檔時間\t廣告\t登入遊戲\t角色\t\n";
						$select_str="lgl.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name, u.mobile, u.email, ch.name character_name";
					} else {
					$content = "會員ID\t會員手機\t會員信箱\tIP位址\t建檔時間\t廣告\t登入遊戲\t\n";
						$select_str="lgl.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name, u.mobile, u.email";
					}
					
					$query = $this->DB2->select($select_str)->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					foreach($query->result() as $row) {		
						if ($this->input->get("show_character")) {				
							$content .= "{$row->uid}\t=\"{$row->mobile}\"\t{$row->email}\"\t{$row->ip}\t{$row->create_time}\t{$row->ad}\t{$row->game_name}_{$row->server_name}\t{$row->character_name}\t\n";
						} else {
							$content .= "{$row->uid}\t=\"{$row->mobile}\"\t{$row->email}\"\t{$row->ip}\t{$row->create_time}\t{$row->ad}\t{$row->game_name}_{$row->server_name}\t\n";
						}
					}
					//echo $content;
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();
						
					break;						
					
				case "通路統計":					
					$query = $this->DB2->select("SUBSTRING(u.external_id, INSTR(u.external_id, '@'), 20 ) title, count(*) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;
					
				case "廣告統計":					
					$query = $this->DB2->select("lgl.ad title, count(*) cnt", false)
						->where('ad <>', '')
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;					

				case "IP統計":
					if ($this->input->get("distinct")) distinct("LEFT(lgl.create_time, {$len})");					
					$query = $this->DB2->select("lgl.ip title, count(*) cnt", false)
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
					$query = $this->DB2->select("LEFT(lgl.create_time, {$len}) title, count(*) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("title desc, {$game_key}")->get();
					break;
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
		
		$games = $this->DB2->get("games");
		$servers = $this->DB2->order_by("server_id")->get("servers");		
			
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
					
			$this->DB2->start_cache();
			
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->DB2->where("gi.server_id", $this->input->get("server"));		
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->DB2->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->DB2->from("log_online_users lou")
				->join("servers gi", "gi.server_id=lou.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("online_date > date_sub(now(), interval 15 minute)", null, false);
			
			switch ($this->input->get("action"))
			{
				case "查詢":								
					$this->DB2->stop_cache();	
							
					$total_rows = $this->DB2->count_all_results();
										
					$query = $this->DB2->select("lou.*, g.abbr as game_name, gi.name as server_name, lou.uid, u.mobile, u.email")
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
						$this->DB2->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as title", false);
						$game_key = "gi.server_id";
					}
					else {
						$this->DB2->select("g.name as title");
						$game_key = "g.game_id";
					}					
					
					$query = $this->DB2->select("count(*) cnt", false)
						->group_by("{$game_key}")
						->order_by("cnt desc")->get();					
					break;		
					
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
		
		$games = $this->DB2->get("games");
		$servers = $this->DB2->order_by("server_id")->get("servers");		
			
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
					
			$this->DB2->start_cache();
			
			$this->input->get("ip") && $this->DB2->where("laa.ip", trim($this->input->get("ip")));
			$this->input->get("desc") && $this->DB2->like("laa.desc", trim($this->input->get("desc")));
						
			$this->DB2->from("log_admin_actions laa")
				->join("admin_users au", "laa.admin_uid=au.uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("laa.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("laa.create_time >= {$start_date}", null, false);
			}
			
			switch ($this->input->get("action"))
			{
				case "查詢":	
					$this->DB2->stop_cache();
					$total_rows = $this->DB2->count_all_results();

					$query = $this->DB2
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
			$this->DB1->reconnect();
			
			$row = $this->DB2->select("id")
				->from("log_game_logins_backup")
				->order_by("id desc")->limit(1)->get()->row();
			
			$now_id = $row->id;
			echo "backup_log_game_logins: backup from {$now_id}<br>";
			
			$this->DB1->query("insert into log_game_logins_backup
						select * from log_game_logins where id > {$now_id} order by id limit 4000");
					
			$cnt = $this->DB1->affected_rows();
			
			/*
			if ($cnt > 0) {
				$this->DB2->query("delete from log_game_logins_offline 
								where create_time <= date_sub(date(now()), interval 1 SECOND) order by id limit 2");
			}
			*/
			
			log_message("error", "backup_log_game_logins: count: {$cnt}");
			echo "backup_log_game_logins: count: {$cnt}<br>";

			sleep(1);
		}
		while ($cnt > 0);
		
		do {
			$this->DB1->reconnect();			

			$this->DB1
				->where("create_time <", "2013-11-01")
				->where("is_recent", "0")
				->limit(1500)
				->delete("log_game_logins");		
					
			$cnt = $this->DB1->affected_rows();
			
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