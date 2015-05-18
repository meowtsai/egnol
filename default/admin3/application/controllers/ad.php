<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->zacl->check_login(true);		
		
		$this->zacl->check("ad", "read");			
	}	
	
	function index()
	{				
		$this->_init_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");			 
					
			$this->db->start_cache();
			
			$this->db->from("log_logins ll");
						
			$this->input->get("ad_channel") && $this->db->where("ll.ad", $this->input->get("ad_channel"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$this->db->where("ll.create_time between {$start_date} and {$end_date}", null, false);	
				}	
				else $this->db->where("ll.create_time >= {$start_date}", null, false);
			}
			
			switch ($this->input->get("action"))
			{
				case "統計":					
					$this->db->stop_cache();

					$click_cnt = $this->db->count_all_results();
					$user_cnt = $this->db->select("uid")->distinct()->get()->num_rows();
					
					$old_user_cnt = $new_user_cnt = 0;
					if ($game = $this->input->get("game")) {						
						$old_user_cnt = $this->db->select("uid")->distinct()->where("exists (select * from characters gsr join servers gi on gi.server_id=gsr.server_id where uid=ll.uid and gi.game_id='{$game}' and create_time<ll.create_time)", null, false)->get()->num_rows();
						$new_user_cnt = $user_cnt - $old_user_cnt;
					}
					
					$this->g_layout
						->set("click_cnt", $click_cnt)
						->set("user_cnt", $user_cnt)
						->set("old_user_cnt", $old_user_cnt)
						->set("new_user_cnt", $new_user_cnt);
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
			->add_breadcrumb("廣告")	
			->set("games", $this->db->get("games"))
			->set("query", isset($query) ? $query : false)
			->add_js_include("ad/index")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
	}
	
	function yahoo_20120717()
	{		
		$this->_init_layout();
		
		if ($this->input->post("submit")) {
			
			$server = "s12";
			$startDate = $this->input->post("start_date");
			$endDate   = $this->input->post("end_date");
			
			//$url='http://interface.forgamecenter.com/api_create_role.php?game=dhcq&op=long_e&server='.$server.'&startdate='.$startdate.'&enddate='.$enddate ;
			
			$query = $this->db->select("account, min(log_date) as log_date")
							->from("event20120717_yahoo")
							->where("log_date BETWEEN '{$startDate}' AND '{$endDate}'", null, false)
							->group_by("account")
							->order_by("min(log_date)")
							->get();
			$this->g_layout->set("query", $query);
		}	
		else {
			$startDate = '';
			$endDate = '';
		}

		$this->g_layout
			->add_breadcrumb("Yahoo關鍵字廣告")
			->set("startDate", $startDate)->set("endDate", $endDate)
			->add_js_include(array("event/yahoo_20120717", "jquery-ui-timepicker-addon"))->render();		
	}
	
	function manage()
	{
		//$this->zacl->check("ad", "manage");		
				
		$this->_init_layout();
		
		$ad_groups = $this->db->from("ad_groups ag")
				->join("games", "ag.game=games.game_id")->order_by("ag.id desc")->get()->result();
		
		foreach($ad_groups as $row) {
			$row->ads = $this->db->from("ads")->where("group_id", $row->id)->order_by("ad")->get()->result();	
		}		
		
		$this->g_layout
			->add_breadcrumb("廣告管理")	
			->set("ad_groups", $ad_groups)
			->render();	
	}
	
	function statistics()
	{			
		//$this->zacl->check("ad", "statistics");
		$this->load->helper("output_table");
		
		$this->_init_layout();
		
		if ($this->input->get("action")) 
		{
			header("Cache-Control: private");		
		
			$ad_channel = $this->input->get("ad_channel");
			
			$this->db->from("ad_traces adt")
				->join("games g", "g.game_id=adt.game");				

			if ($ad_channel) $this->db->like("adt.ad", $ad_channel);
			//$this->input->get("order_id") && $this->db->where("gb.order_id", $this->input->get("order_id"));
			
			if ($this->input->get("start_date")) {
				$start_date = $this->db->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->db->escape($this->input->get("end_date").":59");
					$date_query_str = "between {$start_date} and {$end_date}";
				}	
				else $date_query_str = ">= {$start_date}";
				$this->db->where("adt.create_time {$date_query_str}", null, false);
			}			

			switch ($this->input->get("action"))
			{	
				case "統計":
					$query = $this->db->select("adt.ad title, count(*) cnt, sum(case when device_id is null then 0 else 1 end) cnt2 ", false)
						->group_by("title")->order_by("title")->get();
					
					$where = 'where 1=1 ';
					if ($ad_channel) {
						$where .= "and gsr.ad='{$this->input->get("ad_channel")}'";
					}
					
					if ( ! empty($date_query_str)) $where .= " and gsr.create_time {$date_query_str}";
					$query2 = $this->db->query("SELECT gsr.ad title, count(*) cnt FROM characters gsr
						join ads on gsr.ad=ads.ad {$where} group by title");
					$this->g_layout->set("query2", $query2);
											
					break;
								
				case "時段統計":		
					switch($this->input->get("time_unit")) {
						case 'hour': $len=13; break;
						case 'day': $len=10; break;
						case 'month': $len=7; break;
						case 'year': $len=4; break;
						default: $len=10;
					}
					$query = $this->db->select("LEFT(adt.create_time, {$len}) title, count(*) cnt, sum(case when device_id is null then 0 else 1 end) cnt2 ", false)
						->group_by("title")
						->order_by("title desc")->get();
					
					$where = 'where 1=1 ';
					if ($ad_channel) {
						$where .= "and gsr.ad='{$this->input->get("ad_channel")}'";
					}
					
					if ( ! empty($date_query_str)) $where .= " and gsr.create_time {$date_query_str}";
					$query2 = $this->db->query("SELECT LEFT(gsr.create_time, {$len}) title, count(*) cnt FROM characters gsr
							join ads on gsr.ad=ads.ad {$where} group by LEFT(create_time, {$len})");
					$this->g_layout->set("query2", $query2);
					
					break;
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m')."-01 00:00",
				'time_unit' => 'day',
			);
			$_GET = $default_value;			
		}
		
		$this->g_layout
			->add_breadcrumb("廣告管理", "ad/manage")	
			->add_breadcrumb("廣告統計")	
			->set("query", isset($query) ? $query : false)
			->add_js_include("ad/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}		
		
	function add($group_id)
	{
		$this->zacl->check("ad", "modify");
		
		$this->_init_layout();
		
		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("廣告管理", "ad/manage")
			->add_breadcrumb("新增")
			->add_js_include("ad/form")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("games", $this->db->get_where("games", array("is_active" => "1")))
			->set("group_id", $group_id)
			->set("record", false)
			->render("ad/form");
	}
	
	function modify()
	{
		if ( ! $this->zacl->check_acl("ad", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('ad', '廣告ID', 'required|min_length[2]');

		if ($this->form_validation->run() == FALSE)
		{
			//echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{				
			$data = array(
				'ad'	=> $this->input->post("ad"),
				'group_id' => $this->input->post("group_id"),
			);
			
			
			if ($this->db->get_where("ads", array("ad" => $data['ad']))->num_rows() > 0) {
				die(json_failure("`{$data['ad']}` 廣告已存在."));	
			}

			$insert_id = $this->db->set("create_time", "now()", false)->insert("ads", $data);
			if (empty($insert_id)) {
				die(json_failure("db insert error."));					
			}
			
			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("ad/manage");
			
			echo json_message(array("back_url" => $back_url));
			return;			
		}
	}
	
	function delete($ad)
	{
		if ( ! $this->zacl->check_acl("ad", "delete")) die(json_failure("沒有權限"));
		
		$this->db->where("ad", $ad)->delete("ads");
		
		if ($this->db->affected_rows() > 0) {
			echo json_success();
		}
		else echo json_failure();
	}
	
	function edit_group($id) 
	{
		$this->zacl->check("ad", "modify");
		
		$this->_init_layout();		
		$this->load->library("user_agent");
		
		$this->g_layout
			->add_breadcrumb("廣告管理", "ad/manage")
			->add_breadcrumb("修改")
			->add_js_include("ad/form")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("games", $this->db->get_where("games", array("is_active" => "1")))
			->set("record", $this->db->get_where("ad_groups", array("id"=>$id))->row())
			->render("ad/group_form");		
	}
	
	function add_group()
	{
		$this->zacl->check("ad", "modify");
		
		$this->_init_layout();
		
		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("廣告管理", "ad/manage")
			->add_breadcrumb("新增")
			->add_js_include("ad/form")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("games", $this->db->get_where("games", array("is_active" => "1")))
			->set("record", false)
			->render("ad/group_form");
	}
	
	function modify_group()
	{
		if ( ! $this->zacl->check_acl("ad", "modify")) die(json_failure("沒有權限"));
		
		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('game', '遊戲', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{				
			$data = array(
				'game'	=> $this->input->post("game"),
				'open_url_android' => $this->input->post("open_url_android"),
				'open_url_ios' => $this->input->post("open_url_ios"),
			); 			
			
			if ($id = $this->input->post("id")) { //修改
				$this->db->where("id", $id)->update("ad_groups", $data);
				if ($this->db->affected_rows() == 0) {
					die(json_failure("db update error."));					
				}
			}
			else { //新增
				$insert_id = $this->db->set("create_time", "now()", false)->insert("ad_groups", $data);
				if (empty($insert_id)) {
					die(json_failure("db insert error."));					
				}
			}
			
			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("ad/manage");
			
			echo json_message(array("back_url" => $back_url));
			return;			
		}
	}
	
	function delete_group($id)
	{
		if ( ! $this->zacl->check_acl("ad", "delete")) die(json_failure("沒有權限"));
		
		$this->db->where("id", $id)->delete("ad_groups");
		
		if ($this->db->affected_rows() > 0) {
			$this->db->where("group_id", $id)->delete("ads");
			
			echo json_success();
		}
		else echo json_failure();
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */