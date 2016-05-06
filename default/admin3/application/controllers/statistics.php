<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();					
		
		error_reporting(E_ALL);
		ini_set('display_errors','On');		
	}		
	
	function _init_statistics_layout()
	{
		$this->zacl->check_login(true);
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}
		
		return $this->_init_layout()
			->add_breadcrumb("統計", "statistics");
	}
	
	function index()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		//$this->zacl->check("game_statistics", "read");
		$date = array();
		$date[0] = date("Y-m-d",strtotime("-1 days"));
		$date[1] = date("Y-m-d",strtotime("-2 days"));
		$date[2] = date("Y-m-d",strtotime("-8 days"));
		
		foreach($date as $k => $d) {
			$d_1=date("Y-m-d",strtotime("-1 days", strtotime($d)));
			$d_7=date("Y-m-d",strtotime("-7 days", strtotime($d)));
			
			$name = 'query'.$k;
			$$name = $this->DB2->query("
				SELECT
					*
				FROM
				(
					SELECT 
						date AS 'find_date',
						SUM(login_count) 'login_count',
						SUM(new_login_count) 'new_login_count',
						SUM(device_count) 'device_count',
						SUM(deposit_user_count) 'deposit_user_count',
						SUM(new_deposit_user_count) 'new_deposit_user_count',
						SUM(deposit_total) 'deposit_total',
						SUM(consume_total) 'consume_total',
						SUM(peak_user_count) 'peak_user_count',
						SUM(total_time) 'total_time',
						SUM(one_ltv) 'one_ltv'
					FROM statistics
					WHERE date = '{$d}'
					GROUP by date
				) main,
				(
					SELECT 
						SUM(new_login_count) 'new_login_count_1',
						SUM(one_retention_all_count) 'one_retention_all_count',
						SUM(one_retention_count) 'one_retention_count'
					FROM statistics
					WHERE date = '{$d_1}'
					GROUP by date
				) main_1,
				(
					SELECT 
						SUM(new_login_count) 'new_login_count_7',
						SUM(seven_retention_count) 'seven_retention_count'
					FROM statistics
					WHERE date = '{$d_7}'
					GROUP by date
				) main_7,
				(
					SELECT
						SUM(new_login_count) 'total_users',
						SUM(deposit_total) 'historical_revenue_sum',
						SUM(new_deposit_user_count) 'historical_deposit_user_count'
					FROM
						statistics
					WHERE
						date <= '{$d}'
				) historical_total
			");
		}
		
		$this->g_layout
			->set("query0", isset($query0) ? $query0 : false)
			->set("query1", isset($query1) ? $query1 : false)
			->set("query2", isset($query2) ? $query2 : false)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function statistics()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		if ($this->input->get("action")) 
		{											
			$date_field = "";
			
			switch ($this->input->get("action"))
			{
				case "流失率統計":
					
					$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
					$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
					
					$where = " where 1=1";
					
					if ($server = $this->input->get("server")) {
						$where .= " and gi.server_id={$server} ";
					} else $where .= " and gi.game_id='{$this->game_id}' ";					
					
					if ($channel = $this->input->get("channel")) {
						if ($channel == 'long_e') $where .= " and lgl.account not like '%@%' ";
						else {
							$where .= " and lgl.account like '%@{$channel}' ";
						}
					}
					
					if ($ad_channel = $this->input->get("ad_channel")) {
						$where .= " and lgl.ad like '{$ad_channel}%' ";
					}
			
					$query = $this->DB2->query("
							
						select d, count(*) 'login_cnt', sum(role) 'role_cnt', 
							round(1-sum(role)/count(*), 3) 'role_p',
							sum(role)-sum(c1) 'c1', 
							round((sum(role)-sum(c1)) / sum(role), 3) 'c1_p',
							sum(role)-sum(c7) 'c7', 
							round((sum(role)-sum(c7)) / sum(role), 3) 'c7_p',
							sum(role)-sum(c15) 'c15', 
							round((sum(role)-sum(c15)) / sum(role), 3) 'c15_p',
							sum(role)-sum(c30) 'c30', 
							round((sum(role)-sum(c30)) / sum(role), 3) 'c30_p'
						from 
						(
							SELECT 
								date(lgl.create_time) 'd', lgl.uid, lgl.server_id, gsr.name,
								if (gsr.id is null, '0', '1') 'role',
								if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
									where uid=gsr.uid and server_id=gsr.server_id 
										and create_time >= date_add(date(gsr.create_time), interval 1 day))
								) 'c1', 
								if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
									where uid=gsr.uid and server_id=gsr.server_id 
										and create_time >= date_add(date(gsr.create_time), interval 6 day))
								) 'c7', 
								if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
									where uid=gsr.uid and server_id=gsr.server_id 
										and create_time >= date_add(date(gsr.create_time), interval 14 day)) 
								) 'c15', 
								if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
									where uid=gsr.uid and server_id=gsr.server_id 
										and create_time >= date_add(date(gsr.create_time), interval 29 day)) 
								) 'c30'
							FROM log_game_logins lgl
							join servers gi on lgl.server_id=gi.server_id
							left join characters gsr on date(gsr.create_time)=date(lgl.create_time) and gsr.uid=lgl.uid and gsr.server_id=lgl.server_id
							{$where}
							and lgl.create_time between '{$start_date}' and '{$end_date} 23:59:59'
							and not exists(select * from characters where 
								create_time < lgl.create_time and uid=lgl.uid and server_id=lgl.server_id
							)	
							group by date(lgl.create_time), lgl.uid, lgl.server_id 
						) tmp
						group by d														
					");
					break;
					
				case "廣告時段統計":	
					$query = $this->DB2->select("ga.ad, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->from('characters ga')
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("time, ga.ad")
						->order_by("time desc, ga.ad")->get();
					//die($this->DB2->last_query());
					break;					
			}			
			
			if (empty($query)) {
				if ($this->input->get("start_date")) {
					$start_date = $this->DB2->escape($this->input->get("start_date"));
					if ($this->input->get("end_date")) {
						$end_date = $this->DB2->escape($this->input->get("end_date").":59");
						$this->DB2->where("{$date_field} between {$start_date} and {$end_date}", null, false);	
					}	
					else $this->DB2->where("{$date_field} >= {$start_date}", null, false);
				}			
				
				$query = $this->DB2->get();
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'start_date' => date("Y-m-d"),
				'single_date' => date("Y-m-d"),
			);
			$_GET = $default_value;	
		}		
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function lifetime_value()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->where("game_id", $game_id)->where("date >=", $start_date)->where("date <=", $end_date)->get("statistics");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->set("span", $span)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
    
    function game_consumes() {
		$this->zacl->check_login(true);		
		
		$this->zacl->check("game_statistics", "read");
		
		$this->_init_statistics_layout();
		$this->load->helper("output_table");
            
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");
            
		if ($this->input->get("action") && $game_id) 
		{
			header("Cache-Control: private");	
            
            $game = $this->config->item("game");
            $start_timestamp = strtotime($start_date);
            $end_timestamp = strtotime($end_date);
            
            $this->load->config('g_mongodb');
            $g_mongodb = $this->config->item('mongo_db');
            
            $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
            
            try {
            
                $itemuse_command = new MongoDB\Driver\Command([
                    'aggregate' => 'le_UserItemUse',
                    'pipeline' => [
                        [
                            '$match' => ['game_id' => $game_id, 'le_logTime' => ['$gte' => $start_timestamp, '$lte' => $end_timestamp]],
                        ],
                        [
                            '$group' => [
                                '_id' => ['game_id' => '$game_id', 'le_contentId' => '$le_contentId', 'le_contentType' => '$le_contentType'],
                                'le_count' => ['$sum' => '$le_count']
                            ]
                        ],
                    ],
                    'cursor' => new stdClass,
                ]);
                $itemuse_cursor = $manager->executeCommand('longe_log', $itemuse_command);
                
                $itemget_command = new MongoDB\Driver\Command([
                    'aggregate' => 'le_UserItemGet',
                    'pipeline' => [
                        [
                            '$match' => ['game_id' => $game_id, 'le_logTime' => ['$gte' => $start_timestamp, '$lte' => $end_timestamp]],
                        ],
                        [
                            '$group' => [
                                '_id' => ['game_id' => '$game_id', 'le_contentId' => '$le_contentId', 'le_contentType' => '$le_contentType', 'le_price' => '$le_price'],
                                'le_count' => ['$sum' => '$le_count']
                            ]
                        ],
                    ],
                    'cursor' => new stdClass,
                ]);
                $itemget_cursor = $manager->executeCommand('longe_log', $itemget_command);

                $itemget_result = [];
                
                foreach ($itemget_cursor as $itemget_document) {
                    $itemget_document->used=0;
                    foreach ($itemuse_cursor as $itemuse_document) {
                        if ($itemuse_document->_id->le_contentId==$itemget_document->_id->le_contentId) $itemget_document->used=$itemuse_document->le_count;
                    }
                    $itemget_result[] = $itemget_document;
                }
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo $e->getMessage(), "\n";
            }
					
            $this->load->library('pagination');
            $this->pagination->initialize(array(
                    'base_url'	=> site_url("trade/game_consumes"),
                    'total_rows'=> isset($itemget_result) ? count($itemget_result) : 0,
                    'per_page'	=> 100
                ));			
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
			->add_breadcrumb("消費分析")	
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("query", isset($itemget_result) ? $itemget_result : false)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
    }
	
	function game_length_new()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");

        switch($span) {
			case "weekly":
			    $date_group = 'YEARWEEK(date, 3)';
				break;
			
			case "monthly":
			    $date_group = 'MONTH(date)';
				break;
				
			default:
			    $date_group = 'DATE(date)';
				break;
		}		
		
		if (!$span){
			$query = $this->DB2->query("
				SELECT
					date,
					new_login_count 'login_count',
					new_login_count_15 'login_count_15',
					new_login_count_30 'login_count_30',
					new_login_count_60 'login_count_60',
					new_login_count_90 'login_count_90',
					new_login_count_120 'login_count_120',
					new_login_count_more 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				ORDER BY date DESC
			");
		} else {
			$query = $this->DB2->query("
				SELECT
					YEAR(date) 'year',
					{$date_group} 'date',
					SUM(new_login_count) 'login_count',
					SUM(new_login_count_15) 'login_count_15',
					SUM(new_login_count_30) 'login_count_30',
					SUM(new_login_count_60) 'login_count_60',
					SUM(new_login_count_90) 'login_count_90',
					SUM(new_login_count_120) 'login_count_120',
					SUM(new_login_count_more) 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				GROUP BY game_id, YEAR(date), {$date_group}
				ORDER BY YEAR(date) DESC, {$date_group} DESC
			");
		}
        
		$this->g_layout
			->add_breadcrumb("在線時長")
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("type", "新增用戶")
			->set("span", $span)
		
		->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("statistics/game_length");
	}
	
	function game_length_all()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");

        switch($span) {
			case "weekly":
			    $date_group = 'YEARWEEK(date, 3)';
				break;
			
			case "monthly":
			    $date_group = 'MONTH(date)';
				break;
				
			default:
			    $date_group = 'DATE(date)';
				break;
		}		
		
		if (!$span){
			$query = $this->DB2->query("
				SELECT
					date,
					login_count,
					login_count_15,
					login_count_30,
					login_count_60,
					login_count_90,
					login_count_120,
					login_count_more
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				ORDER BY date DESC
			");
		} else {
			$query = $this->DB2->query("
				SELECT
					YEAR(date) 'year',
					{$date_group} 'date',
					SUM(login_count) 'login_count',
					SUM(login_count_15) 'login_count_15',
					SUM(login_count_30) 'login_count_30',
					SUM(login_count_60) 'login_count_60',
					SUM(login_count_90) 'login_count_90',
					SUM(login_count_120) 'login_count_120',
					SUM(login_count_more) 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				GROUP BY game_id, YEAR(date), {$date_group}
				ORDER BY YEAR(date) DESC, {$date_group} DESC
			");
		}
        
		$this->g_layout
			->add_breadcrumb("在線時長")
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("type", "所有用戶")
			->set("span", $span)
		
		->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("statistics/game_length");
	}
	
	function game_length_deposit()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");

        switch($span) {
			case "weekly":
			    $date_group = 'YEARWEEK(date, 3)';
				break;
			
			case "monthly":
			    $date_group = 'MONTH(date)';
				break;
				
			default:
			    $date_group = 'DATE(date)';
				break;
		}		
		
		if (!$span){
			$query = $this->DB2->query("
				SELECT
					date,
					deposit_login_count 'login_count',
					deposit_login_count_15 'login_count_15',
					deposit_login_count_30 'login_count_30',
					deposit_login_count_60 'login_count_60',
					deposit_login_count_90 'login_count_90',
					deposit_login_count_120 'login_count_120',
					deposit_login_count_more 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				ORDER BY date DESC
			");
		} else {
			$query = $this->DB2->query("
				SELECT
					YEAR(date) 'year',
					{$date_group} 'date',
					SUM(deposit_login_count) 'login_count',
					SUM(deposit_login_count_15) 'login_count_15',
					SUM(deposit_login_count_30) 'login_count_30',
					SUM(deposit_login_count_60) 'login_count_60',
					SUM(deposit_login_count_90) 'login_count_90',
					SUM(deposit_login_count_120) 'login_count_120',
					SUM(deposit_login_count_more) 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				GROUP BY game_id, YEAR(date), {$date_group}
				ORDER BY YEAR(date) DESC, {$date_group} DESC
			");
		}
        
		$this->g_layout
			->add_breadcrumb("在線時長")
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("type", "儲值用戶")
			->set("span", $span)
		
		->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("statistics/game_length");
	}
	
	function game_length_new_deposit()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");

        switch($span) {
			case "weekly":
			    $date_group = 'YEARWEEK(date, 3)';
				break;
			
			case "monthly":
			    $date_group = 'MONTH(date)';
				break;
				
			default:
			    $date_group = 'DATE(date)';
				break;
		}		
		
		if (!$span){
			$query = $this->DB2->query("
				SELECT
					date,
					new_deposit_login_count 'login_count',
					new_deposit_login_count_15 'login_count_15',
					new_deposit_login_count_30 'login_count_30',
					new_deposit_login_count_60 'login_count_60',
					new_deposit_login_count_90 'login_count_90',
					new_deposit_login_count_120 'login_count_120',
					new_deposit_login_count_more 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				ORDER BY date DESC
			");
		} else {
			$query = $this->DB2->query("
				SELECT
					YEAR(date) 'year',
					{$date_group} 'date',
					SUM(new_deposit_login_count) 'login_count',
					SUM(new_deposit_login_count_15) 'login_count_15',
					SUM(new_deposit_login_count_30) 'login_count_30',
					SUM(new_deposit_login_count_60) 'login_count_60',
					SUM(new_deposit_login_count_90) 'login_count_90',
					SUM(new_deposit_login_count_120) 'login_count_120',
					SUM(new_deposit_login_count_more) 'login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				GROUP BY game_id, YEAR(date), {$date_group}
				ORDER BY YEAR(date) DESC, {$date_group} DESC
			");
		}
        
		$this->g_layout
			->add_breadcrumb("在線時長")
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("type", "新增儲值用戶")
			->set("span", $span)
		
		->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render("statistics/game_length");
	}
	
	function operation()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
		
        switch($span) {
			case "weekly":
			    $date_group = 'YEARWEEK';
			    $interval = 'WEEK';
				$stc3 = 'weekly_statistics';
				break;
			
			case "monthly":
			    $date_group = 'MONTH';
				$interval = 'MONTH';
				$stc3 = 'monthly_statistics';
				break;
				
			default:
			    $date_group = 'DATE';
				$interval = 'DATE';
				break;
		}
		
		if (!$span) {
		    $query = $this->DB2->query("
		    (SELECT 
		        stc.date 'date',
			    stc.game_id 'game_id',
			    stc.login_count 'login_count',
			    stc.new_login_count 'new_login_count',
			    stc2.login_count 'y_login_count',
			    stc2.new_login_count 'y_new_login_count',
                stc2.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.deposit_total 'deposit_total',
                stc.new_deposit_user_count 'new_user_deposit_count',
                stc.new_user_deposit_total 'new_user_deposit_total'
		    FROM statistics stc
		    LEFT JOIN statistics stc2 ON stc.game_id=stc2.game_id AND stc.date=DATE_ADD(stc2.date, interval 1 day)
		    WHERE stc.game_id = '{$game_id}'
		    AND stc.date BETWEEN '{$start_date}' AND '{$end_date}')
		
		    UNION
		
		    (SELECT 
		        stc.date 'date',
			    stc.game_id 'game_id',
			    stc.login_count 'login_count',
			    stc.new_login_count 'new_login_count',
			    stc2.login_count 'y_login_count',
			    stc2.new_login_count 'y_new_login_count',
                stc2.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.deposit_total 'deposit_total',
                stc.new_deposit_user_count 'new_user_deposit_count',
                stc.new_user_deposit_total 'new_user_deposit_total'
		    FROM statistics stc
		    RIGHT JOIN statistics stc2 ON stc.game_id=stc2.game_id AND stc.date=DATE_ADD(stc2.date, interval 1 day)
		    WHERE stc.game_id = '{$game_id}'
		    AND stc.date BETWEEN '{$start_date}' AND '{$end_date}')
		    ORDER BY date DESC
		    ");
		}
		elseif('weekly'==$span)
		{
		    $query = $this->DB2->query("
		    (SELECT 
		        YEARWEEK((stc.date),3) 'date',
			    stc.game_id 'game_id',
			    SUM(stc.login_count) 'login_count',
			    SUM(stc.new_login_count) 'new_login_count',
			    SUM(stc2.login_count) 'y_login_count',
			    SUM(stc2.new_login_count) 'y_new_login_count',
                SUM(stc3.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.deposit_total) 'deposit_total',
			    SUM(stc3.new_user_deposit_count) 'new_user_deposit_count',
                SUM(stc3.new_user_deposit_total) 'new_user_deposit_total'
		    FROM statistics stc
		    LEFT JOIN statistics stc2 ON stc.game_id=stc2.game_id 
				AND stc.date=DATE_ADD(stc2.date, interval 1 week)
			LEFT JOIN weekly_statistics stc3 ON stc3.game_id=stc2.game_id 
				AND stc3.date=stc2.date
		    WHERE 
				stc.date BETWEEN '{$start_date}' AND '{$end_date}'
					AND stc.game_id = '{$game_id}'
		    GROUP BY YEARWEEK((stc.date),3))
		
		    UNION
		
		    (SELECT 
		        YEARWEEK((stc.date),3) 'date',
			    stc.game_id 'game_id',
			    SUM(stc.login_count) 'login_count',
			    SUM(stc.new_login_count) 'new_login_count',
			    SUM(stc2.login_count) 'y_login_count',
			    SUM(stc2.new_login_count) 'y_new_login_count',
                SUM(stc3.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.deposit_total) 'deposit_total',
			    SUM(stc3.new_user_deposit_count) 'new_user_deposit_count',
                SUM(stc3.new_user_deposit_total) 'new_user_deposit_total'
		    FROM statistics stc
		    RIGHT JOIN statistics stc2 ON stc.game_id=stc2.game_id 
				AND stc.date=DATE_ADD(stc2.date, interval 1 week)
			LEFT JOIN weekly_statistics stc3 ON stc3.game_id=stc2.game_id 
				AND stc3.date=stc2.date
		    WHERE 
				stc.date BETWEEN '{$start_date}' AND '{$end_date}'
					AND stc.game_id = '{$game_id}'
		    GROUP BY YEARWEEK((stc.date),3))
		    ORDER BY date DESC
		    ");
		}
		else
		{
			$stc2_start_date = date("Y-m", strtotime('-1 month', strtotime($start_date)))."-01";
			$stc2_end_date = date("Y-m-t", strtotime('-1 month', strtotime($end_date)));
			
		    $query = $this->DB2->query("
				SELECT 
					stc.*,
					stc2.y_login_count,
					stc2.y_new_login_count,
					stc3.one_retention_count 'y_one_retention_count',
					stc3.new_user_deposit_count 'new_user_deposit_count',
					stc3.new_user_deposit_total 'new_user_deposit_total'
				FROM
					(
						SELECT 
							YEAR(stc.date) 'year',
							MONTH(stc.date) 'date',
							stc.game_id 'game_id',
							SUM(stc.login_count) 'login_count',
							SUM(stc.new_login_count) 'new_login_count',
							SUM(stc.deposit_user_count) 'deposit_user_count',
							SUM(stc.deposit_total) 'deposit_total'
						FROM
							statistics stc
						WHERE
							stc.date BETWEEN '{$start_date}' AND '{$end_date}'
								AND stc.game_id = '{$game_id}'
						GROUP BY YEAR(stc.date) , MONTH(stc.date)
					) stc
						LEFT JOIN
					(
						SELECT 
							game_id,
							YEAR(date) 'year',
							MONTH(date) 'date',
							SUM(login_count) 'y_login_count',
							SUM(new_login_count) 'y_new_login_count'
						FROM
							statistics
						WHERE
							game_id = '{$game_id}'
								AND date BETWEEN '{$stc2_start_date}' AND '{$stc2_end_date}'
						GROUP BY YEAR(date) , MONTH(date)
					) stc2 ON stc.game_id = stc2.game_id
						AND stc2.year = YEAR(DATE_ADD(CONCAT(stc.year, '-', stc.date, '-01'), INTERVAL - 1 MONTH))
						AND stc2.date = MONTH(DATE_ADD(CONCAT(stc.year, '-', stc.date, '-01'), INTERVAL - 1 MONTH))
						LEFT JOIN
					monthly_statistics stc3 ON stc3.game_id = stc2.game_id
						AND YEAR(stc3.date) = stc2.year
						AND MONTH(stc3.date) = stc2.date
				ORDER BY stc.year DESC, stc.date DESC
		    ");
		}
        
		$this->load->library('jpgraph');
		$jgraph_data = array();
		$jgraph_labels = array();
        
        $expected_date;
        $row_cnt = 0;
        $sum_type;
        
		foreach($query->result() as $row) {
            $row_cnt++;
            if ($date_group == 'DATE') {
                if ($row_cnt>1) {
                    for($next_date=strtotime((string)$row->date); $next_date<$expected_date; $expected_date=strtotime('-1 day', $expected_date)) {
                        $row_cnt++;
                        $jgraph_data[] = 0;
                        $jgraph_labels[] = date('m/d', $expected_date);
                    }
                }
                $expected_date = strtotime('-1 day', strtotime((string)$row->date));
                $jgraph_labels[] = date('m/d', strtotime((string)$row->date));
            } else if ($date_group == 'WEEK') {
                $jgraph_labels[] = date('m/d', strtotime(sprintf("%4dW%02d", (string)$row->year, (string)$row->date)));
            } else {
                $jgraph_labels[] = (string)$row->date;
            }
            						
            switch ($this->input->get("action"))
            {
                case "登入用戶":
                    $jgraph_data[] = $row->login_count;
                    break;
                case "付費比":
                    $jgraph_data[] = ($row->login_count)?$row->deposit_user_count/$row->login_count:0;
                    break;
                case "儲值人數":
                    $jgraph_data[] = $row->deposit_user_count;
                    break;
                case "ARPPU":
                    $jgraph_data[] = ($row->deposit_user_count)?$row->deposit_total/$row->deposit_user_count:0;
                    break;
                case "日營收":
                    $jgraph_data[] = $row->deposit_total;
                    break;
                case "註冊留存":
                    $jgraph_data[] = ($row->y_new_login_count)?$row->y_one_retention_count/$row->y_new_login_count:0;
                    break;
                case "行銷花費":
                    $jgraph_data[] = 0;
                    break;
                case "新用戶付費":
                    $jgraph_data[] = $row->new_user_deposit_total;
                    break;
                case "付費ROI":
                    $jgraph_data[] = 0;
                    break;
                case "整體ROI":
                    $jgraph_data[] = 0;
                    break;
                default:
                    $jgraph_data[] = $row->new_login_count;
                    break;
            }
		}
        
		$settings = array('width' => $row_cnt*35+50);
        
		$revenue_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/'.$span.'_operation_graph', $settings);
        
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function deposit_level()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
    
        $query = $this->DB2->query("
            SELECT
                COUNT(uid) 'user_count',
                SUM(amount) 'total',
				SUM(CASE WHEN amount>=10001 THEN 1 ELSE 0 END) 'lvl6',
				SUM(CASE WHEN amount BETWEEN 5000 AND 10000 THEN 1 ELSE 0 END) 'lvl5',
				SUM(CASE WHEN amount BETWEEN 2000 AND 4999 THEN 1 ELSE 0 END) 'lvl4',
				SUM(CASE WHEN amount BETWEEN 1000 AND 1999 THEN 1 ELSE 0 END) 'lvl3',
				SUM(CASE WHEN amount BETWEEN 500 AND 999 THEN 1 ELSE 0 END) 'lvl2',
				SUM(CASE WHEN amount<=499 THEN 1 ELSE 0 END) 'lvl1',
				SUM(CASE WHEN amount>=10001 THEN amount ELSE 0 END) 'lvl6_sum',
				SUM(CASE WHEN amount BETWEEN 5000 AND 10000 THEN amount ELSE 0 END) 'lvl5_sum',
				SUM(CASE WHEN amount BETWEEN 2000 AND 4999 THEN amount ELSE 0 END) 'lvl4_sum',
				SUM(CASE WHEN amount BETWEEN 1000 AND 1999 THEN amount ELSE 0 END) 'lvl3_sum',
				SUM(CASE WHEN amount BETWEEN 500 AND 999 THEN amount ELSE 0 END) 'lvl2_sum',
				SUM(CASE WHEN amount<=499 THEN amount ELSE 0 END) 'lvl1_sum'
            FROM
            (
                SELECT
                    ub.uid 'uid',
                    SUM(ub.amount) 'amount'
                FROM user_billing ub
                    JOIN servers sr ON ub.server_id=sr.server_id
                WHERE sr.game_id = '{$game_id}'
                    AND ub.create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
                    AND ub.billing_type = 2
                    AND ub.result = 1
                GROUP BY ub.uid
            ) tmp
        ");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function deposit_analysis()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
    
        $query = $this->DB2->query("
            SELECT 
		        date,
			    game_id,
			    login_count 'login_count',
			    new_login_count 'new_login_count',
                deposit_user_count 'deposit_user_count',
                deposit_total 'deposit_total',
                new_user_deposit_count 'new_user_deposit_count',
                new_user_deposit_total 'new_user_deposit_total'
		    FROM statistics 
		    WHERE game_id = '{$game_id}'
		    AND date BETWEEN '{$start_date}' AND '{$end_date}'
        ");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function revenue()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		
        switch($span) {
			case "weekly":
			    $date_group = 'WEEK';
				break;
			
			case "monthly":
			    $date_group = 'MONTH';
				break;
				
			default:
			    $date_group = 'DATE';
				break;
		}
		
		$query = $this->DB2->query("
			SELECT
				YEAR(create_time) 'year',
				{$date_group}(create_time) 'date',
				SUM(amount) 'sum',
				SUM(CASE WHEN transaction_type='inapp_billing_ios' THEN amount ELSE 0 END) 'ios_sum',
				SUM(CASE WHEN transaction_type='inapp_billing_google' THEN amount ELSE 0 END) 'android_sum',
				SUM(CASE WHEN transaction_type='gash_billing' THEN amount ELSE 0 END) 'gash_sum',
				SUM(CASE WHEN transaction_type not in ('inapp_billing_ios','inapp_billing_google','gash_billing') THEN amount ELSE 0 END) 'other_billing_sum',
				SUM(CASE WHEN country_code='TWN' THEN amount ELSE 0 END) 'twn_sum',
				SUM(CASE WHEN country_code='HKG' THEN amount ELSE 0 END) 'hkg_sum',
				SUM(CASE WHEN country_code='MAC' THEN amount ELSE 0 END) 'mac_sum',
				SUM(CASE WHEN country_code='SGP' THEN amount ELSE 0 END) 'sgp_sum',
				SUM(CASE WHEN country_code='MYS' THEN amount ELSE 0 END) 'mys_sum',
				SUM(CASE WHEN country_code not in ('TWN','HKG','MAC','SGP','MYS') THEN amount ELSE 0 END) 'other_country_sum'
			FROM user_billing
				JOIN servers ON user_billing.server_id=servers.server_id
			WHERE create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND billing_type = 1
				AND result = 1
			GROUP BY YEAR(create_time), {$date_group}(create_time)
		    ORDER BY YEAR(create_time) DESC, {$date_group}(create_time) DESC
		");
        
		$this->load->library('jpgraph');
		$jgraph_data = array();
		$jgraph_labels = array();
        
        $expected_date;
        $row_cnt = 0;
        $sum_type;
										
        switch ($this->input->get("action"))
        {
            case "iOS":
                $sum_type = 'ios_sum';
                break;
            case "Android":
                $sum_type = 'android_sum';
                break;
            case "GASH":
                $sum_type = 'gash_sum';
                break;
            case "其他儲點":
                $sum_type = 'other_billing_sum';
                break;
            default:
                $sum_type = 'sum';
                break;
        }
        
		foreach($query->result() as $row) {
            $row_cnt++;
            if ($date_group == 'DATE') {
                if ($row_cnt>1) {
                    for($next_date=strtotime((string)$row->date); $next_date<$expected_date; $expected_date=strtotime('-1 day', $expected_date)) {
                        $row_cnt++;
                        $jgraph_data[] = 0;
                        $jgraph_labels[] = date('m/d', $expected_date);
                    }
                }
                $expected_date = strtotime('-1 day', strtotime((string)$row->date));
                $jgraph_labels[] = date('m/d', strtotime((string)$row->date));
            } else if ($date_group == 'WEEK') {
                $jgraph_labels[] = date('m/d', strtotime(sprintf("%4dW%02d", (string)$row->year, (string)$row->date)));
            } else {
                $jgraph_labels[] = (string)$row->date;
            }
            
            $jgraph_data[] = $row->$sum_type;
		}
        
		$settings = array('width' => $row_cnt*35+50);
        
		$revenue_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/'.$span.'_revenue_graph', $settings);
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_new()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
    
        $query = $this->DB2->query("
            SELECT 
		        date,
			    game_id,
			    new_login_count,
                new_device_count
		    FROM statistics 
		    WHERE game_id = '{$game_id}'
		    AND date BETWEEN '{$start_date}' AND '{$end_date}'
        ");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_new_by_login()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		//$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
    
        $query = $this->DB2->query("
            SELECT 
		        date,
			    game_id,
			    new_login_count,
                new_login_facebook_count,
                new_login_google_count,
                new_login_longe_count,
                new_login_quick_count,
                new_device_count,
                new_device_facebook_count,
                new_device_google_count,
                new_device_longe_count,
                new_device_quick_count
		    FROM statistics 
		    WHERE game_id = '{$game_id}'
		    AND date BETWEEN '{$start_date}' AND '{$end_date}'
        ");
		
        $facebook_count = 0;
        $google_count = 0;
        $longe_count = 0;
        $quick_count = 0;
        
        if ($query) {
			foreach($query->result() as $row) {
                if ($this->input->get("action") == '新增登入設備') {
                    $facebook_count += $row->new_device_facebook_count;
                    $google_count += $row->new_device_google_count;
                    $longe_count += $row->new_device_longe_count;
                    $quick_count += $row->new_device_quick_count;
                } else {
                    $facebook_count += $row->new_login_facebook_count;
                    $google_count += $row->new_login_google_count;
                    $longe_count += $row->new_login_longe_count;
                    $quick_count += $row->new_login_quick_count;
                }
            }
        }
        
        $this->load->library('jpgraph');
        $jgraph_data = array();
        $jgraph_labels = array();
        $jgraph_data[]=($facebook_count); $jgraph_labels[]="FB\n(%.1f%%)";
        $jgraph_data[]=($google_count); $jgraph_labels[]="Google\n(%.1f%%)";
        $jgraph_data[]=($longe_count); $jgraph_labels[]="Longe\n(%.1f%%)";
        $jgraph_data[]=($quick_count); $jgraph_labels[]="Quick\n(%.1f%%)";
        
        $deposit_pie_chart = $this->jpgraph->pie_chart($jgraph_data, $jgraph_labels, "", dirname(__FILE__).'/../../p/jpgraphs/user_new_by_login_pie_chart');
        
        
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_retention()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->where("game_id", $game_id)->where("date >=", $start_date)->where("date <=", $end_date)->order_by("date", "desc")->get("statistics");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_retention_by_login()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->where("game_id", $game_id)->where("date >=", $start_date)->where("date <=", $end_date)->order_by("date", "desc")->get("statistics");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_return()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT
				statistics.date,
				statistics.game_id,
				statistics.one_return_count 'one_return_count',
				statistics.one_return_rate 'one_return_rate',
				statistics.three_return_count 'three_return_count',
				statistics.three_return_rate 'three_return_rate',
				weekly_statistics.return_count 'weekly_return_count',
				weekly_statistics.return_rate 'weekly_return_rate',
				monthly_statistics.return_count 'monthly_return_count',
				monthly_statistics.return_rate 'monthly_return_rate'
			FROM statistics
			LEFT JOIN weekly_statistics 
				ON statistics.game_id = weekly_statistics.game_id 
				AND statistics.date = weekly_statistics.date 
			LEFT JOIN monthly_statistics 
				ON statistics.game_id = monthly_statistics.game_id 
				AND statistics.date = monthly_statistics.date 
			WHERE statistics.date BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND statistics.game_id = '{$game_id}'
		    ORDER BY statistics.date DESC
		");
		
		$region_query = $this->DB2->query("
			SELECT 
				user_info.nation, COUNT(user_info.uid) 'user_count'
			FROM
				users
				LEFT JOIN user_info ON users.uid=user_info.uid
			WHERE
				users.create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
			GROUP BY user_info.nation
		");
		
		$this->load->library('jpgraph');
		$jgraph_data = array();
		$jgraph_labels = array();
		
		foreach($region_query->result() as $row) {
			$jgraph_data[] = $row->user_count;
			$jgraph_labels[] = $row->nation;
		}
		
		$region_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/region_graph');
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("region_graph",  isset($region_graph) ? $region_graph : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function user_return_by_login()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT
				statistics.date,
				statistics.game_id,
				statistics.one_return_facebook_count 'one_return_facebook_count',
				statistics.one_return_google_count 'one_return_google_count',
				statistics.one_return_longe_count 'one_return_longe_count',
				statistics.one_return_quick_count 'one_return_quick_count',
				statistics.one_return_count 'one_return_count',
				statistics.one_return_facebook_rate 'one_return_facebook_rate',
				statistics.one_return_google_rate 'one_return_google_rate',
				statistics.one_return_longe_rate 'one_return_longe_rate',
				statistics.one_return_quick_rate 'one_return_quick_rate',
				statistics.one_return_rate 'one_return_rate',
				statistics.three_return_facebook_count 'three_return_facebook_count',
				statistics.three_return_google_count 'three_return_google_count',
				statistics.three_return_longe_count 'three_return_longe_count',
				statistics.three_return_quick_count 'three_return_quick_count',
				statistics.three_return_count 'three_return_count',
				statistics.three_return_facebook_rate 'three_return_facebook_rate',
				statistics.three_return_google_rate 'three_return_google_rate',
				statistics.three_return_longe_rate 'three_return_longe_rate',
				statistics.three_return_quick_rate 'three_return_quick_rate',
				statistics.three_return_rate 'three_return_rate',
				weekly_statistics.return_facebook_count 'weekly_return_facebook_count',
				weekly_statistics.return_google_count 'weekly_return_google_count',
				weekly_statistics.return_longe_count 'weekly_return_longe_count',
				weekly_statistics.return_quick_count 'weekly_return_quick_count',
				weekly_statistics.return_count 'weekly_return_count',
				weekly_statistics.return_facebook_rate 'weekly_return_facebook_rate',
				weekly_statistics.return_google_rate 'weekly_return_google_rate',
				weekly_statistics.return_longe_rate 'weekly_return_longe_rate',
				weekly_statistics.return_quick_rate 'weekly_return_quick_rate',
				weekly_statistics.return_rate 'weekly_return_rate',
				monthly_statistics.return_facebook_count 'monthly_return_facebook_count',
				monthly_statistics.return_google_count 'monthly_return_google_count',
				monthly_statistics.return_longe_count 'monthly_return_longe_count',
				monthly_statistics.return_quick_count 'monthly_return_quick_count',
				monthly_statistics.return_count 'monthly_return_count',
				monthly_statistics.return_facebook_rate 'monthly_return_facebook_rate',
				monthly_statistics.return_google_rate 'monthly_return_google_rate',
				monthly_statistics.return_longe_rate 'monthly_return_longe_rate',
				monthly_statistics.return_quick_rate 'monthly_return_quick_rate',
				monthly_statistics.return_rate 'monthly_return_rate'
			FROM statistics
			LEFT JOIN weekly_statistics 
				ON statistics.game_id = weekly_statistics.game_id 
				AND statistics.date = weekly_statistics.date 
			LEFT JOIN monthly_statistics 
				ON statistics.game_id = monthly_statistics.game_id 
				AND statistics.date = monthly_statistics.date 
			WHERE statistics.date BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND statistics.game_id = '{$game_id}'
		    ORDER BY statistics.date DESC
		");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function marketing()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");
		if (empty($this->input->get_post("start_date")) && empty($this->input->get_post("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		
        switch($span) {
			case "weekly":
                $select = "s.date 'date'";
                $s_select = "YEARWEEK((date),3) 'date'";
                $group = "YEARWEEK((date),3)";
                $order = "s.date DESC";
				break;
			
			case "monthly":
                $select = "s.year 'year',
                           s.date 'date'";
                $s_select = "YEAR(date) 'year',
                           MONTH(date) 'date'";
                $group = "YEAR(date),
                          MONTH(date)";
                $order = "s.year DESC,
                          s.date DESC";
				break;
				
			default:
                $select = "s.date 'date'";
                $s_select = "date";
                $group = "date";
                $order = "s.date DESC";
				break;
		}
        
        $query = $this->DB2->query("
            SELECT 
                {$select},
                s.game_id,
                s.login_count 'login_count',
                s.new_login_count 'new_login_count',
                s.device_count 'device_count',
                apk_ms.login_count 'apk_login_count',
                ios_ms.install_count 'ios_download_count',
                ios_ms.tw_install_count 'ios_tw_download_count',
                ios_ms.hk_install_count 'ios_hk_download_count',
                ios_ms.mo_install_count 'ios_mo_download_count',
                ios_ms.sg_install_count 'ios_sg_download_count',
                ios_ms.my_install_count 'ios_my_download_count',
                google_ms.install_count 'google_download_count',
                google_ms.tw_install_count 'google_tw_download_count',
                google_ms.hk_install_count 'google_hk_download_count',
                google_ms.mo_install_count 'google_mo_download_count',
                google_ms.sg_install_count 'google_sg_download_count',
                google_ms.my_install_count 'google_my_download_count',
                apk_ms.install_count 'apk_download_count',
                apk_ms.tw_install_count 'apk_tw_download_count',
                apk_ms.hk_install_count 'apk_hk_download_count',
                apk_ms.mo_install_count 'apk_mo_download_count',
                apk_ms.sg_install_count 'apk_sg_download_count',
                apk_ms.my_install_count 'apk_my_download_count'
            FROM
            (
                SELECT {$s_select}, game_id, SUM(login_count) 'login_count', SUM(new_login_count) 'new_login_count', SUM(device_count) 'device_count'
                FROM statistics
                WHERE date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS s
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'ios'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS ios_ms ON s.date=ios_ms.date ".(($span=='monthly')?"AND s.year=ios_ms.year":"")."
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'google'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS google_ms ON s.date=google_ms.date ".(($span=='monthly')?"AND s.year=google_ms.year":"")."
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(af_login) 'login_count', 
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'apk'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS apk_ms ON s.date=apk_ms.date ".(($span=='monthly')?"AND s.year=apk_ms.year":"")."
            ORDER BY {$order}
        ");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("span", $span)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function deposit_behavior()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		
		$deposit_amount_query = $this->DB2->query("
            SELECT 
				SUM(CASE WHEN amount>=100001 THEN 1 ELSE 0 END) 'lvl6',
				SUM(CASE WHEN amount BETWEEN 20000 AND 100000 THEN 1 ELSE 0 END) 'lvl5',
				SUM(CASE WHEN amount BETWEEN 5000 AND 19999 THEN 1 ELSE 0 END) 'lvl4',
				SUM(CASE WHEN amount BETWEEN 1500 AND 4999 THEN 1 ELSE 0 END) 'lvl3',
				SUM(CASE WHEN amount BETWEEN 600 AND 1499 THEN 1 ELSE 0 END) 'lvl2',
				SUM(CASE WHEN amount BETWEEN 60 AND 599 THEN 1 ELSE 0 END) 'lvl1'
            FROM
                user_billing
            WHERE
                create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
                AND billing_type = 2
                AND result = 1
		")->row();
		
		$this->load->library('jpgraph');
		$jgraph_data = array($deposit_amount_query->lvl6, $deposit_amount_query->lvl5, $deposit_amount_query->lvl4, $deposit_amount_query->lvl3, $deposit_amount_query->lvl2, $deposit_amount_query->lvl1);
		$jgraph_labels = array('LV6($100,001+)', 'LV5($20,000~$100,000)', 'LV4($5,000~$19,999)', 'LV3($1,500~$4,999)', 'LV2($600~$1,499)', 'LV1($60~$599)');
        
		$settings = array('horizontal' => true , 'y_label_width' => 150);
        
		$deposit_amount_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/deposit_amount_graph', $settings);
		
		$deposit_count_query = $this->DB2->query("
			SELECT
				deposit_count, COUNT(deposit_count) 'deposit_count_rate'
			FROM
			(
				SELECT 
					COUNT(create_time) 'deposit_count'
				FROM
					user_billing
				WHERE
					create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
					AND billing_type = 2
					AND result = 1
				GROUP BY uid
			) tmp
			GROUP BY deposit_count
			ORDER BY deposit_count DESC
		");
		
		//$this->load->library('jpgraph');
		$jgraph_data = array();
		$jgraph_labels = array();
		
		$deposit_great_count = 0;
		
		foreach($deposit_count_query->result() as $row) {
			if ($row->deposit_count >= 10) {
				$deposit_great_count += $row->deposit_count;
			} else {
				$jgraph_data[] = $row->deposit_count_rate;
				$jgraph_labels[] = (string)$row->deposit_count;
			}
		}
		
		if ($deposit_great_count) {
			array_unshift($jgraph_data, $deposit_great_count);
			array_unshift($jgraph_labels, "10+");
		}
        
		$deposit_count_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/deposit_count_graph', $settings);
		
		$region_count_query = $this->DB2->query("
			SELECT 
				country_code, COUNT(create_time) 'region_count'
			FROM
				user_billing
			WHERE
				create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND billing_type = 2
				AND result = 1
			GROUP BY country_code
		");
		
		//$this->load->library('jpgraph');
		$jgraph_data = array();
		$jgraph_labels = array();
		
		foreach($region_count_query->result() as $row) {
			$jgraph_data[] = $row->region_count;
			$jgraph_labels[] = (string)$row->country_code;
		}
		
		$region_count_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/jpgraphs/region_count_graph', $settings);
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->set("deposit_count_graph",  isset($deposit_count_graph) ? $deposit_count_graph : false)
			->set("region_count_graph",  isset($region_count_graph) ? $region_count_graph : false)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	    
    function user_online() {
		$this->zacl->check_login(true);
		
		$this->zacl->check("game_statistics", "read");
		
		$this->_init_layout();
		$this->load->helper("output_table");
				
        header("Cache-Control: private");	
        
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");
        
        $prev_date = date("Y-m-d", strtotime($start_date)-24*60*60);
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        
        $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
        
        $online_query = new MongoDB\Driver\Query(['game_id' => $game_id, 'date' => ['$gte' => $prev_date, '$lte' => $start_date]]);
        
        $online_cursor = $manager->executeQuery("longe_log.user_online", $online_query);

        $sharp = [];
        $peak = [];
        
        foreach ($online_cursor as $document) {
            if (!isset($sharp[$document->date][$document->hour])) $sharp[$document->date][$document->hour] = 0;
            if (!isset($peak[$document->date][$document->hour])) $peak[$document->date][$document->hour] = 0;
            if (isset($document->sharp)) $sharp[$document->date][$document->hour] += $document->sharp;
            if (isset($document->peak)) $peak[$document->date][$document->hour] += $document->peak;
        }
        
        $high_peak = 0;
        $bi_peak = array();
        
        if (isset($peak[$start_date])) {
            foreach ($peak[$start_date] as $hour_peak) {
                if ($hour_peak > $high_peak) $high_peak = $hour_peak;
            }
            
            if (isset($peak[$start_date][0]) && isset($peak[$prev_date][23])) {
                $bi_peak[0] = ($peak[$start_date][0]>$peak[$prev_date][23])?$peak[$start_date][0]:$peak[$prev_date][23];
            } elseif (!isset($peak[$start_date][0]) && isset($peak[$prev_date][23])) {
                $bi_peak[0] = $peak[$prev_date][23];
            } elseif (isset($peak[$start_date][0]) && !isset($peak[$prev_date][23])) {
                $bi_peak[0] = $peak[$start_date][0];
            } else {
                $bi_peak[0] = 0;
            }
            
            for ($h=2;$h<24;$h+=2) {
                if (isset($peak[$start_date][$h]) && isset($peak[$start_date][$h-1])) {
                    $bi_peak[$h] = ($peak[$start_date][$h]>$peak[$start_date][$h-1])?$peak[$start_date][$h]:$peak[$start_date][$h-1];
                } elseif (!isset($peak[$start_date][$h]) && isset($peak[$start_date][$h-1])) {
                    $bi_peak[$h] = $peak[$start_date][$h-1];
                } elseif (isset($peak[$start_date][$h]) && !isset($peak[$start_date][$h-1])) {
                    $bi_peak[$h] = $peak[$start_date][$h];
                } else {
                    $bi_peak[$h] = 0;
                }
            }
        }
        
        $count_query = new MongoDB\Driver\Query(['game_id' => $game_id]);
        
        $count_cursor = $manager->executeQuery("longe_log.user_count", $count_query);
        
        $user_count = 0;
        
        foreach ($count_cursor as $document) {
            $user_count += $document->count;
        }
        
		$this->g_layout
			->add_breadcrumb("即時在線")	
			->set("sharp", $sharp)
			->set("bi_peak", $bi_peak)
			->set("high_peak", $high_peak)
			->set("user_count", $user_count)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("prev_date", $prev_date)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
    }
	
	function whale_users()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT 
				whales.uid 'uid',
				chr.name 'character_name',
				whales.server_name 'server_name',
				whales.deposit_total 'deposit_total',
				gm.exchange_rate*whales.deposit_total 'currency_total',
				DATE(chr.create_time) 'create_date',
				csm.consume_sum 'currency_consumed'
			FROM
				(	
					SELECT 
						ub.uid 'uid',
						ub.server_id 'server_id',
						svr.game_id 'game_id',
						svr.name 'server_name',
						SUM(ub.amount) 'deposit_total'
					FROM
						user_billing ub
						JOIN servers svr ON svr.server_id = ub.server_id
						LEFT JOIN testaccounts ta ON ub.uid = ta.uid
					WHERE
						ub.billing_type = 2
						AND ub.result = 1
						AND svr.game_id = '{$game_id}'
						AND ta.uid IS NULL
					GROUP BY ub.uid
                    HAVING SUM(ub.amount) >= 5000
					ORDER BY SUM(ub.amount) DESC
				) whales
					JOIN games gm ON whales.game_id = gm.game_id
					LEFT JOIN 
				( 
					SELECT
						uid,
						server_id,
						MIN(create_time) 'create_time',
						name
					FROM characters
					GROUP BY server_id, uid
				) chr ON chr.uid = whales.uid
						AND chr.server_id = whales.server_id
					LEFT JOIN
				(
					SELECT
						uid,
						server_id,
						SUM(amount) 'consume_sum'
					FROM
						log_game_consumes
					WHERE
						game_id = '{$game_id}'
					GROUP BY server_id, uid
				) csm ON csm.uid = whales.uid
					AND csm.server_id = whales.server_id
		");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	    
    function level_analysis() {
		$this->zacl->check_login(true);
		
		$this->zacl->check("level_analysis", "read");
		
		$this->_init_layout();
		$this->load->helper("output_table");
				
		if ($this->input->get("action") && $this->input->get("game")) 
		{
			header("Cache-Control: private");	
            
            $game = $this->config->item("game");
            
			$start_date = ($this->input->get("start_date"))?strtotime($this->input->get("start_date")):0;
			$end_date = ($this->input->get("end_date"))?strtotime($this->input->get("end_date")):time();
            
            $this->load->config('g_mongodb');
            $g_mongodb = $this->config->item('mongo_db');
            
            $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
            
            try {
            
                $itemuse_command = new MongoDB\Driver\Command([
                    'aggregate' => 'le_UserItemUse',
                    'pipeline' => [
                        [
                            '$match' => ['game_id' => $this->input->get("game"), 'le_logTime' => ['$gte' => $start_date, '$lte' => $end_date]],
                        ],
                        [
                            '$group' => [
                                '_id' => ['game_id' => '$game_id', 'le_contentId' => '$le_contentId', 'le_contentType' => '$le_contentType'],
                                'le_count' => ['$sum' => '$le_count']
                            ]
                        ],
                    ],
                    'cursor' => new stdClass,
                ]);
                $itemuse_cursor = $manager->executeCommand('longe_log', $itemuse_command);
                
                $itemget_command = new MongoDB\Driver\Command([
                    'aggregate' => 'le_UserItemGet',
                    'pipeline' => [
                        [
                            '$match' => ['game_id' => $this->input->get("game"), 'le_logTime' => ['$gte' => $start_date, '$lte' => $end_date]],
                        ],
                        [
                            '$group' => [
                                '_id' => ['game_id' => '$game_id', 'le_contentId' => '$le_contentId', 'le_contentType' => '$le_contentType', 'le_price' => '$le_price'],
                                'le_count' => ['$sum' => '$le_count']
                            ]
                        ],
                    ],
                    'cursor' => new stdClass,
                ]);
                $itemget_cursor = $manager->executeCommand('longe_log', $itemget_command);

                $itemget_result = [];
                
                foreach ($itemget_cursor as $itemget_document) {
                    $itemget_document->used=0;
                    foreach ($itemuse_cursor as $itemuse_document) {
                        if ($itemuse_document->_id->le_contentId==$itemget_document->_id->le_contentId) $itemget_document->used=$itemuse_document->le_count;
                    }
                    $itemget_result[] = $itemget_document;
                }
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo $e->getMessage(), "\n";
            }
					
            $this->load->library('pagination');
            $this->pagination->initialize(array(
                    'base_url'	=> site_url("statistics/level_analysis"),
                    'total_rows'=> isset($itemget_result) ? count($itemget_result) : 0,
                    'per_page'	=> 100
                ));			
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'display_game' => 'game',
			);
			$_GET = $default_value;
		}
		
		$games = $this->DB2->get("games");
		$servers = $this->DB2->order_by("server_id")->get("servers");		
			
		$this->g_layout
			->add_breadcrumb("等級分析")	
			->set("games", $games)
			->set("servers", $servers)	
			->set("query", isset($itemget_result) ? $itemget_result : false)
			->add_js_include("statistics/level_analysis")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();	
    }
    
	function appsflyer()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		//$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->where("date >=", $start_date)->where("date <=", $end_date)->order_by("date", "desc")->order_by("game_id", "asc")->order_by("platform", "asc")->order_by("country_code", "asc")->order_by("media", "asc")->get("marketing_statistics");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			//->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
    
	function facebook_installs()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT
                date,
                install_count,
                platform,
                
		");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */