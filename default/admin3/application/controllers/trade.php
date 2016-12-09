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
			$this->DB1->empty_table("gash_settings");
			foreach($choose as $item) {
				if (empty($item)) continue;
				$arr = explode("|", $item);
				print_r($arr);
				$this->DB1->insert("gash_settings", array(
							"gash_paid" => $arr[0],
							"pepay_prod_id" => $arr[1],
							"pepay_pay_type" => $arr[2],
							"pepay_sub_pay_type" => $arr[3],
							"amount" => $arr[4],
							"close" => $arr[5],
						));
			}	
		}
		
		$query = $this->DB2->get("gash_settings");
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
			"5" => array("name" => "等候入點", "class" => "success"),
		);
		$this->g_layout->set("result_table", $result_table);
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->DB2->start_cache();
			
			$this->input->get("id") && $this->DB2->where("ub.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("ub.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("ub.uid", $this->g_user->decode($this->input->get("euid")));
			$this->input->get("order_no") && $this->DB2->where("ub.order_no", $this->input->get("order_no"));
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->DB2->where("gi.server_id", $this->input->get("server"));
			$this->input->get("result") && $this->DB2->where("ub.result", substr($this->input->get("result"),1));
			$this->input->get("transaction_type") && $this->DB2->where("ub.transaction_type", $this->input->get("transaction_type"));
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->DB2->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->DB2->from("user_billing ub")
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->join("users u", "u.uid=ub.uid", "left");
            
            //$this->DB2->where("ub.transaction_type !=", "vip_billing");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ub.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("ub.uid not in (select uid from testaccounts)")
					->where("(gi.is_test_server is null or gi.is_test_server=0)", null, false);
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("ub.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->select("ub.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name")->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("ub.id desc")->get();					

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
				
					$query = $this->DB2->select("ub.*, gi.name server_name, g.name game_name, g.abbr game_abbr_name")->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "訂單號,uid,euid,轉點管道,金額,遊戲伺服器,結果,訊息,第三方單號,原廠單號,建立日期\n";
					foreach($query->result() as $row) {
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",{$row->transaction_type},{$row->amount},({$row->game_abbr_name}){$row->server_name},{$row->result},".strip_tags($row->note).",\"{$row->order_no}\",{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;		
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
				'test' => 'no',
			);
			$_GET = $default_value;			
		}
		
		$games = $this->DB2->from("games")->get();
		$servers = $this->DB2->from("servers")->order_by("server_id desc")->get();		
			
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
			
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->DB2->where("gi.server_id", $this->input->get("server"));			
			$this->input->get("transaction_type") && $this->DB2->where("ub.transaction_type", $this->input->get("transaction_type"));
			
			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->DB2->where_in("g.game_id", $_SESSION["admin_allow_games"]);
			}
			
			$this->DB2->from("user_billing ub")
					->join("users u", "u.uid=ub.uid", "left")			
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->where("billing_type", "2")
					->where("result", "1")					
					->where("ub.uid not in (select uid from testaccounts)")->where("gi.is_test_server", "0");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ub.create_time >= {$start_date}", null, false);
			}

			if ($this->input->get("start_regdate")) {
				$start_date = $this->DB2->escape($this->input->get("start_regdate"));
				if ($this->input->get("end_regdate")) {
					$end_date = $this->DB2->escape($this->input->get("end_regdate").":59");
					$this->DB2->where("u.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("u.create_time >= {$start_date}", null, false);
			}
			
			if ($channel = $this->input->get("channel")) {
				if ($channel == 'long_e') $this->DB2->not_like("u.external_id", "@");
				else $this->DB2->where("u.external_id like '%@{$channel}'", null, false);
			}
			
			if ($ad_channel = $this->input->get("ad_channel")) {
				$this->DB2->where("EXISTS(select * from characters where uid=ub.uid and server_id=gi.server_id and ad='{$ad_channel}')", null, false);
			}
			
			if ($this->input->get("display_game") == "server") {
				if ($this->input->get("game") == 'dh' && ! $this->input->get("server")) {
					$this->DB2->select("gi.address, gi.address as name", false);
					$game_key = "gi.address";
				}
				else {
					$this->DB2->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.server_id";
				}
			}
			else {
				$this->DB2->select("ub.*, g.name as name");
				$game_key = "g.game_id";
			}
			$this->DB2->select("{$game_key} as `key`");
					
			switch ($this->input->get("action"))
			{							
				case "通路統計":				
					if ($this->input->get("game")) {
						$query = $this->DB2->select("SUBSTRING(u.uid, INSTR(u.uid, '@'), 20 ) title, sum(ub.amount) cnt, count(*) cnt2, COUNT(DISTINCT u.uid) cnt3", false)
							->group_by("title")
							->order_by("cnt desc")->get();
					}
					else {
						$query = $this->DB2->select("SUBSTRING(u.uid, INSTR(u.uid, '@'), 20 ) title, sum(ub.amount) cnt", false)
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
					$query = $this->DB2->select("LEFT(ub.create_time, {$len}) title, sum(ub.amount) cnt", false)
						->group_by("title, {$game_key}")->order_by("title desc")->get();
					break;
					
				case "會員統計":			
					$query = $this->DB2->select("u.uid, sum(ub.amount)  cnt", false)
						->group_by("u.uid, {$game_key}")
						->order_by("u.uid, {$game_key}")->get();					
					break;		

				case "廣告統計":			
					$query = $this->DB2->select("gsr.ad title, sum(ub.amount) cnt", false)
						->join("characters gsr", "gsr.uid=ub.uid and gsr.server_id=gi.server_id and ad<>''")
						->group_by("title, {$game_key}")
						->order_by("cnt desc, {$game_key}")->get();					
					break;		
				
				case "儲值統計":
					$query = $this->DB2->select("sum(ub.amount) cnt, count(*) cnt2, COUNT(DISTINCT ub.uid) cnt3, gi.server_id", false)			
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
		
		$games = $this->DB2->from("games")->get();
		$servers = $this->DB2->from("servers")->order_by("server_id")->get();
			
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
			
			$this->g_wallet->update_order_note($row, $this->input->post("note"), $this->input->get_post("question_id"));
			header("location:".current_url());
		}
		
		$this->_init_trade_layout()
			->add_breadcrumb("編輯訂單")	
			->set("row", $row)
			->render();
	}
	
	function re_complete_order($order_id)
	{
		$this->zacl->check("transfer", "modify");	
        
		$this->load->library("g_wallet");		
		$row = $this->g_wallet->get_order($order_id) or die("訂單不存在");
		$err_message = "";
        
		if ($this->input->post()) 
		{	
			$amount = ($this->input->post("amount")) ? $this->input->post("amount") : $row->amount;
			$order_no = ($this->input->post("order_no")) ? $this->input->post("order_no") : $row->order_no;
            if (!$amount) $err_message = "請準確填寫金額";
            if (!$order_no) $err_message = "請準確填寫訂單號";
            
            if (!$err_message) {
				
				$server_info = $this->DB2->where("server_id", $server_id)->from("server")->get()->row();
				
                $transfer_order = $this->g_wallet->re_complete_order($row, $amount, $order_no);
				
				switch ($row->transaction_type) {
					case "inapp_billing_google":
						// 呼叫遊戲入點機制
						$this->load->library("game_api/{$server_info->game_id}");
						$res = $this->{$server_info->game_id}->iap_transfer($transfer_order, $server_info, "google_play", $product_id, $amount, 'TWD');
						$err_message = $this->{$server_info->game_id}->error_message;
						break;
					case "inapp_billing_ios":
						// 呼叫遊戲入點機制
						$this->load->library("game_api/{$server_info->game_id}");
						$res = $this->{$server_info->game_id}->iap_transfer($transfer_order, $server_info, "app_store", $product_id, $amount, 'TWD');
						$err_message = $this->{$server_info->game_id}->error_message;
						break;
					case "mycard_billing":
						
						break;
				}
                header("location:".current_url());
            }
		}
        
		$this->_init_trade_layout()
			->add_breadcrumb("補送訂單")	
			->set("row", $row)
			->set("err_message", $err_message)
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
					
			$this->DB2->start_cache();
			
			$this->input->get("id") && $this->DB2->where("mb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("mb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("mb.uid", $this->g_user->decode($this->input->get("euid")));			
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			
			$this->input->get("trade_seq") && $this->DB2->where("mb.trade_seq", $this->input->get("trade_seq"));
			$this->input->get("mycard_trade_seq") && $this->DB2->where("mb.mycard_trade_seq", $this->input->get("mycard_trade_seq"));
			$this->input->get("mycard_card_id") && $this->DB2->where("mb.mycard_card_id", $this->input->get("mycard_card_id"));
			$this->input->get("result") && $this->DB2->where("mb.result", $this->input->get("result"));
			
			$this->DB2
				->select("mb.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name, ub.partner_order_id")
				->select("coalesce(trade_code, mycard_trade_seq) as mycard_key", false)
				->from("mycard_billing mb")
				->join("user_billing ub", "ub.mycard_billing_id=mb.id", "left")
				->join("servers gi", "gi.server_id=mb.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("users u", "u.uid=mb.uid", "left");
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("mb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("mb.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("mycard_channel")) {
				if ($this->input->get("mycard_channel") == 'SW') {
					$this->DB2->like("mb.mycard_trade_seq", $this->input->get("mycard_channel"), 'after');	
				} else $this->DB2->like("mb.trade_code", $this->input->get("mycard_channel"), 'after');
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("mb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("mb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("mb.id desc")->get();					

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
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,交易管道,訂單號,Mycard訂單號,卡號,遊戲伺服器,金額,結果,活動代號,訊息,原廠單號,建立日期\n";
					
					foreach($query->result() as $row) {
						
						$trade_channel = '';
						if ( ! empty ($row->payment_type)) {
							switch($row->payment_type) {
								case "INGAME":
									$trade_channel = "實體卡";
									break;
								case "COSTPOINT":
									$trade_channel = "會員扣點";
									break;
								default:
									$trade_channel = "小額付費";
									break;
							}
						}
						$mycard_trade_seq = empty($row->trade_code) ? $row->mycard_trade_seq : $row->trade_code;
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",{$trade_channel},\"{$row->trade_seq}\",{$mycard_trade_seq},{$row->mycard_card_id},{$row->server_name},{$row->amount},".($row->result=='1' ? '成功' : '失敗').",{$row->promo_code},{$row->note},{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;		
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
                
			$this->DB2->start_cache();
            
			$this->DB2
				->select("gb.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name, ub.id ubid, ub.partner_order_id")
				->from("gash_billing gb")
				->join("servers gi", "gi.server_id=gb.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("user_billing ub", "ub.gash_billing_id=gb.id", "left")
				->join("users u", "u.uid=gb.uid", "left");			
			
			$this->input->get("country") && $this->DB2->where("gb.country", $this->input->get("country"));
			$this->input->get("id") && $this->DB2->where("gb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("gb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("gb.uid", $this->g_user->decode($this->input->get("euid")));			
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			if ($status = $this->input->get("PAY_STATUS")) {
				 if ($status == 'S') $this->DB2->where("gb.status", "2");
				 else  $this->DB2->where("gb.status <", "2");				
			}
			$this->input->get("PAID") && $this->DB2->where("gb.PAID", $this->input->get("PAID"));
			$this->input->get("COID") && $this->DB2->where("gb.COID", $this->input->get("COID"));
			$this->input->get("RRN") && $this->DB2->where("gb.RRN", $this->input->get("RRN"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("gb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("gb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("gb.id desc")->get();					

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
					
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,龍邑單號,交易管道,訂單號,GPS訂單號,遊戲伺服器,金額,結果,訊息,原廠單號,建立日期\n";
					
					foreach($query->result() as $row) {
						$trade_channel = $gash_conf["PAID"][$row->PAID]."(".$gash_conf["CUID"][$row->CUID].")";
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",\"{$row->ubid}\",{$trade_channel},\"{$row->COID}\",{$row->RRN},\"({$row->game_abbr_name}){$row->server_name}\",{$row->AMOUNT},".($row->status=='2' ? '成功' : ($row->status=='1' ? '未請款' : '失敗')).",{$row->note},{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;					
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
					
			$this->DB2->start_cache();
			
			$this->DB2
				->select("pb.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name")
				->from("pepay_billing pb")
				->join("servers gi", "gi.server_id=pb.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("users u", "u.uid=pb.uid", "left");			
			
			$this->input->get("id") && $this->DB2->where("pb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("pb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("pb.uid", $this->g_user->decode($this->input->get("euid")));			
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			$this->input->get("ORDER_ID") && $this->DB2->where("pb.ORDER_ID", $this->input->get("ORDER_ID"));
			$this->input->get("PROD_ID") && $this->DB2->where("pb.PROD_ID", $this->input->get("PROD_ID"));
			$this->input->get("SESS_ID") && $this->DB2->where("pb.SESS_ID", $this->input->get("SESS_ID"));
			
			if ($this->input->get("TRADE_CODE") == 'Y')
				$this->DB2->where("pb.status", '2');
			else if ($this->input->get("TRADE_CODE") == 'N')
				$this->DB2->where("pb.status !=", '2');
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("pb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("pb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("pb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("pb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("pb.id desc")->get();					

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
					
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,交易管道,訂單號,PEPAY交易代碼,金額,結果,訊息,原廠單號,建立日期\n";
					
					foreach($query->result() as $row) {
						$trade_channel = $pepay_conf['Prod_ids'][$row->PROD_ID];
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",{$trade_channel},\"{$row->ORDER_ID}\",\"{$row->SESS_ID}\",\"({$row->game_abbr_name}){$row->server_name}\",{$row->AMOUNT},".($row->status=='2' ? '成功' : '失敗').",{$row->note},{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;						
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
					
			$this->DB2->start_cache();
			
			$this->DB2
				->select("gb.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name")
				->from("user_billing gb")
				->join("servers gi", "gi.server_id=gb.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("users u", "u.uid=gb.uid", "left")
				->where("gb.transaction_type", "inapp_billing_google")
				->where("gb.billing_type", "1");		
			
			$this->input->get("id") && $this->DB2->where("gb.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("gb.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("gb.uid", $this->g_user->decode($this->input->get("euid")));		
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			$this->input->get("order_no") && $this->DB2->where("gb.order_no", $this->input->get("order_no"));
			
			if ($this->input->get("result") == 'Y')
				$this->DB2->where("gb.result", '1');
			else if ($this->input->get("result") == 'N')
				$this->DB2->where("(gb.result is null or gb.result <> '1')", null, false);
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("gb.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("gb.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("gb.id desc")->get();					

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
					
				case "輸出":
					ini_set("memory_limit","2048M");
					
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,訂單號,遊戲伺服器,金額,結果,訊息,原廠單號,建立日期\n";
					
					foreach($query->result() as $row) {
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",\"".ltrim($row->order_no, "GPA.")."\",\"({$row->game_abbr_name}){$row->server_name}\",{$row->amount},".($row->result=='1' ? ($row->is_confirmed=='' ? '未請款' : '成功') : '失敗').",{$row->note},{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;		
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
					
			$this->DB2->start_cache();
			
			$this->DB2
				->select("ib.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name")
				->from("user_billing ib")
				->join("servers gi", "gi.server_id=ib.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("users u", "u.uid=ib.uid", "left")
				->where("ib.transaction_type", "inapp_billing_ios")
				->where("ib.billing_type", "1");
			
			$this->input->get("id") && $this->DB2->where("ib.id", $this->input->get("id"));
			$this->input->get("uid") && $this->DB2->where("ib.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("ib.uid", $this->g_user->decode($this->input->get("euid")));		
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			$this->input->get("order_no") && $this->DB2->where("ib.order_no", $this->input->get("order_no"));
			
			if ($this->input->get("result") == 'Y')
				$this->DB2->where("ib.result", '1');
			else if ($this->input->get("result") == 'N')
				$this->DB2->where("(ib.result is null or ib.result <> '1')", null, false);
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ib.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ib.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("ib.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("ib.uid in (select uid from testaccounts)");
			}
			
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();
					
					$total_rows = $this->DB2->count_all_results();
	
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("ib.id desc")->get();					
					//die($this->DB2->last_query());
					
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
					
				case "輸出":
					ini_set("memory_limit","2048M");
					
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,訂單號,遊戲伺服器,金額,結果,訊息,原廠單號,建立日期\n";
					
					foreach($query->result() as $row) {
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",\"{$row->order_no}\",\"({$row->game_abbr_name}){$row->server_name}\",{$row->amount},".($row->result=='1' ? '成功' : '失敗').",{$row->note},{$row->partner_order_id},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;	
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
    
	function vip()
	{
		$this->zacl->check("vip", "read");	
		
		//$this->load->config("g_gash");
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
                
			$this->DB2->start_cache();
            
			$this->DB2
				->select("vt.*, u.email, u.mobile, u.external_id, gi.name server_name, g.name game_name, g.abbr game_abbr_name, ub.id ubid, ubt.id ubtid")
				->from("vip_tickets vt")
				->join("servers gi", "gi.server_id=vt.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->join("user_billing ub", "ub.vip_ticket_id=vt.id", "left")
				->join("user_billing ubt", "ubt.vip_ticket_id=vt.id and ubt.transaction_type='top_up_account'", "left")
				->join("users u", "u.uid=vt.uid", "left");		
            $this->DB2->where("ub.transaction_type", "vip_billing");
			
			$this->input->get("vip_ticket_id") && $this->DB2->where("vt.id", $this->input->get("vip_ticket_id"));
			$this->input->get("transfer_id") && $this->DB2->where("ubt.id", $this->input->get("transfer_id"));
			$this->input->get("uid") && $this->DB2->where("vt.uid", $this->input->get("uid"));
			$this->input->get("euid") && $this->DB2->where("vt.uid", $this->g_user->decode($this->input->get("euid")));			
			if ($this->input->get("account")) {
				$this->DB2->where("u.email", trim($this->input->get("account")));		
				$this->DB2->or_where("u.mobile", trim($this->input->get("account")));
			}		
			
			if ($status = $this->input->get("status")) {
				 if ($status == 'S') $this->DB2->where("vt.status >=", "2");
				 else  $this->DB2->where("vt.status <", "2");				
			}
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("vt.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("vt.create_time >= {$start_date}", null, false);
			}
			
			$this->input->get("billing_account") && $this->DB2->where("vt.billing_account", $this->input->get("billing_account"));
			$this->input->get("billing_name") && $this->DB2->where("vt.billing_name", $this->input->get("billing_name"));
			if ($this->input->get("billing_start_date")) {
				$billing_start_date = $this->DB2->escape($this->input->get("billing_start_date"));
				if ($this->input->get("billing_end_date")) {
					$billing_end_date = $this->DB2->escape($this->input->get("billing_end_date").":59");
					$this->DB2->where("vt.billing_time between {$billing_start_date} and {$billing_end_date}", null, false);	
				}	
				else $this->DB2->where("vt.billing_time >= {$billing_start_date}", null, false);
			}
            
			if ($this->input->get("test") == 'no') {
				$this->DB2->where("vt.uid not in (select uid from testaccounts)");
			}
			else if ($this->input->get("test") == 'only') {
				$this->DB2->where("vt.uid in (select uid from testaccounts)");
			}
		
			switch ($this->input->get("action"))
			{
				case "查詢": 					
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$query = $this->DB2->limit(100, $this->input->get("record"))->order_by("vt.id desc")->get();					

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
					
					$query = $this->DB2->get();
						
					$filename = "output.csv";					
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					
					$content = "id,uid,euid,信箱,手機,龍邑單號,VIP訂單號,遊戲伺服器,金額,結果,匯款帳戶末5碼,匯款姓名,匯款時間,建立日期\n";
					
					foreach($query->result() as $row) {
						$content .= "{$row->id},{$row->uid},".$this->g_user->encode($row->uid).",\"{$row->email}\",\"{$row->mobile}\",{$row->ubid}\",{$id},\"({$row->game_abbr_name}){$row->server_name}\",{$row->AMOUNT},".($row->status=='2' ? '成功' : ($row->status=='1' ? '未請款' : '失敗')).",{$row->note},".date("Y-m-d H:i", strtotime($row->create_time))."\n";
					}
					echo iconv('utf-8', 'big5//TRANSLIT//IGNORE', $content);
					exit();						
					break;					
			}
			
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
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
			->add_breadcrumb("VIP儲值查詢")
			->set("query", isset($query) ? $query : false)
			->add_js_include("trade/vip")
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
		
			$this->DB2->from("mycard_billing mb")
				->where("result", "1")
				->where("mb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=mb.uid", "left");				
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("mb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("mb.create_time >= {$start_date}", null, false);
			}

			if ($channel = $this->input->get("mycard_channel")) {
				if ($this->input->get("mycard_channel") == 'SW') {
					$this->DB2->like("mb.mycard_trade_seq", $this->input->get("mycard_channel"), 'after');	
				} else $this->DB2->like("mb.trade_code", $this->input->get("mycard_channel"), 'after');
			}
		
			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->DB2->select("payment_type, sum(amount) cnt", false)
						->group_by("payment_type")
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
					$query = $this->DB2->select("LEFT(mb.create_time, {$len}) title, sum(REPLACE(mb.item_code,'long_e','')) cnt", false)
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
		
			$this->DB2->from("gash_billing gb")
				->where("status", "2")
				->where("gb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=gb.uid", "left")
				->join("servers gi", "gi.server_id=gb.server_id", "left");				
									
			$this->input->get("country") && $this->DB2->where("gb.country", $this->input->get("country"));
			$this->input->get("PAID") && $this->DB2->where("gb.PAID", $this->input->get("PAID"));
			$this->input->get("CUID") && $this->DB2->where("gb.CUID", $this->input->get("CUID"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("game")) {
				$this->DB2->where("gi.game_id", $this->input->get("game"));
			}

			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->DB2->select("gb.PAID title, gb.CUID, sum(gb.AMOUNT) cnt", false)
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
					$query = $this->DB2->select("LEFT(gb.create_time, {$len}) title, gb.CUID, sum(gb.AMOUNT) cnt", false)
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
		
		$games = $this->DB2->from("games")->get();
			
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
		
			$this->DB2->from("pepay_billing pb")
				->where("pb.status", "2")
				->where("pb.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=pb.uid", "left")
				->join("servers gi", "gi.server_id=pb.server_id", "left");				
									
			$this->input->get("PROD_ID") && $this->DB2->where("pb.PROD_ID", $this->input->get("PROD_ID"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("pb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("pb.create_time >= {$start_date}", null, false);
			}
					
			if ($this->input->get("game")) {
				$this->DB2->where("gi.game_id", $this->input->get("game"));
			}

			switch ($this->input->get("action"))
			{						
				case "交易管道統計":
									
					$query = $this->DB2->select("pb.PROD_ID title, sum(pb.AMOUNT) cnt", false)
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
					$query = $this->DB2->select("LEFT(pb.create_time, {$len}) title, sum(pb.AMOUNT) cnt", false)
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
		
		$games = $this->DB2->from("games")->get();
			
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
		
		
			$this->DB2->from("user_billing gb")
				->join("users u", "u.uid=gb.uid", "left")
				->join("servers gi", "gb.server_id=gi.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("result", "1")
				->where("gb.uid not in (select uid from testaccounts)")	
				->where("gb.transaction_type", "inapp_billing_google")
				->where("gb.billing_type", "1");			
									
			$this->input->get("order_id") && $this->DB2->where("gb.order_id", $this->input->get("order_id"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("gb.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("gb.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("game")) {
				$this->DB2->where("gi.game_id", $this->input->get("game"));
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
					$query = $this->DB2->select("LEFT(gb.create_time, {$len}) title, g.name, sum(gb.amount) cnt", false)
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
		
		$games = $this->DB2->from("games")->get();
			
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
		
			$this->DB2->from("user_billing ib")
				->join("users u", "u.uid=ib.uid", "left")
				->join("servers gi", "ib.server_id=gi.server_id", "left")
				->join("games g", "g.game_id=gi.game_id", "left")
				->where("result", "1")
				->where("ib.uid not in (select uid from testaccounts)")	
				->where("ib.transaction_type", "inapp_billing_ios")
				->where("ib.billing_type", "1");				
			
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ib.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ib.create_time >= {$start_date}", null, false);
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
					$query = $this->DB2->select("LEFT(ib.create_time, {$len}) title, g.name, sum(ib.amount) cnt", false)
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
	
	function vip_statistics()
	{				
		$this->zacl->check("vip", "statistics");
		
		$this->load->helper("output_table");
		
		$this->_init_trade_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$this->DB2->from("vip_tickets vt")
				->where("status >= ", "2")
				->where("vt.uid not in (select uid from testaccounts)")	
				->join("users u", "u.uid=vt.uid", "left")
				->join("servers gi", "gi.server_id=vt.server_id", "left");				
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("vt.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("vt.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("game")) {
				$this->DB2->where("gi.game_id", $this->input->get("game"));
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
					$query = $this->DB2->select("LEFT(vt.create_time, {$len}) title, sum(vt.cost) cnt", false)
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
		
		$games = $this->DB2->from("games")->get();
			
		$this->g_layout
			->add_breadcrumb("VIP儲值統計")	
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
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
					$billing = $this->DB2->where("trade_seq", $trade_seq)->from("mycard_billing")->get()->row();
					
					if ($billing)
					{
						if (strpos($billing->item_code, "long_e") !== false) {
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
					$billing = $this->DB2->where("id", $billing_id)->from("user_billing")->get()->row();
					
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
			$this->DB2->from("user_billing ub")
					->join("servers gi", "gi.server_id=ub.server_id", "left")
					->join("games g", "g.game_id=gi.game_id", "left")
					->join("users u", "u.uid=ub.uid", "left")
					->where("billing_type", "2")
					->where("result", "1")					
					->where("ub.uid not in (select uid from testaccounts)")->where("gi.is_test_server", "0");
			
			if ($this->zacl->check_acl("partner", "all") == false) {
				$this->DB2->like("u.account", "@{$partner}"); //partner設定
			}
			
			$this->input->get("game") && $this->DB2->where("g.game_id", $this->input->get("game"));
			$this->input->get("server") && $this->DB2->where("gi.server_id", $this->input->get("server"));					
									
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
					$this->DB2->where("ub.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->DB2->where("ub.create_time >= {$start_date}", null, false);
			}
			
			if ($this->input->get("display_game") == "server") {
				if ($this->input->get("game") == 'dh' && ! $this->input->get("server")) {
					$this->DB2->select("gi.address, gi.address as name", false);
					$game_key = "gi.address";
				}
				else {
					$this->DB2->select("gi.server_id, concat('(', g.abbr, ')', gi.name) as name", false);
					$game_key = "gi.server_id";
				}
			}
			else {
				$this->DB2->select("g.name as name");
				$game_key = "g.game_id";
			}
			$this->DB2->select("{$game_key} as `key`");

			if ($this->zacl->check_acl("all_game", "all") == false) {
				if ($this->input->get("game")) {				 
					$this->zacl->check($this->input->get("game"), "read");
				}
				else $this->DB2->where_in("g.game_id", $_SESSION["admin_allow_games"]);
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
					$query = $this->DB2->select("LEFT(ub.create_time, {$len}) title, sum(ub.amount) cnt", false)
						->group_by("title, {$game_key}")
						->order_by("title desc, {$game_key}")->get();
					break;
					
				case "廣告統計":	
					switch ($partner) 
					{
						case 'rc':
							$this->DB2->where("gsr.ad like 'rc%'", null, false);
							break;
														
						default: $this->DB2->where("gsr.ad", $partner);
					}	
					$query = $this->DB2->select("gsr.ad title, sum(ub.amount) cnt", false)
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
		
		$games = $this->DB2->from("games")->get();
		$servers = $this->DB2->from("servers")->order_by("server_id")->get();
			
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