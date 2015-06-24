<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Character extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();	
	}		
	
	function _init_character_layout()
	{
		$this->zacl->check_login(true);
		$this->zacl->check("character", "read");
		
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}			
		return 	$this->_init_layout()->add_breadcrumb("創角資料", "character?game_id={$this->game_id}");	
	}
	
	function index()
	{								
		$this->_init_character_layout();
		$this->load->helper("output_table");
		
		if ($this->input->get("action")) 
		{				
			$this->db->start_cache();
			
			$this->input->get("server") && $this->db->where("ga.server_id", $this->input->get("server")) or $this->db->where("gi.game_id", $this->game_id) ;
			$this->input->get("ad_channel") && $this->db->where("ga.ad", $this->input->get("ad_channel"));			
			$this->input->get("character_name") && $this->db->where("ga.character_name", trim($this->input->get("character_name")));
			$this->input->get("uid") && $this->db->where("ga.uid", trim($this->input->get("uid")));
			$this->input->get("euid") && $this->db->where("ga.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("account") && $this->db->where("ga.account", trim($this->input->get("account")));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ga.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ga.create_time >= {$start_date}", null, false);
			}			

			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->db->not_like("ga.account", "@");
				else $this->db->where("ga.account like '%@{$channel}'", null, false);
			}
		
			$member_type = $this->input->get("member_type");
			if ($member_type == 'distinct') {
				//$where = $server ? " where server_id={$server} " : '';
				//$this->db->join("(select min(id) id, account, server_id from characters {$where} group by account, server_id) tmp", "tmp.account=ga.account and tmp.id=ga.id and tmp.server_id=ga.server_id");
				$this->db->where('(create_status="1" or create_status="2" or create_status="3")', null, false);
			}
			else if ($member_type == 'new_character') {
				//$this->db->join("(select min(gr.id) id, account from characters gr join servers gi on gr.server_id=gi.server_id where gi.game_id='{$this->game_id}' group by user_name) tmp", "tmp.account=ga.account and tmp.id=ga.id");
				$this->db->where('(create_status="2" or create_status="3")', null, false);
			}
			else if ($member_type == 'all_new_character') {
				$this->db->where('create_status', '3');
			}
			
			switch($this->input->get("time_unit")) {
				case 'hour': $len=13; break;
				case 'day': $len=10; break;
				case 'month': $len=7; break;
				case 'year': $len=4; break;
			}
			
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->from("characters ga")
						->join("servers gi", "ga.server_id=gi.server_id")
						->join("users u", "u.account=ga.account");

					$this->db->stop_cache();

					$total_rows = $this->db->count_all_results();
					
					$query = $this->db->limit(10, $this->input->get("record"))
								->order_by("ga.create_time desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("character?".$query_string),
							'total_rows'=> $total_rows,
							'per_page'	=> 10
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");
					
					$query = $this->db->from("characters ga")
						->join("servers gi", "ga.server_id=gi.server_id")
						->join("users u", "u.account=ga.account")->order_by("ga.id")->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "會員帳號 \t會員ID \te-mail \t會員帳號來源 \t註冊日期 \t角色名稱 \t建檔時間\n";
					foreach($query->result() as $row) {
						$regist_date = date("Y-m-d", strtotime($row->create_time));
						
						$channels = $this->config->item('channels');
						$spt = explode("@", $row->account);
						$key= (count($spt)>1) ? $spt[1] : 'long_e';
						
						$content .= "=\"{$row->account}\"\t{$row->uid}\t{$row->email}\t{$channels[$key]}\t{$regist_date}\t{$row->character_name}\t{$row->create_time}\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();
						
					break;					
			
				case "通路統計":			
					$query = $this->db->select("SUBSTRING(ga.account, INSTR(ga.account, '@'), 20 ) channel, count(*) cnt", false)
						->from("characters ga")
						->join("servers gi", "ga.server_id=gi.server_id")
						//->where("ga.account in (select user_name from users where user_name=ga.account)", null, false)
						//->where("exists (select uid from users where user_name=ga.account)", null, false)
						->group_by("channel", false)->order_by("cnt desc")->get();
					break;
					
				case "廣告統計":
					$query = $this->db->select("ga.ad, count(*) cnt", false)
						->from("characters ga")
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("ga.ad", false)->order_by("cnt desc")->get();
					break;					
					
				case "伺服器時段統計":		
					$query = $this->db->select("ga.server_id, gi.name, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->from('characters ga')
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("time, ga.server_id")
						->order_by("time desc, ga.server_id")->get();
					//die($this->db->last_query());
					break;
					
				case "廣告時段統計":	
					$query = $this->db->select("ga.ad, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->from('characters ga')
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("time, ga.ad")
						->order_by("time desc, ga.ad")->get();
					//die($this->db->last_query());
					break;					
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'start_date' => date('Y-m-d')." 00:00",
				'member_type' => 'distinct',
			);
			$_GET = $default_value;	
		}		
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("id desc")->get();	
		
		$this->g_layout
			->set("games", $games)
			->set("servers", $servers)
			->set("query", isset($query) ? $query : false)
			->add_js_include("character/index")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}

	function update($game_id)
	{
		$this->load->library("game_api/{$game_id}");
		$this->$game_id->update_character();
	}	
	
	function import()
	{
		$this->zacl->check("character", "import");
		
		$error_message = '';
		
		if ($this->input->post())
		{			
			header("Cache-Control: no-cache"); 
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('server_id', '伺服器', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$error_message = validation_errors(' ', ' ');
			}
			else
			{
				$this->load->library('upload', array("upload_path" => realpath("p/upload/tmp"), "allowed_types"=>"*"));

				if ( ! $this->upload->do_upload())
				{
					fb($this->upload);
					$error_message = $this->upload->display_errors('','');	
				}
				else
				{
					$file_data = $this->upload->data();	
					$server_id = $this->input->post("server_id");
					$query = $this->db->from("servers gi")->where("id", $server_id)->get();
					if ($query->num_rows() == 0) 
					{
						$error_message = "伺服器不存在";						
					} 
					else 
					{
						$server = $query->row();
						
						$this->load->library("php_excel");
						$fdata = $this->php_excel->load_data($file_data["full_path"]);
						
			    		if ( empty($fdata) ) 
			    		{
			      			$error_message = "檔案無法開啟";
			    		}
			    		else 
			    		{
			      			$success_cnt = 0;
			      			$size = ($file_data["file_size"]*1024)+1;
			      			$field = array('uid', 'euid', 'account', 'character_name', 'ad', 'create_time');
			      					      			
			      			foreach ($fdata as $idx => $row)
			      			{		
			      				if ($idx == 0) { //首行
			      					if ($row !== $field) {
			      						$error_message = '欄位格式錯誤'; 
			      						break;
			      					}
			      					continue;
			      				}
			      				
		      					$d = array();
		      					foreach($field as $i=> $f) $d[$f] = $row[$i];
		      					
		      					if (empty($d["account"])) { // 將uid euid 轉為 user_name
			      					if (empty($d["uid"]) && $d["euid"]) {
			      						$d["uid"] = $this->g_user->decode($d["euid"]);
			      					}
			      					if ($d["uid"]) {
			      						$query = $this->db->from("users")->where("uid", $d["uid"])->get();
			      						if ($query->num_rows() > 0) {
			      							$d["account"] = $query->row()->account;	
			      						}			      						
			      					} 
		      					}
		      					if (empty($d["account"])) {
		      						$error_message .= "#".($idx+1)." user_name未填寫<br>";
		      					}
		      					else 
		      					{				      					
		      						if ( ! $this->g_user->check_account_exist($d["account"])) {
		      							$error_message .= "#".($idx+1)." `{$d["account"]}`帳號不存在<br>";
		      						}
		      						else
		      						{
		      							//創角狀態
										$create_status = 1;
										$cnt = $this->db->from("characters gr")->where("account", $d['account'])->count_all_results();
										
								    	if ($cnt > 0) {
									    	$query = $this->db->from("characters gr")->join("servers gi", "gr.server_id=gi.server_id")
									    					->where("gi.game_id", $server->game_id)->where("account", $d['account'])->get();							
									    	if ($query->num_rows() > 0) {
									    		foreach($query->result() as $row) {
									    			if ($row->server_id == $server->server_id) {
									    				$create_status = 0;
									    				break;
									    			}
									    		}
									    	}
									    	else $create_status = 2;
								    	}
								    	else $create_status = 3;
	
								    	//創角時間
								    	if ($d['create_time']) {
								    		$create_time = $d['create_time'];
								    	}
								    	else {
								    		$create_time = date("Y/m/d H:i:s", time());
								    	}
								    	
										$data = array(
											'account' => $d['account'],
											'character_name' => $d['character_name'],
											'server_id' => $server->server_id,
											'ad' => $d['ad'],
											'create_time' => $create_time,
											'create_status' => $create_status,
										);
										
										//檢查角色是否存在
										$chk_exists = $this->db->from("characters")
											->where("character_name", $d['character_name'])
											->where("server_id", $server->server_id)->count_all_results() > 0 ? true : false;
										
										if ($chk_exists) {
											$error_message .= "#".($idx+1)." 已存在<br>";
										}	
										else {
											if ( ! $this->db->insert("characters", $data)) { 
												$error_message .= "#".($idx+1)." 資料庫新增錯誤<br>";
											}
											else {
												$success_cnt++;
											}											
										}
		      						}
		      					}	  		
			      			}
			      			$error_message = "總共匯入 {$success_cnt} 筆<br>" . $error_message;
			    		}	
					}
				}	
			}
		}
		
		$server = $this->db->select("gi.name as server_name, gi.server_id")
					->from("games g")
					->join("servers gi", "g.game_id=gi.game_id")
					->where("g.game_id", $this->game_id)
					->get();
		
		$this->_init_character_layout()
			->add_breadcrumb("匯入")
			->set("server", $server)
			->set("error_message", $error_message)		
			->render();		
	}
		
	/**
	  * fgetcsv
	  *
	  * 修正原生fgetcsv讀取中文函式
	  *
	  * @param CSV文件檔案
	  * @param length 每一行所讀取的最大資料長度
	  * @param d 資料分隔符號(預設為逗號)
	  * @param e 字串包含符號(預設為雙引號)
	  * @return $_csv_data
	  */
	function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"') 
	{
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		$eof=false;
		while ($eof != true) {
			$_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
			if ($itemcnt % 2 == 0){
				$eof = true;
			}
		}
	 
		$_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
	 
		$_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
	 
		for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
			$_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);
			$_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
		}
	 
		return empty ($_line) ? false : $_csv_data;
	}	
	
	function xf_ad()
	{
		$this->game_id = 'xf';
		
		if ($this->input->post()) 
		{
			$ad_channel = $this->input->post("ad_channel");
			$startDate = strtotime($this->input->post("startDate"));
			$endDate = strtotime($this->input->post("endDate"));
			$url = "http://203.75.245.54:136/interface/index.php/andy/GetCharacters?key=03&startTime={$startDate}&endTime={$endDate}&Advertise={$ad_channel}";
			fb( $url );
			$result =  my_curl($url) ;	
			//call api
		}
		
		$servers = $this->db->where("game_id", $this->game_id)->from("servers")->order_by("name")->get();
		
		$this->_init_layout();
		$this->g_layout
			->add_breadcrumb("創角統計")
			->set("servers", $servers)
			->set("result", isset($result) ? $result : false)
			->add_js_include("character/index")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function partner($partner)
	{						
		$this->zacl->check_login(true);
		$this->_init_layout();
		$this->load->helper("output_table");

		$this->zacl->check("partner", $partner);
		
		if ($this->input->get("action")) 
		{
			$this->db->from('characters ga')
				->join("servers gi", "ga.server_id=gi.server_id")
				->join("games g", "g.game_id=gi.game_id", "left");
						
			if ($this->zacl->check_acl("partner", "all") == false) {
				$this->db->like("ga.account", "@{$partner}", false); //partner設定	
			}			
			
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("ga.server_id", $this->input->get("server"));		
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ga.create_time between {$start_date} and {$end_date}", null, false);	
				}
				else $this->db->where("ga.create_time >= {$start_date}", null, false);
			}
				
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}

			switch($this->input->get("time_unit")) {
				case 'hour': $len=13; break;
				case 'day': $len=10; break;
				case 'month': $len=7; break;
				case 'year': $len=4; break;
			}			
			
			switch ($this->input->get("action"))
			{		
				case "時段統計":
					if ($this->input->get("display_game") == "server") {
						$this->db->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
						$game_key = "gi.server_id";
					}
					else {
						$this->db->select("g.name as name");
						$game_key = "g.game_id";
					}
					$this->db->select("{$game_key} as `key`");
			
					$query = $this->db->select("LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->group_by("time, {$game_key}")
						->order_by("time desc, {$game_key}")->get();
					break;
					
				case "廣告統計":	
					switch ($partner) 
					{
						case 'rc':
							$this->db->where("ga.ad like 'rc%'", null, false);
							break;
														
						default: $this->db->where("ga.ad", $partner);
					}	
					$query = $this->db->select("ga.ad, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->group_by("time, ga.ad")
						->order_by("time desc, ga.ad")->get();
					break;
										
				default: die('err');
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'start_date' => date('Y-m-')."01 00:00",
			);
			$_GET = $default_value;	
		}				
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("id desc")->get();
		
		$this->g_layout
			->add_breadcrumb("創角統計")
			->set("partner", $partner)
			->set("games", $games)
			->set("servers", $servers)
			->set("query", isset($query) ? $query : false)
			->add_js_include("character/partner")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("", "partner");
	}	
	
	function create_character_job()
	{
		if ($this->input->get("start_date")) 
		{
			$query = $this->db->select("min(gl.id) as id")
						->from("log_game_logins gl")
						->join("servers g", "gl.server_id=g.server_id")
						->join("characters gr", "gr.server_id=gl.server_id and gr.uid=gl.uid", "left")
						->where("gl.uid <>", "0")
						->where("gl.create_time >=", $this->input->get("start_date"))
						->where("game_id", $this->game_id)
						->where("gr.id is null", null, false)
						->group_by("gl.uid, gl.server_id")
						->order_by("gl.id")->get();		
		}
		else
		{
			$row = $this->db->select("DATE_SUB(create_time, INTERVAL 30 MINUTE) create_time", false)
					->from("characters gr")
					->join("servers g", "gr.server_id=g.server_id")
					->where("game_id", $this->game_id)
					->order_by("create_time", "desc")->limit(1)->get()->row();
			if ($row) {
				$_GET['start_date'] = date("Y-m-d H:i", strtotime($row->create_time));
			}
			else {
				$_GET['start_date'] = date('Y-m-d')." 00:00";
			}
		}
		
		$this->_init_character_layout();
				
		$this->g_layout
			->add_breadcrumb("更新")
			->set("query", isset($query) ? $query : false)
			->add_js_include("character/create_character_job")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
	function kunlun_create_character_job()
	{
		if ($this->input->get("start_date")) 
		{
			$query = $this->db->select("min(gl.id) as id")
						->from("log_game_logins gl")
						->join("servers g", "gl.server_id=g.server_id")
						->join("characters gr", "gr.server_id=gl.server_id and gr.uid=gl.uid", "left")
						->where("gl.uid <>", "0")
						->where("gl.create_time >=", $this->input->get("start_date"))
						->where("game_id", $this->game_id)
						->where("gr.id is null", null, false)
						->group_by("gl.uid, gl.server_id")
						->order_by("gl.id")->get();		
		}
		else
		{
			$row = $this->db->select("DATE_SUB(create_time, INTERVAL 30 MINUTE) create_time", false)
					->from("characters gr")
					->join("servers g", "gr.server_id=g.server_id")
					->where("game_id", $this->game_id)
					->order_by("create_time", "desc")->limit(1)->get()->row();
			if ($row) {
				$_GET['start_date'] = date("Y-m-d H:i", strtotime($row->create_time));
			}
			else {
				$_GET['start_date'] = date('Y-m-d')." 00:00";
			}
		}
		
		$this->_init_character_layout();
				
		$this->g_layout
			->add_breadcrumb("更新")
			->set("query", isset($query) ? $query : false)
			->add_js_include("character/kunlun_create_character_job")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
	function run_job($log_id)
	{
		error_reporting(E_ALL);
		ini_set('display_errors','On');

		$row = $this->db->select("gl.uid, gl.account, gl.ad, game_id, gl.server_id, gl.create_time, g.id as sid, g.address, g.merge_address, g.server_id")
					->from("log_game_logins gl")
					->join("servers g", "gl.server_id=g.server_id")
					->join("characters gr", "gr.server_id=gl.server_id and gr.uid=gl.uid", "left")
					//->where_in("game_id", array("xj", "sg2", "xl", "gt", "jh", "eb", "dxc", "bt", "xg", "sw", "qjp2", "ly", "sj","dp", "st", "fs", "mq"))
					->where("gl.uid <>", "0")
					->where("gl.id", $log_id)
					->where("gr.id is null", null, false)
					->get()->row();

		if ($row) {
			$server = array("id"=>$row->sid, "game_id"=>$row->game_id, "address"=>$row->address, "merge_address"=>$row->merge_address, "server_id"=>$row->server_id);
			$user = array("uid"=>$row->uid, "account"=>$row->account);
					
			$re_arr = $this->_check_character_status($server, $user, $row->game_id);			
			if ($re_arr['result'] == '1') {
				$this->load->model("g_characters");
				$data = array(
					"uid" => $row->uid,
					'account' => $row->account,
					'create_time' => $row->create_time,
					'ad' => $row->ad,
				);
				if ( ! empty($re_arr["character_name"])) $data["character_name"] = $re_arr["character_name"];
				$this->g_characters->create_character($server, $data);
				
				echo 'insert:'. $this->db->insert_id();
			}
			print_r($re_arr);
		}
		else echo '--';
	}
	
	//遊戲判斷有無角色
    function _check_character_status($server, $user, $game)
    {   
		if (is_array($server)) $server = (object)$server; 
		if (is_array($user)) $user = (object)$user;
		    
    	switch ($game) 
    	{
    		case 'fen':
    			$key = '88box@##*)32!^*"$long_e';
    			$url = 'http://www.88box.com/co/check_role/game/ft';    			
    			$get = array(
    					'partner' => 'long_e',
    					'user_id' => $this->g_user->encode($user->uid),
    					'server_id' => $server->address,
    					'time' => time(),
    			);
    			$get['sign'] = md5($get['partner'].$get['user_id'].$get['server_id'].$get['time'].$key);
    			 
    			$re = my_curl($url.'?'.http_build_query($get));
    			$arr = explode("|", $re);    			
    			if (empty($arr)) return '-1';
    			else if ($arr[0] == '0') return array("result"=>"1", "character_name"=>$arr[1]);
    			else {
    				return array("result"=>"-1");
    			}    			    			
    			break;
    		
    		case 'hg':
    	    	$get = array(
		    		'uid' => $this->g_user->encode($user->uid),
		    		'sid' => $server->address,	
		    	);    
		    	$get['sign'] = md5($get['uid'].$get['sid'].'805_IP8i-MKqhuxiaoIGgW6-cnolanCA');		    	
		    	$api_url = "http://s".$server->address."-hg.yeapgame.com/active?".http_build_query($get);		    	
		    	$re = my_curl($api_url);
		   		if ($re === '0') return array("result"=>"1");	   				
		   		else {   			
		   			 return array("result"=>"-1");
		   		}	
		   		break;	
    		
    		case 'zj':
    			
    				$url = "http://203.75.245.81:3000/sg_user?serv_id={$server->address}&acc_id=".$this->g_user->encode($user->uid);
					$re = my_curl($url);
					$json = json_decode($re);
					if ($json->status === 0) {
						return array("result"=>"1", "character_name"=>$json->player->nick);	
					}
					else {
						return array("result"=>"-1");
					}
			
					/*
    			header('Content-Type: text/html; charset=GBK');
    	    	$api_url = "http://game.zj.longeplay.com.tw:1080/getnick.php";
		    	$get = array(
		    		'euid_f' => $this->g_user->encode($user->uid),		 
		    		'servid' => $server->address,    		
		    	);    
		    	$re = my_curl($api_url.'?'.http_build_query($get));
		    			    	
		    	
		   		if (empty($re)) return array("result"=>"-1");
		   		else {
					return array("result"=>"1", "character_name"=>iconv("GBK", "UTF-8", $re));	   						
		   		}	
		   		*/		
    			break;
    			
    		case 'ly':
    	    	$api_url = "http://{$server->address}.ly.longeplay.com.tw/getCharacterInfo.php";
		    	$get = array(
		    		'id' => $this->g_user->encode($user->uid),		 
		    		'time' => time(),    		
		    	);    
		    	$get['sign'] = md5("{$get['id']}.{$get['time']}.QxWbedfQxHzzfZpyMjJE7kmsAx9zDN@S");		    	
		    	$re = my_curl($api_url.'?'.http_build_query($get));

		   		if ($re == 'not found') return array("result"=>"-1");
		   		else {
					$json = json_decode($re);
					if ($json) {
						return array("result"=>"1", "character_name"=>$json[0]->name);	
					}   						
		   		}
		   		return array("result"=>"-1");
		   		break;		   		
    		
    		case 'sj':
    			$key = 'long_e34ba78238579876ee1bb6fc9up9y';
				$data = array(    			
		    		"company" => "long_e",
		    		"tstamp" => time(),
		    	);    	
		    	$data["ticket"] = md5($data["company"].$data["tstamp"].$key);
		    	$url = "http://www.muxplay.com/union/auth?".http_build_query($data);    	
		   		$re = my_curl($url);
		   		
		   		$auth_key = "";
		   		if ($json = json_decode($re)) {
		   			if ($json->status == "4") {
		   				$auth_key = $json->statusTxt;
		   			}
		   		}   	
    			
    	   		$data = array(    			
		    		"company" => "long_e",
		   			"account" => $this->g_user->encode($user->uid),
		    		"user_tstamp" => time(),
		   			"game_id" => '33',
		   			"server_id" => $server->address,
		    	);  
		   		$data["user_ticket"] = md5($data["company"].$data["account"].$data["user_tstamp"].$data["game_id"].$data["server_id"].$key.$auth_key);
		
		    	$check_user_url = 'http://www.muxplay.com/union/character?'.http_build_query($data);
		    	
    			$maximumLoopNum = 3;    	
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($check_user_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }      
		
		   		if ($re) {   			
			   		$json = json_decode($re);
			   		if ($json->status == "4") {
			   			return array("result"=>"1", "character_name"=>$json->character[0]->characterName);	
			   		}
		   		}	
		   		
		   		return array("result"=>"-1");
   		    			
				break;      		

    		case 'st': case 'qq': case 'jj': case 'cy':
    			$arr = array(
    				"st" => array(
	    				"game" => 'ST',
	    				"key" => '2eb6956719c805e12d72637218a11c30',
    				),
    				"qq" => array(
	    				"game" => 'QQ',
	    				"key" => '911d4d67911e7996a69cb1f84e8e8708',
    				),
    				"jj" => array(
	    				"game" => 'JJ',
	    				"key" => '4be569973baba234215c95864dc31013',
    				),
    				'cy' => array( //gamexdd
    					'game' => 'CY',
    					'key' => 'd199658869bba46862804a12c9d857fd',
    				),
    			);
    			
    	    	$api_url = 'http://www.gamexdd.com/partner/role/';
    	
		    	$get = array(
		    		'partner' => 'COOZ',
		    		'game' => $arr[$game]['game'],
		    		'uid' => $this->g_user->encode($user->uid),
		    		'sid' => $server->address,	
		    		'time' => time(),
		    	);    
		    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].$arr[$game]['key']);	
		    	
		    	$check_user_url = $api_url.'?'.http_build_query($get);
		
		    	$maximumLoopNum = 3;    	
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($check_user_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }   
    	
		        $json = json_decode($re);
		        
		   		if (!empty($json->roleName)) {
		   			return array("result"=>"1", "character_name"=>$json->roleName);	
		   		}
		   		else if ($re == '') return array("result"=>"-1");
		   		else {   			
		   			return array("result"=>"-1");
		   		}   		    	
		   			
				break; 				
				
    		case 'xg':
    	    	$api_url = 'http://www.179game.com/partner/checkrole/';
    	
		    	$get = array(
		    		'partner' => 'COOZ',
		    		'game' => 'RR',
		    		'uid' => $this->g_user->encode($user->uid),
		    		'sid' => $server->address,	
		    		'time' => time(),
		    	);    
		    	$get['verify'] = md5($get['partner'].$get['game'].$get['uid'].$get['sid'].$get['time'].'ee5a1f09e64ca3bf8b8276f0d2eb7b0f');	
		    	
		    	$check_user_url = $api_url.'?'.http_build_query($get);
		
		    	$maximumLoopNum = 3;    	
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($check_user_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }   
    	
		   		if ($re == '1') return array("result"=>"1");
		   		else if ($re == '') return array("result"=>"-1");
		   		else {   			
		   			return array("result"=>"-1");
		   		}   		    	
		   			
				break;    		
    		
    		case 'bt':
    	    	$id = $this->g_user->encode($user->uid);
		    	$check_user_url = sprintf("http://p.337.com/site/api.php?method=game.getUserInfo&gKey=%s&uid=long_e_%d", $server->address, $id);
		    	
		    	$maximumLoopNum = 3;    	
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($check_user_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }        
		
		       	if (empty($re)) {
		    		return array("result"=>"-1");
		    	}
		    	else { 
		    		$json_result = json_decode($re, TRUE);
		    		
		    		if (empty($json_result)) return array("result"=>"-1");
		    		
	    			if ( ! empty($json_result['character_name']) || ! empty($json_result[0]['character_name'])) {
	    				$character_name = empty($json_result['character_name']) ? $json_result[0]['character_name'] : $json_result['character_name'];
		    			return array("result"=>"1", "character_name"=>$character_name);
		    		}
		    		else {
		    			return array("result"=>"-1");	    				
			    	}
				}
				break;
		        
    		
    		case 'dxc': case 'sw':    			
    			if ($game == 'dxc') {
    	    		$api_url = 'http://www.870.com/api/user/openid/long_e/role.php';
    			} else if ($game == 'sw') {
    				$api_url = 'http://www.870.com/api/user/openid/long_e/role_swjt.php';
    			}
    	
		    	$get = array(
		    		'userid' => $this->g_user->encode($user->uid),
		    		'server_id' => $server->address,
		    		'time' => time(),
		    	);    	
		    	$get['signmsg'] = md5($get['userid'].$get['server_id'].$get['time']);
		    	
		    	$re = my_curl($api_url.'?'.http_build_query($get));
		    	
		    	if (empty($re)) {
		    		return array("result"=>"-1");
		    	}
		    	else if ($re == '-1') {
		    		return array("result"=>"-1");
		    	}
		    	else {
		    		 
		    		$data = json_decode($re);
		    		if (empty($data)) return array("result"=>"-1");
		    			
		    		if (strval($data->role) == '-1') {
		    			return array("result"=>"-1");
		    		}
		    		else {		    			
		    			return array("result"=>"1", "character_name"=>$data->role[0]->name);
		    		}
				}				
    			break;
    			    		
    		case 'jh':	case 'dp': case 'mq': case 'aj': case 'dd':
    			if ($game == 'jh') $api_url = 'http://www.tt-play.com/loginApi/checkrolejh/';
    			elseif ($game == 'dp') $api_url = 'http://www.tt-play.com/loginApi/checkroledps/';
    			elseif ($game == 'mq') $api_url = 'http://www.tt-play.com/loginApi/checkroleDetailQQ/';
    			elseif ($game == 'aj') $api_url = 'http://www.tt-play.com/loginApi/checkroleAJ2/';
    			elseif ($game == 'dd') $api_url = 'http://www.tt-play.com/loginApi/checkroleDD/';
    	
		    	$get = array(
		    		'account' => $this->g_user->encode($user->uid),
		    		'server' => $server->address,
		    		'from' => 'long_e',
		    		'src' => 'long_e',	
		    	);    	
		    	$re = my_curl($api_url.'?'.http_build_query($get));		    	
		    	
		    	if (empty($re)) {
		    		return array("result"=>"-1");
		    	}
		    	else { 
		    		$data = json_decode($re);
		    		
		    		if ($game == 'jh') {
			    		if ($data->result == '1') {
			    			return array("result"=>"1", "character_name"=>$data->charInfo[0]->cname);
			    		}
			    		else {
			    			return array("result"=>"-1");
			    		}
		    		}
		    		else if ($game == 'aj') {
			    		if ($data->result == '1') {
			    			return array("result"=>"1", "character_name"=>urldecode($data->roleinfo[0]->name));
			    		}
			    		else {
			    			return array("result"=>"-1");
			    		}
		    		}
		    	    else if ($game == 'mq') {    		
				        if ( ! empty($data->roleName)) {
				        	return array("result"=>"1", "character_name"=>$data->roleName);
				        }
				    	else {
				    		return array("result"=>"-1");
				    	}		
				    }
				    else if ($game == 'dd') {
				    	
				  	    if ($data->status == 'ok' && ! empty($data->roles)) {
	    					return array("result"=>"1", "character_name"=>urldecode($data->roles[0]->name));
			    		}
			    		else {
			    			return array("result"=>"-1");
			    		}
				    }		    		
		    		else {
		    			if ($data->status == '1') {
				    		foreach ($data->data as $property=>$value)
							{
							   $character_name = $value[0]->name;
							}
			    			return array("result"=>"1", "character_name"=>$character_name);
			    		}
			    		else {
			    			return array("result"=>"-1");
			    		}
		    		}
				}    			
    			break;
    			
    		case 'xl':		    	    			    	    	
		    	$no = (int)strtr($server->server_id, array("xl_"=>""));
		    	//$id = $user->uid;
		    	$id = $this->g_user->encode($user->uid);
		    	$check_user_url = sprintf('http://p.337.com/site/api.php?method=game.getUserInfo&gKey=xlfc@long_e_tw_%d&uid=long_e_%d', $no, $id);
		    	
		    	$maximumLoopNum = 3;
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($check_user_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }        
		
		        if (empty($re)) {
		    		return array("result"=>"-1");
		    	}
		    	else {
		    		$json_result = json_decode($re, TRUE);
		    		if (empty($json_result)) {
		    			return array("result"=>"-1");
		    		}
		    		else {
			    		if ( ! empty($json_result[0]['character_name'])) {
			    			return array("result"=>"1");
			    		}
			    		else {
		    				return array("result"=>"-1");
				    	}
		    		}
		    	}    			
    			break;
    			
    		default: 
		    	$key = array( //transfer_key
		    		'xj' => 'LMIoMmgXUfePZpjyTFvDXBjKkvuwHCkU',
		    		'sg2' => 'daGYtUEtDdPTljdbqVgpdeHcNhjlGcNg',
		    		'gt' => 'tWAztbLgIJtaZDCIQUwQlBBOnohTwhov',
		    		'qjp2' => '6c1e4f2167a3bea23bd023012003370e',
		    		'fs' => 'jbznpibeerojstjzxzthsmniweuaheig',
		    		'lz' => 'hojhbyxlwuwkbllkmfbthbugkaovwvku',
		    	);
		    	if ( ! array_key_exists($game, $key)) return array("result"=>"-1");
		    	
		    	if (in_array($server->game_id, array('qjp2', 'fs', 'lz'))) {
		    		$api_url = 'http://user.unite.kimi.com.tw/Char/getCharInfo';
		    	}
		    	else {
		    		$api_url = 'http://user.qjp.longeplay.com.tw/Char/getCharInfo';
		    	}
		    	
		    	if (is_array($server)) $server = (object)$server; 
		    	if (is_array($user)) $user = (object)$user;    	
		    					
		    	$sid = empty($server->merge_address) ? $server->address : $server->merge_address;
		    	$key = $key[$server->game_id];
		    	$uname 	= urlencode($user->account);
		    	$lgtime = date("YmdHis",time());
		    	$sign = md5("uid={$user->uid}&uname={$uname}&serverid={$sid}&type=long_e&key={$key}");
		    	
		    	$connect_url = "{$api_url}?uid={$user->uid}&uname={$uname}&serverid={$sid}&type=long_e&sign={$sign}";
		    	//fb($connect_url);
		  	    	
				$maximumLoopNum = 3;
		        while ($maximumLoopNum-- > 0) { 
					$re = my_curl($connect_url);	
		    		if (empty($re)) sleep(1); //對方無反應，隔1秒再試
		    		else break;
		        }        
		        
		        if (empty($re)) {
		    		return array("result"=>"-1");
		    	}
		    	else {
		    		$json_result = json_decode($re, TRUE);
		    		if (empty($json_result)) {
		    			return array("result"=>"-1");
		    		}
		    		else {
			    		if ($json_result['status'] == '600') {
			    			return array("result"=>"1");
			    		}
			    		else {
			    			return array("result"=>$json_result['status']);
				    	}
		    		}
		    	}
    	}
    }    
        
    function auto_check_character_job()
    {        			    	
    	error_reporting(E_ALL);
		ini_set('display_errors','On');		
		ini_set('max_execution_time', 600);

		$row = $this->db->select("DATE_SUB(create_time, INTERVAL 30 MINUTE) create_time", false)
				->from("characters gr")
				->join("servers g", "gr.server_id=g.server_id")
				->where("game_id", $this->game_id)
				->order_by("create_time", "desc")->limit(1)->get()->row();
		
		if ($row) {
			$start_date = date("Y-m-d H:i", strtotime($row->create_time));
		}
		else {
			$start_date = date('Y-m-d')." 00:00";
		}
			    	
    	$query = $this->db->select("min(gl.id) as id")
					->from("log_game_logins gl")
					->join("servers g", "gl.server_id=g.server_id")
					->join("characters gr", "gr.server_id=gl.server_id and gr.uid=gl.uid", "left")
					->where("gl.uid <>", "0")
					->where("gl.create_time >=", $start_date)
					->where("game_id", $this->game_id)
					->where("gr.id is null", null, false)
					->group_by("gl.uid, gl.server_id")
					->order_by("gl.id")->get();
    	   	
    	$i = 0;
    	foreach($query->result() as $row) {
    		$i++;
    		echo $i."<br>";
    		$url = site_url("character/run_job/{$row->id}");
			echo $url . "<br>";

			$this->run_job($row->id);		
			echo "<br>";	
			usleep(150000);
			//if ($i == 1000) break;
    	}
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */