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
	
	function retention()
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
			->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->set("span", $span)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function game_length()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");

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
					new_login_count,
					new_login_count_15,
					new_login_count_30,
					new_login_count_60,
					new_login_count_90,
					new_login_count_120,
					new_login_count_more,
					login_count,
					login_count_15,
					login_count_30,
					login_count_60,
					login_count_90,
					login_count_120,
					login_count_more,
					deposit_login_count,
					deposit_login_count_15,
					deposit_login_count_30,
					deposit_login_count_60,
					deposit_login_count_90,
					deposit_login_count_120,
					deposit_login_count_more,
					new_deposit_login_count,
					new_deposit_login_count_15,
					new_deposit_login_count_30,
					new_deposit_login_count_60,
					new_deposit_login_count_90,
					new_deposit_login_count_120,
					new_deposit_login_count_more
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
					SUM(new_login_count) 'new_login_count',
					SUM(new_login_count_15) 'new_login_count_15',
					SUM(new_login_count_30) 'new_login_count_30',
					SUM(new_login_count_60) 'new_login_count_60',
					SUM(new_login_count_90) 'new_login_count_90',
					SUM(new_login_count_120) 'new_login_count_120',
					SUM(new_login_count_more) 'new_login_count_more',
					SUM(login_count) 'login_count',
					SUM(login_count_15) 'login_count_15',
					SUM(login_count_30) 'login_count_30',
					SUM(login_count_60) 'login_count_60',
					SUM(login_count_90) 'login_count_90',
					SUM(login_count_120) 'login_count_120',
					SUM(login_count_more) 'login_count_more',
					SUM(deposit_login_count) 'deposit_login_count',
					SUM(deposit_login_count_15) 'deposit_login_count_15',
					SUM(deposit_login_count_30) 'deposit_login_count_30',
					SUM(deposit_login_count_60) 'deposit_login_count_60',
					SUM(deposit_login_count_90) 'deposit_login_count_90',
					SUM(deposit_login_count_120) 'deposit_login_count_120',
					SUM(deposit_login_count_more) 'deposit_login_count_more',
					SUM(new_deposit_login_count) 'new_deposit_login_count',
					SUM(new_deposit_login_count_15) 'new_deposit_login_count_15',
					SUM(new_deposit_login_count_30) 'new_deposit_login_count_30',
					SUM(new_deposit_login_count_60) 'new_deposit_login_count_60',
					SUM(new_deposit_login_count_90) 'new_deposit_login_count_90',
					SUM(new_deposit_login_count_120) 'new_deposit_login_count_120',
					SUM(new_deposit_login_count_more) 'new_deposit_login_count_more'
				FROM statistics
				WHERE game_id = '{$game_id}'
				AND date BETWEEN '{$start_date}' AND '{$end_date}'
				GROUP BY game_id, YEAR(date), {$date_group}
				ORDER BY YEAR(date) DESC, {$date_group} DESC
			");
		}
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("span", $span)
		
		->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
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
			$start_date = date("Y-m-d",strtotime("-1 days"));
			$end_date = date("Y-m-d",strtotime("-8 days"));
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
			    stc.new_character_count 'new_character_count',
			    stc.device_count 'device_count',
			    stc2.login_count 'y_login_count',
			    stc2.new_login_count 'y_new_login_count',
			    stc2.one_retention_all_count 'y_one_retention_all_count',
                stc2.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.new_deposit_user_count 'new_deposit_user_count',
                stc.consume_user_count 'consume_user_count',
                stc.new_consume_user_count 'new_consume_user_count',
                stc.currency_total 'currency_total',
                stc.paid_currency_total 'paid_currency_total',
                stc.deposit_total 'deposit_total',
                stc.consume_total 'consume_total',
                stc.peak_user_count 'peak_user_count',
                stc.total_time 'total_time',
                stc.paid_total_time 'paid_total_time'
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
			    stc.new_character_count 'new_character_count',
			    stc.device_count 'device_count',
			    stc2.login_count 'y_login_count',
			    stc2.new_login_count 'y_new_login_count',
			    stc2.one_retention_all_count 'y_one_retention_all_count',
                stc2.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.new_deposit_user_count 'new_deposit_user_count',
                stc.consume_user_count 'consume_user_count',
                stc.new_consume_user_count 'new_consume_user_count',
                stc.currency_total 'currency_total',
                stc.paid_currency_total 'paid_currency_total',
                stc.deposit_total 'deposit_total',
                stc.consume_total 'consume_total',
                stc.peak_user_count 'peak_user_count',
                stc.total_time 'total_time',
                stc.paid_total_time 'paid_total_time'
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
			    SUM(stc.new_character_count) 'new_character_count',
			    SUM(stc.device_count) 'device_count',
			    SUM(stc2.login_count) 'y_login_count',
			    SUM(stc2.new_login_count) 'y_new_login_count',
			    SUM(stc3.one_retention_all_count) 'y_one_retention_all_count',
                SUM(stc3.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.new_deposit_user_count) 'new_deposit_user_count',
                SUM(stc.consume_user_count) 'consume_user_count',
                SUM(stc.new_consume_user_count) 'new_consume_user_count',
                SUM(stc.currency_total) 'currency_total',
                SUM(stc.paid_currency_total) 'paid_currency_total',
                SUM(stc.deposit_total) 'deposit_total',
                SUM(stc.consume_total) 'consume_total',
                SUM(stc.peak_user_count) 'peak_user_count',
                SUM(stc.total_time) 'total_time',
                SUM(stc.paid_total_time) 'paid_total_time'
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
			    SUM(stc.new_character_count) 'new_character_count',
			    SUM(stc.device_count) 'device_count',
			    SUM(stc2.login_count) 'y_login_count',
			    SUM(stc2.new_login_count) 'y_new_login_count',
			    SUM(stc3.one_retention_all_count) 'y_one_retention_all_count',
                SUM(stc3.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.new_deposit_user_count) 'new_deposit_user_count',
                SUM(stc.consume_user_count) 'consume_user_count',
                SUM(stc.new_consume_user_count) 'new_consume_user_count',
                SUM(stc.currency_total) 'currency_total',
                SUM(stc.paid_currency_total) 'paid_currency_total',
                SUM(stc.deposit_total) 'deposit_total',
                SUM(stc.consume_total) 'consume_total',
                SUM(stc.peak_user_count) 'peak_user_count',
                SUM(stc.total_time) 'total_time',
                SUM(stc.paid_total_time) 'paid_total_time'
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
					stc3.one_retention_all_count 'y_one_retention_all_count',
					stc3.one_retention_count 'y_one_retention_count'
				FROM
					(
						SELECT 
							YEAR(stc.date) 'year',
							MONTH(stc.date) 'date',
							stc.game_id 'game_id',
							SUM(stc.login_count) 'login_count',
							SUM(stc.new_login_count) 'new_login_count',
							SUM(stc.new_character_count) 'new_character_count',
							SUM(stc.device_count) 'device_count',
							SUM(stc.deposit_user_count) 'deposit_user_count',
							SUM(stc.new_deposit_user_count) 'new_deposit_user_count',
							SUM(stc.consume_user_count) 'consume_user_count',
							SUM(stc.new_consume_user_count) 'new_consume_user_count',
							SUM(stc.currency_total) 'currency_total',
							SUM(stc.paid_currency_total) 'paid_currency_total',
							SUM(stc.deposit_total) 'deposit_total',
							SUM(stc.consume_total) 'consume_total',
							SUM(stc.peak_user_count) 'peak_user_count',
							SUM(stc.total_time) 'total_time',
							SUM(stc.paid_total_time) 'paid_total_time'
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
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("span", $span)
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
			$start_date = date("Y-m-d",strtotime("-1 days"));
			$end_date = date("Y-m-d",strtotime("-8 days"));
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
				{$date_group}(create_time) 'date',
				SUM(amount) 'sum',
				SUM(CASE WHEN transaction_type='ios_billing' THEN amount ELSE 0 END) 'ios_sum',
				SUM(CASE WHEN transaction_type='android_billing' THEN amount ELSE 0 END) 'android_sum',
				SUM(CASE WHEN transaction_type='gash_billing' THEN amount ELSE 0 END) 'gash_sum',
				SUM(CASE WHEN transaction_type='mycard_billing' THEN amount ELSE 0 END) 'mycard_sum',
				SUM(CASE WHEN transaction_type='paypal_billing' THEN amount ELSE 0 END) 'paypal_sum',
				SUM(CASE WHEN transaction_type='atm_billing' THEN amount ELSE 0 END) 'atm_sum',
				SUM(CASE WHEN transaction_type='cht_billing' THEN amount ELSE 0 END) 'cht_sum',
				SUM(CASE WHEN transaction_type='twm_billing' THEN amount ELSE 0 END) 'twm_sum',
				SUM(CASE WHEN transaction_type='fet_billing' THEN amount ELSE 0 END) 'fet_sum',
				SUM(CASE WHEN transaction_type='vibo_billing' THEN amount ELSE 0 END) 'vibo_sum',
				SUM(CASE WHEN transaction_type not in ('ios_billing','android_billing','gash_billing','mycard_billing','paypal_billing','atm_billing','cht_billing','twm_billing','fet_billing','vibo_billing') THEN amount ELSE 0 END) 'other_billing_sum',
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
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
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
			$start_date = date("Y-m-d",strtotime("-1 days"));
			$end_date = date("Y-m-d",strtotime("-8 days"));
		} 
		
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
					AND billing_type = 1
					AND result = 1
				GROUP BY uid
			) tmp
			GROUP BY deposit_count
			ORDER BY deposit_count DESC
		");
		
		$this->load->library('jpgraph');
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
		
		$deposit_count_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/deposit_count_graph');
		
		$region_count_query = $this->DB2->query("
			SELECT 
				country_code, COUNT(create_time) 'region_count'
			FROM
				user_billing
			WHERE
				create_time BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND billing_type = 1
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
		
		$region_count_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/region_count_graph');
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->set("deposit_count_graph",  isset($deposit_count_graph) ? $deposit_count_graph : false)
			->set("region_count_graph",  isset($region_count_graph) ? $region_count_graph : false)
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
					ORDER BY SUM(ub.amount) DESC
					LIMIT 20
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
	
	function user_return()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		if (empty($this->input->get("start_date")) && empty($this->input->get("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-1 days"));
			$end_date = date("Y-m-d",strtotime("-8 days"));
		} 
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT
				statistics.date,
				statistics.game_id,
				statistics.one_return_count 'one_return_count',
				(statistics.one_return_count*100/statistics.login_count) 'one_return_percentage',
				statistics.three_return_count 'three_return_count',
				(statistics.three_return_count*100/statistics.login_count) 'three_return_percentage',
				weekly_statistics.return_count 'weekly_return_count',
				(weekly_statistics.return_count*100/weekly_statistics.login_count) 'weekly_return_percentage',
				monthly_statistics.return_count 'monthly_return_count',
				(monthly_statistics.return_count*100/monthly_statistics.login_count) 'monthly_return_percentage'
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
		
		$region_graph = $this->jpgraph->bar_chart($jgraph_data, $jgraph_labels, dirname(__FILE__).'/../../p/region_graph');
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("region_graph",  isset($region_graph) ? $region_graph : false)
			->set("game_id", $game_id)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */