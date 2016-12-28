<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_statistics extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();					
		
		error_reporting(E_ALL);
		ini_set('display_errors','On');		
			
    	$query = $this->DB2->select("uid")->from("testaccounts")->get();

        $testaccounts = array();
        
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $testaccounts[] = $row->uid;
		    }
		}
        
        $testaccounts_str = implode(",", $testaccounts);
        $this->testaccounts = $testaccounts_str;
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
				FROM user_statistics
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
				FROM user_statistics
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
			->render("user_statistics/game_length");
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
				FROM user_statistics
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
				FROM user_statistics
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
			->render("user_statistics/game_length");
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
				FROM user_statistics
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
				FROM user_statistics
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
			->render("user_statistics/game_length");
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
				FROM user_statistics
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
				FROM user_statistics
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
			->render("user_statistics/game_length");
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
                    AND ub.create_time BETWEEN DATE('{$start_date}') AND DATE_ADD(DATE('{$end_date}'), INTERVAL 1 DAY)
                    AND ub.billing_type = 1
                    AND ub.result = 1
                    ".(($this->testaccounts)?" AND ub.uid NOT IN (".$this->testaccounts.") ":"")."
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
		    FROM user_statistics 
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
				YEAR(ub.create_time) 'year',
				{$date_group}(ub.create_time) 'date',
				SUM(ub.amount) 'sum',
				SUM(CASE WHEN ub.transaction_type='inapp_billing_ios' THEN amount ELSE 0 END) 'ios_sum',
				SUM(CASE WHEN ub.transaction_type='inapp_billing_google' THEN amount ELSE 0 END) 'android_sum',
				SUM(CASE WHEN ub.transaction_type='mycard_billing' THEN amount ELSE 0 END) 'mycard_sum',
				SUM(CASE WHEN ub.transaction_type='vip_billing' THEN amount ELSE 0 END) 'vip_sum',
				SUM(CASE WHEN ub.country_code='TWN' THEN ub.amount ELSE 0 END) 'twn_sum',
				SUM(CASE WHEN ub.country_code='HKG' THEN ub.amount ELSE 0 END) 'hkg_sum',
				SUM(CASE WHEN ub.country_code='MAC' THEN ub.amount ELSE 0 END) 'mac_sum',
				SUM(CASE WHEN ub.country_code='SGP' THEN ub.amount ELSE 0 END) 'sgp_sum',
				SUM(CASE WHEN ub.country_code='MYS' THEN ub.amount ELSE 0 END) 'mys_sum',
				SUM(CASE WHEN ub.country_code not in ('TWN','HKG','MAC','SGP','MYS') THEN ub.amount ELSE 0 END) 'other_country_sum'
			FROM user_billing ub
				JOIN servers s ON ub.server_id=s.server_id
			WHERE ub.create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND ub.billing_type = 1
				AND ub.result = 1 
				AND s.is_test_server=0 
				".(($this->testaccounts)?" AND ub.uid NOT IN (".$this->testaccounts.") ":"")."
			GROUP BY YEAR(ub.create_time), {$date_group}(ub.create_time)
		    ORDER BY YEAR(ub.create_time) DESC, {$date_group}(ub.create_time) DESC
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
            case "MyCard":
                $sum_type = 'mycard_sum';
                break;
            case "其他儲點":
                $sum_type = 'vip_sum';
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
	
	function new_users()
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
		    FROM user_statistics 
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
	
	function new_users_by_login()
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
		    FROM user_statistics 
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
        
        $has_data = $facebook_count+$google_count+$longe_count+$quick_count;
        
        if ($has_data) {
            $this->load->library('jpgraph');
            $jgraph_data = array();
            $jgraph_labels = array();
            $jgraph_data[]=($facebook_count); $jgraph_labels[]="FB\n(%.1f%%)";
            $jgraph_data[]=($google_count); $jgraph_labels[]="Google\n(%.1f%%)";
            $jgraph_data[]=($longe_count); $jgraph_labels[]="Longe\n(%.1f%%)";
            $jgraph_data[]=($quick_count); $jgraph_labels[]="Quick\n(%.1f%%)";

            $deposit_pie_chart = $this->jpgraph->pie_chart($jgraph_data, $jgraph_labels, "", dirname(__FILE__).'/../../p/jpgraphs/user_new_by_login_pie_chart');
        }
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("has_data", $has_data)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
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
	    
    function online() {
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
		
		$this->zacl->check("whale_users_statistics", "read");
		
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
    function user_count_by_country()
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
        $datetime1 = date_create($start_date);
        $datetime2 = date_create($end_date);

        $diff =date_diff($datetime1 ,$datetime2)->format("%d");  //算出幾天
        
        $tmQuery="SUM(CASE WHEN create_time='$start_date' THEN 1 ELSE 0 END) '$start_date'";


        for ($x = 0; $x < $diff; $x++) {


            $day  = new DateInterval('P1D');
            $tmp_date=date_format(date_add($datetime1,$day),'Y-m-d');
            $tmQuery.=",";
            $tmQuery.="SUM(CASE WHEN create_time='$tmp_date' THEN 1 ELSE 0 END) '$tmp_date'";
            
        } 
        
        $qString ="SELECT country as '國家',
        {$tmQuery}
        FROM
        (SELECT a.create_time,a.uid,b.country
        FROM
        (SELECT distinct Date_format(create_time,'%Y-%m-%d') AS create_time,uid 
        FROM log_game_logins 
        WHERE game_id='{$game_id}' and create_time BETWEEN '{$start_date}' AND '{$end_date} 23:59:59') a 
        INNER JOIN user_info b on a.uid=b.uid ) tmp
        GROUP BY country order by 2 desc";
        
        $query = $this->DB2->query($qString);
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
            ->set("qString", isset($qString) ? $qString : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("span", $span)
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();

	}
    
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */