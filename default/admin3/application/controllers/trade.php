<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends MY_Controller {

	function __construct() 
	{
		parent::__construct();
		$this->zacl->check_login(true);			
	}		
	
	function _init_trade_layout()
	{
		return $this->_init_layout()->add_breadcrumb("交易", "trade");	
	}
	
	function index()
	{
		$this->_init_trade_layout()->render();
	}
	
	function setup()
	{
		if ($this->input->post()) {						
			$choose = $this->input->post("choose");
			print_r($choose);
			$this->db->empty_table("gash_settings");
			foreach($choose as $item) {
				if (empty($item)) continue;
				$arr = explode("|", $item);
				print_r($arr);
				$this->db->insert("gash_settings", array(
							"gash_paid" => $arr[0],
							"pepay_prod_id" => $arr[1],
							"pepay_pay_type" => $arr[2],
							"pepay_sub_pay_type" => $arr[3],
							"amount" => $arr[4],
							"close" => $arr[5],
						));
			}	
		}
		
		$query = $this->db->get("gash_settings");
		$pepay_table = array();
		foreach($query->result() as $row) {
			$pepay_table[] = "{$row->gash_paid}|{$row->pepay_prod_id}|{$row->pepay_pay_type}|{$row->pepay_sub_pay_type}|{$row->amount}|{$row->close}";
		}
		
		$this->_init_trade_layout()
			->add_breadcrumb("交易設定")	
			->set("pepay_table", $pepay_table)
			->render();
	}
	
	function setup_ajax()
	{
		$amount = $this->input->post("amount");
		
		
	}
	
	function transfer()
	{	
		$this->_init_trade_layout();	
		$this->zacl->check("transfer", "read");
			
		$result_table = array(
			"0" => array("name" => "初始", "class" => "error"), 
			"1" => array("name" => "成功", "class" => "success"), 
			"2" => array("name" => "失敗", "class" => "warning"),
			"3" => array("name" => "交易逾時", "class" => "error"),
			"4" => array("name" => "其它", "class" => "info"),
		);
		$this->g_layout->set("result_table", $result_table);
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->input->get("id") && $this->db->where("ub.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("ub.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("ub.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("order_no") && $this->db->where("ub.order_no", $this->input->get("order"));
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("gi.server_id", $this->input->get("server"));
			$this->input->get("result") && $this->db->where("ub.result", substr($this->input->get("result"),1));
			$this->input->get("transaction_type") && $this->db->where("ub.transaction_type", $this->input->get("transaction_type"));
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->db->from("user_billing ub")
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->join("users u", "u.uid=ub.uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ub.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("ub.uid not in (select uid from testaccounts)")
					->where("(gi.is_test_server is null or gi.is_test_server=0)", null, false);
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("ub.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->select("ub.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name")->stop_cache();

					$total_rows = $this->db->count_all_results();
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("ub.id desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/transfer?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");
				
					$query = $this->db->select("ub.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name")->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "訂單號 \tuid \teuid \t轉點管道 \t金額 \t遊戲伺服器 \t結果 \t訊息 \t第三方訂單號 \t建立日期\n";
					foreach($query->result() as $row) {
						$content .= "{$row->id}\t{$row->uid}\t".$this->g_user->encode($row->uid)."\t{$row->transaction_type}\t{$row->amount}\t({$row->game_abbr_name}){$row->server_name}\t{$row->result}\t{".strip_tags($row->note)."\t=\"{$row->order}\"\t".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;		
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'test' => 'no',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("server_id desc")->get();		
			
		$this->g_layout
			->add_breadcrumb("轉點查詢")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function transfer_statistics()
	{	
		$this->_init_trade_layout();
		$this->load->helper("output_table");
					
		$this->zacl->check("transfer", "statistics");
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
			
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("gi.server_id", $this->input->get("server"));			
			$this->input->get("transaction_type") && $this->db->where("ub.transaction_type", $this->input->get("transaction_type"));
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->db->from("user_billing ub")
					->join("users u", "u.uid=ub.uid", "left")			
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->where("billing_type", "2")
					->where("result", "1")					
					->where("ub.uid not in (select uid from testaccounts)")->where("gi.is_test_server", "0");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ub.create_time >= {$start_date}", null, false);
			}

			if ($this->input->get("start_regdate")) {
				$start_date = $this->db->escape($this->input->get("start_regdate"));
				if ($this->input->get("end_regdate")) {
					$end_date = $this->db->escape($this->input->get("end_regdate").":59");
					$this->db->where("u.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("u.create_time >= {$start_date}", null, false);
			}
			
			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->db->not_like("u.account", "@");
				else $this->db->where("u.account like '%@{$channel}'", null, false);
			}
			
			if ($ad_channel = $this->input->get("ad_channel")) {
				$this->db->where("EXISTS(select * from characters where uid=ub.uid and server_id=gi.server_id and ad='{$ad_channel}')", null, false);
			}
			
			if ($this->input->get("display_game") == "server") {
				if ($this->input->get("game") == 'dh' && ! $this->input->get("server")) {
					$this->db->select("gi.address, gi.address as name", false);
					$game_key = "gi.address";
				}
				else {
					$this->db->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.server_id";
				}
			}
			else {
				$this->db->select("g.name as name");
				$game_key = "g.game_id";
			}
			$this->db->select("{$game_key} as `key`");
					
			switch ($this->input->get("action"))
			{							
				case "通路統計":				
					if ($this->input->get("game")) {
						$query = $this->db->select("SUBSTRING(u.uid, INSTR(u.uid, '@'), 20 ) title, sum(ub.amount) cnt, count(*) cnt2, COUNT(DISTINCT u.uid) cnt3", false)
							->group_by("title")
							->order_by("cnt desc")->get();
					}
					else {
						$query = $this->db->select("SUBSTRING(u.uid, INSTR(u.uid, '@'), 20 ) title, sum(ub.amount) cnt", false)
							->group_by("title, {$game_key}")
							->order_by("cnt desc, {$game_key}")->get();
					}					
					break;
					
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(ub.create_time, {$len}) title, sum(ub.amount) cnt", false)
						->group_by("title, {$game_key}")->order_by("title desc")->get();
					break;
					
				case "會員統計":			
					$query = $this->db->select("u.uid, sum(ub.amount)  cnt", false)
						->group_by("u.uid, {$game_key}")
						->order_by("u.uid, {$game_key}")->get();					
					break;		

				case "廣告統計":			
					$query = $this->db->select("gsr.ad title, sum(ub.amount) cnt", false)
						->join("characters gsr", "gsr.uid=ub.uid and gsr.server_id=gi.server_id and ad<>''")
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;		
				
				case "儲值統計":
					$query = $this->db->select("sum(ub.amount) cnt, count(*) cnt2, COUNT(DISTINCT ub.uid) cnt3, gi.server_id", false)			
							->group_by("{$game_key}")
							->order_by("cnt desc")->get();
					break;		
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("server_id")->get();
			
		$this->g_layout
			->add_breadcrumb("轉點統計")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/transfer_statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function modify_order($order_id)
	{
		$this->zacl->check("transfer", "modify");	
		
		$this->load->library("g_wallet");		
		$row = $this->g_wallet->get_order($order_id) or die("訂單不存在");
		
		if ($this->input->post()) 
		{	
			switch ($this->input->post("result"))
			{
				case "1":
					$this->g_wallet->complete_order($row);
					break;
					
				case "2":
					$this->g_wallet->cancel_order($row);
					break;
			} 
			
			$this->g_wallet->update_order_note($row, $this->input->post("note"));
			header("location:".current_url());
		}
		
		$this->_init_trade_layout()
			->add_breadcrumb("編輯訂單")	
			->set("row", $row)
			->render();
	}
	
	function mycard()
	{
		$this->zacl->check("mycard", "read");	
		
		$this->load->config("g_mycard");
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->input->get("id") && $this->db->where("mb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("mb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("mb.uid", $this->g_user->decode($this->input->get("euid")));			
			$this->input->get("account") && $this->db->where("u.account", $this->input->get("account"));
			
			
			$this->input->get("trade_seq") && $this->db->where("mb.trade_seq", $this->input->get("trade_seq"));
			$this->input->get("mycard_trade_seq") && $this->db->where("mb.mycard_trade_seq", $this->input->get("mycard_trade_seq"));
			$this->input->get("mycard_card_id") && $this->db->where("mb.mycard_card_id", $this->input->get("mycard_card_id"));
			$this->input->get("trade_ok") && $this->db->where("mb.trade_ok", substr($this->input->get("trade_ok"),1));
			
			$this->db
				->select("mb.*, u.*")
				->select("coalesce(trade_code, mycard_trade_seq) as mycard_key", false)
				->from("mycard_billing mb")
				->join("users u", "u.uid=mb.uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("mb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("mb.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("mycard_channel")) {
				if ($this->input->get("mycard_channel") == 'SW') {
					$this->db->like("mb.mycard_trade_seq", $this->input->get("mycard_channel"), 'after');	
				} else $this->db->like("mb.trade_code", $this->input->get("mycard_channel"), 'after');
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("mb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("mb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->stop_cache();

					$total_rows = $this->db->count_all_results();
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("mb.id desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/mycard?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");
				
					$mycard_channel = $this->config->item("mycard_channel");					
					$query = $this->db->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id \tuid \teuid \t帳號 \t交易管道 \t訂單號 \tMycard訂單號 \t卡號 \t金額 \t結果 \t訊息 \t建立日期\n";
					
					foreach($query->result() as $row) {
						$trade_channel = '';
						if ( ! empty ($row->mycard_key)) {
							foreach ($mycard_channel as $key => $chnnel) {
								if (strpos($row->mycard_key, $key) === 0) {
									$trade_channel =  $chnnel;
									break;
								}
							}
						}
						$mycard_trade_seq = empty($row->trade_code) ? $row->mycard_trade_seq : $row->trade_code;
						$content .= "{$row->id} \t{$row->uid} \t".$this->g_user->encode($row->uid)." \t=\"{$row->account}\" \t{$trade_channel} \t=\"{$row->trade_seq}\" \t{$mycard_trade_seq} \t{$row->mycard_card_id} \t".strtr($row->product_code, array("long_e"=>""))." \t".($row->trade_ok=='1' ? '成功' : '失敗')." \t{$row->note} \t".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;		
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'test' => 'no',
			);
			$_GET = $default_value;			
		}		
		
		$this->g_layout
			->add_breadcrumb("Mycard儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/payment")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}
	
	function gash()
	{
		$this->zacl->check("gash", "read");	
		
		$this->load->config("g_gash");
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db
				->from("gash_billing gb")
				->join("users u", "u.uid=gb.uid", "left");			
			
			$this->input->get("country") && $this->db->where("gb.country", $this->input->get("country"));
			$this->input->get("id") && $this->db->where("gb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("gb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("gb.uid", $this->g_user->decode($this->input->get("euid")));			
			$this->input->get("account") && $this->db->where("u.account", $this->input->get("account"));
			
			if ($status = $this->input->get("PAY_STATUS")) {
				 if ($status == 'S') $this->db->where("gb.status", "2");
				 else  $this->db->where("gb.status <", "2");				
			}
			$this->input->get("PAID") && $this->db->where("gb.PAID", $this->input->get("PAID"));
			$this->input->get("COID") && $this->db->where("gb.COID", $this->input->get("COID"));
			$this->input->get("RRN") && $this->db->where("gb.RRN", $this->input->get("RRN"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("gb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("gb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->stop_cache();

					$total_rows = $this->db->count_all_results();
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("gb.id desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/gash?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");

					$gash_conf = $this->config->item('gash');
					
					$query = $this->db->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id \tuid \teuid \t帳號 \t交易管道 \t訂單號 \tGPS訂單號 \t金額 \t結果 \t訊息 \t建立日期\n";
					
					foreach($query->result() as $row) {
						$trade_channel = $gash_conf["PAID"][$row->PAID]."(".$gash_conf["CUID"][$row->CUID].")";
						$content .= "{$row->id} \t{$row->uid} \t".$this->g_user->encode($row->uid)." \t=\"{$row->account}\"\t{$trade_channel}\t=\"{$row->COID}\"\t{$row->RRN}\t{$row->AMOUNT}\t".($row->status=='2' ? '成功' : '失敗')."\t{$row->note}\t".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;					
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'test' => 'no',
			);
			$_GET = $default_value;			
		}		
		
		$this->g_layout
			->add_breadcrumb("Gash儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/payment")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}
	
	function pepay()
	{
		$this->zacl->check("pepay", "read");	
		
		$this->load->config("g_pepay");
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db
				->from("pepay_billing pb")
				->join("users u", "u.uid=pb.uid", "left");			
			
			$this->input->get("id") && $this->db->where("pb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("pb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("pb.uid", $this->g_user->decode($this->input->get("euid")));			
			$this->input->get("account") && $this->db->where("u.account", $this->input->get("account"));
			
			$this->input->get("ORDER_ID") && $this->db->where("pb.ORDER_ID", $this->input->get("ORDER_ID"));
			$this->input->get("PROD_ID") && $this->db->where("pb.PROD_ID", $this->input->get("PROD_ID"));
			$this->input->get("SESS_ID") && $this->db->where("pb.SESS_ID", $this->input->get("SESS_ID"));
			
			if ($this->input->get("TRADE_CODE") == 'Y')
				$this->db->where("pb.status", '2');
			else if ($this->input->get("TRADE_CODE") == 'N')
				$this->db->where("pb.status !=", '2');
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("pb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("pb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("pb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("pb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->stop_cache();

					$total_rows = $this->db->count_all_results();
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("pb.id desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/pepay?{$query_string}"),
							'total_rows'=> $total_rows,
							'per_page'	=> 100
						));				
					
					$this->g_layout->set("total_rows", $total_rows);
					break;
					
				case "輸出":
					ini_set("memory_limit","2048M");

					$pepay_conf = $this->config->item('pepay');
					
					$query = $this->db->get();
						
					$filename = "output.xls";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id \tuid \teuid \t帳號 \t交易管道 \t訂單號 \tPEPAY交易代碼 \t金額 \t結果 \t訊息 \t建立日期\n";
					
					foreach($query->result() as $row) {
						$trade_channel = $pepay_conf['Prod_ids'][$row->PROD_ID];
						$content .= "{$row->id}\t{$row->uid}\t".$this->g_user->encode($row->uid)."\t=\"{$row->account}\"\t{$trade_channel}\t=\"{$row->ORDER_ID}\"\t=\"{$row->SESS_ID}\"\t{$row->AMOUNT}\t".($row->status=='2' ? '成功' : '失敗')."\t{$row->note}\t".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;						
			}
			
			$this->db->stop_cache();
			$this->db->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'test' => 'no',
			);
			$_GET = $default_value;			
		}		
		
		$this->g_layout
			->add_breadcrumb("Pepay儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/payment")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}
	
	function google()
	{
		$this->zacl->check("google", "read");	
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db
				->from("google_billing gb")
				->join("users u", "u.uid=gb.uid", "left");			
			
			$this->input->get("id") && $this->db->where("gb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("gb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("gb.uid", $this->g_user->decode($this->input->get("euid")));			
			$this->input->get("account") && $this->db->where("u.account", $this->input->get("account"));
			
			$this->input->get("order_id") && $this->db->where("gb.order_id", $this->input->get("order_id"));
			
			if ($this->input->get("purchase_state") == 'Y')
				$this->db->where("gb.purchase_state", '0');
			else if ($this->input->get("purchase_state") == 'N')
				$this->db->where("(gb.purchase_state is null or gb.purchase_state <> '0')", null, false);
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("gb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("gb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->stop_cache();

					$total_rows = $this->db->count_all_results();
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("gb.id desc")->get();					

					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/google?{$query_string}"),
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
				'test' => 'no',
			);
			$_GET = $default_value;			
		}		
		
		$this->g_layout
			->add_breadcrumb("Google儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/payment")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}	
	
	function ios()
	{
				error_reporting(E_ALL);
		ini_set('display_errors','On');
		
		$this->zacl->check("ios", "read");	
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db
				->from("ios_billing ib")
				->join("users u", "u.uid=ib.uid", "left");			
			
			$this->input->get("id") && $this->db->where("ib.id", $this->input->get("id"));
			$this->input->get("uid") && $this->db->where("ib.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->db->where("ib.uid", $this->g_user->decode($this->input->get("euid")));			
			$this->input->get("account") && $this->db->where("u.account", $this->input->get("account"));
			
			$this->input->get("transaction_id") && $this->db->where("ib.transaction_id", $this->input->get("transaction_id"));
			
			if ($this->input->get("transaction_state") == 'Y')
				$this->db->where("ib.transaction_state", '1');
			else if ($this->input->get("transaction_state") == 'N')
				$this->db->where("(ib.transaction_state is null or ib.transaction_state <> '1')", null, false);
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ib.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ib.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->db->where("ib.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->db->where("ib.uid in (select uid from testaccounts)");
			}
			
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->db->stop_cache();
					
					$total_rows = $this->db->count_all_results();
	
					$query = $this->db->limit(100, $this->input->get("record"))->order_by("ib.id desc")->get();					
					//die($this->db->last_query());
					
					$get = $this->input->get();					
					unset($get["record"]);
					$query_string = http_build_query($get);
					
					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("trade/ios?{$query_string}"),
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
				'test' => 'no',
			);
			$_GET = $default_value;			
		}		
		
		$this->g_layout
			->add_breadcrumb("IOS儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/payment")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}		
	
	function mycard_statistics()
	{				
		$this->zacl->check("mycard", "statistics");
		
		$this->load->config("g_mycard");
		$this->_init_trade_layout();
		$this->load->helper("output_table");
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->db->from("mycard_billing mb")
				->where("trade_ok", "1")
				->where("mb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=mb.uid", "left");				
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("mb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("mb.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("mycard_channel")) {
				if ($this->input->get("mycard_channel") == 'SW') {
					$this->db->like("mb.mycard_trade_seq", $this->input->get("mycard_channel"), 'after');	
				} else $this->db->like("mb.trade_code", $this->input->get("mycard_channel"), 'after');
			}
		
			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->db->select("mid(coalesce(trade_code, mycard_trade_seq),1,3) title, sum(REPLACE(mb.product_code,'long_e','')) cnt", false)
						->group_by("title")
						->order_by("cnt desc")->get();					
					break;
							
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(mb.create_time, {$len}) title, sum(REPLACE(mb.product_code,'long_e','')) cnt", false)
						->group_by("title")
						->order_by("title desc")->get();
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
			
		$this->g_layout
			->add_breadcrumb("儲值統計")	
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function gash_statistics()
	{				
		$this->zacl->check("gash", "statistics");
		
		$this->load->config("g_gash");
		$this->load->helper("output_table");
		
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->db->from("gash_billing gb")
				->where("status", "2")
				->where("gb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=gb.uid", "left")
				->join("servers gi", "gi.server_id=gb.server_id", "left");				
									
			$this->input->get("country") && $this->db->where("gb.country", $this->input->get("country"));
			$this->input->get("PAID") && $this->db->where("gb.PAID", $this->input->get("PAID"));
			$this->input->get("CUID") && $this->db->where("gb.CUID", $this->input->get("CUID"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("game")) {
				$this->db->where("gi.game_id", $this->input->get("game"));
			}

			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->db->select("gb.PAID title, gb.CUID, sum(gb.AMOUNT) cnt", false)
						->group_by("title, gb.CUID")
						->order_by("cnt desc")->get();					
					break;
							
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(gb.create_time, {$len}) title, gb.CUID, sum(gb.AMOUNT) cnt", false)
						->group_by("title, gb.CUID")
						->order_by("title desc")->get();
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
			
		$this->g_layout
			->add_breadcrumb("Gash+儲值統計")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}	
	
	function pepay_statistics()
	{				
		$this->zacl->check("pepay", "statistics");
		
		$this->load->config("g_pepay");
		$this->load->helper("output_table");
		
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->db->from("pepay_billing pb")
				->where("pb.status", "2")
				->where("pb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=pb.uid", "left")
				->join("servers gi", "gi.server_id=pb.server_id", "left");				
									
			$this->input->get("PROD_ID") && $this->db->where("pb.PROD_ID", $this->input->get("PROD_ID"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("pb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("pb.create_time >= {$start_date}", null, false);
			}
					
			if ($this->input->get("game")) {
				$this->db->where("gi.game_id", $this->input->get("game"));
			}

			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->db->select("pb.PROD_ID title, sum(pb.AMOUNT) cnt", false)
						->group_by("title")
						->order_by("cnt desc")->get();					
					break;
							
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(pb.create_time, {$len}) title, sum(pb.AMOUNT) cnt", false)
						->group_by("title")
						->order_by("title desc")->get();
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
			
		$this->g_layout
			->add_breadcrumb("Pepay儲值統計")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}	
	
	function google_statistics()
	{				
		$this->zacl->check("google", "statistics");
		$this->load->helper("output_table");
		
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->db->from("google_billing gb")
				->join("users u", "u.uid=gb.uid", "left")
				->join("servers gi", "gb.server_id=gi.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("purchase_state", "0")
				->where("gb.uid not in (select uid from testaccounts)")	
				;				
									
			$this->input->get("order_id") && $this->db->where("gb.order_id", $this->input->get("order_id"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("game")) {
				$this->db->where("gi.game_id", $this->input->get("game"));
			}

			switch ($this->input->get("action"))
			{				
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(gb.create_time, {$len}) title, g.name, sum(gb.price) cnt", false)
						->group_by("title, g.name")
						->order_by("title desc, g.name")->get();
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
			
		$this->g_layout
			->add_breadcrumb("Google儲值統計")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}		
	
	function ios_statistics()
	{				
		$this->zacl->check("ios", "statistics");
		$this->load->helper("output_table");
		
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->db->from("ios_billing ib")
				->join("users u", "u.uid=ib.uid", "left")
				->join("servers gi", "ib.server_id=gi.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("transaction_state", "1")
				->where("ib.uid not in (select uid from testaccounts)")	
				;													
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ib.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ib.create_time >= {$start_date}", null, false);
			}

			switch ($this->input->get("action"))
			{				
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(ib.create_time, {$len}) title, g.name, sum(ib.price) cnt", false)
						->group_by("title, g.name")
						->order_by("title desc, g.name")->get();
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
			
		$this->g_layout
			->add_breadcrumb("IOS儲值統計")	
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/transfer")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}			
	
	function mycard_api()
	{
		$this->zacl->check("mycard_api", "read");
		
		if ($this->input->get("action"))
		{
			$this->load->library("g_mycard");
							
			$trade_seq = $this->input->get("trade_seq");		
			switch ($this->input->get("action"))
			{
				case "查詢":
					$billing = $this->db->where("trade_seq", $trade_seq)->from("mycard_billing")->get()->row();
					
					if ($billing)
					{
						if (strpos($billing->product_code, "long_e") !== false) {
							$result = $this->g_mycard->query_billing($billing->auth_code);
							$result->type = 'billing'; 
						}								
						else {
							$result = $this->g_mycard->query_ingame($trade_seq);
							$result->type = 'ingame';
						}
					}					
					break;
					
				case "查詢 Billing 付款成功儲值失敗":			
					$result = $this->g_mycard->query_billing_data($this->input->get("start_date"), $this->input->get("end_date"));
					break;
					
			}
		}
		
		$this->_init_trade_layout()
			->add_breadcrumb("Mycard查詢")
			->set("result", isset($result) ? $result : false)
			->add_js_include("trade/mycard")
			->add_js_include("jquery-ui-timepicker-addon")	
			->render();
	}
	
	function omg_api()
	{
		$this->zacl->check("omg_api", "read");
		
		if ($this->input->get("action"))
		{							
			$billing_id = $this->input->get("billing_id");		
			switch ($this->input->get("action"))
			{
				case "查詢":
					$billing = $this->db->where("id", $billing_id)->from("user_billing")->get()->row();
					
					if ($billing)
					{
						$url = 'http://pay.omg.com.tw/Code/API/PaymentInterface/webservice.aspx/getOrderStatusByStore';						
						$store_id = "O2012071801";
						$cashtype = "1";
						$hashkey = '9b77d5e353a9';
						$data = array(
									"storeid" => $store_id,
									"cashtype" => $cashtype,
									"storeorderid" => $billing_id,
									"hash" => md5($store_id."|".$cashtype."|".$billing_id."|".$hashkey)
								);		

						// Initializing curl
						$ch = curl_init($url);
						 
						// Configuring curl options
						$options = array(
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
							CURLOPT_POSTFIELDS => json_encode($data),
						);
						 
						// Setting curl options
						curl_setopt_array( $ch, $options );
						 
						// Getting results
						$result =  json_decode(curl_exec($ch))->d; // Getting jSON result string
					}					
					break;					
			}
		}
		
		$this->_init_trade_layout()
			->add_breadcrumb("OMG查詢")
			->set("result", isset($result) ? $result : false)
			->render();
	}
	
	function test()
	{
		$this->load->library("g_mycard");
		$result = $this->g_mycard->query_billing_data('2013-06-06 00:00:00', '2013-07-01 00:00:00');
		fb($result);
		foreach($result->NewDataSet->Table as $obj) {
			fb($obj);
		}
	}
	
	function test2()
	{
		$this->load->library("g_mycard");
		$result = $this->g_mycard->query_billing('08534B07304CEF9C26B383F033985F89D050D7CF751969442949160C6C5544E2');
		var_dump($result);
	}
	
	function partner($partner)
	{	
		$this->_init_layout();		
		$this->load->helper("output_table");
		
		$this->zacl->check("partner", $partner);
		
		if ($this->input->get("action")) 
		{	
			$this->db->from("user_billing ub")
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->join("users u", "u.uid=ub.uid", "left")
					->where("billing_type", "2")
					->where("result", "1")					
					->where("ub.uid not in (select uid from testaccounts)")->where("gi.is_test_server", "0");
			
			if ($this->zacl->check_acl("partner", "all") == false) {
				$this->db->like("u.account", "@{$partner}"); //partner設定
			}
			
			$this->input->get("game") && $this->db->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->db->where("gi.server_id", $this->input->get("server"));					
									
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ub.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("display_game") == "server") {
				if ($this->input->get("game") == 'dh' && ! $this->input->get("server")) {
					$this->db->select("gi.address, gi.address as name", false);
					$game_key = "gi.address";
				}
				else {
					$this->db->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.server_id";
				}
			}
			else {
				$this->db->select("g.name as name");
				$game_key = "g.game_id";
			}
			$this->db->select("{$game_key} as `key`");

			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->db->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
		
			switch ($this->input->get("action"))
			{										
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(ub.create_time, {$len}) title, sum(ub.amount) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("title desc, {$game_key}")->get();
					break;
					
				case "廣告統計":	
					switch ($partner) 
					{
						case 'rc':
							$this->db->where("gsr.ad like 'rc%'", null, false);
							break;
														
						default: $this->db->where("gsr.ad", $partner);
					}	
					$query = $this->db->select("gsr.ad title, sum(ub.amount) cnt", false)
						->join("characters gsr", "gsr.uid=ub.uid and gsr.server_id=gi.server_id and ad<>''")
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;					
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->db->from("games")->get();
		$servers = $this->db->from("servers")->order_by("server_id")->get();
			
		$this->g_layout
			->add_breadcrumb("轉點統計")
			->set("partner", $partner)	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/transfer_statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("", "partner");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */