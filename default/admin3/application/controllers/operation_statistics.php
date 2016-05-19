<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operation_statistics extends MY_Controller {
	
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
						s.date AS 'find_date',
						SUM(s.login_count) 'login_count',
						SUM(s.new_login_count) 'new_login_count',
						SUM(s.device_count) 'device_count',
						SUM(s.deposit_user_count) 'deposit_user_count',
						SUM(s.new_deposit_user_count) 'new_deposit_user_count',
						SUM(s.deposit_total) 'deposit_total',
						SUM(s.consume_total) 'consume_total',
						SUM(s.peak_user_count) 'peak_user_count',
						SUM(s.total_time) 'total_time',
						SUM(os.one_ltv) 'one_ltv'
					FROM user_statistics s
                    LEFT JOIN operation_statistics os ON s.game_id=os.game_id AND s.date=os.date
					WHERE s.date = '{$d}'
					GROUP BY s.date
				) main,
				(
					SELECT 
						SUM(s.new_login_count) 'new_login_count_1',
						SUM(os.one_retention_all_count) 'one_retention_all_count',
						SUM(os.one_retention_count) 'one_retention_count'
					FROM user_statistics s
                    LEFT JOIN operation_statistics os ON s.game_id=os.game_id AND s.date=os.date
					WHERE s.date = '{$d_1}'
					GROUP BY s.date
				) main_1,
				(
					SELECT 
						SUM(s.new_login_count) 'new_login_count_7',
						SUM(os.seven_retention_count) 'seven_retention_count'
					FROM user_statistics s
                    LEFT JOIN operation_statistics os ON s.game_id=os.game_id AND s.date=os.date
					WHERE s.date = '{$d_7}'
					GROUP BY s.date
				) main_7,
				(
					SELECT
						SUM(new_login_count) 'total_users',
						SUM(deposit_total) 'historical_revenue_sum',
						SUM(new_deposit_user_count) 'historical_deposit_user_count'
					FROM
						user_statistics
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
	
	function lifetime_value()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get("span");
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->select("os.*, us.new_login_count")
            ->from("operation_statistics os")
            ->join("user_statistics us", "os.game_id=us.game_id and os.date=us.date")
            ->where("os.game_id", $game_id)
            ->where("os.date >=", $start_date)
            ->where("os.date <=", $end_date)->get();
        
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
	
	function overview()
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
				break;
			
			case "monthly":
			    $date_group = 'MONTH';
				$interval = 'MONTH';
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
                stc3.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.deposit_total 'deposit_total',
                stc.new_deposit_user_count 'new_user_deposit_count',
                stc.new_user_deposit_total 'new_user_deposit_total'
		    FROM user_statistics stc
		    LEFT JOIN user_statistics stc2 ON stc.game_id=stc2.game_id AND stc.date=DATE_ADD(stc2.date, interval 1 day)
		    LEFT JOIN operation_statistics stc3 ON stc.game_id=stc3.game_id AND stc.date=DATE_ADD(stc3.date, interval 1 day)
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
                stc3.one_retention_count 'y_one_retention_count',
                stc.deposit_user_count 'deposit_user_count',
                stc.deposit_total 'deposit_total',
                stc.new_deposit_user_count 'new_user_deposit_count',
                stc.new_user_deposit_total 'new_user_deposit_total'
		    FROM user_statistics stc
		    RIGHT JOIN user_statistics stc2 ON stc.game_id=stc2.game_id AND stc.date=DATE_ADD(stc2.date, interval 1 day)
		    RIGHT JOIN operation_statistics stc3 ON stc.game_id=stc3.game_id AND stc.date=DATE_ADD(stc3.date, interval 1 day)
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
                SUM(stc4.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.deposit_total) 'deposit_total',
			    SUM(stc3.new_user_deposit_count) 'new_user_deposit_count',
                SUM(stc3.new_user_deposit_total) 'new_user_deposit_total'
		    FROM user_statistics stc
		    LEFT JOIN user_statistics stc2 ON stc.game_id=stc2.game_id 
				AND stc.date=DATE_ADD(stc2.date, interval 1 week)
			LEFT JOIN weekly_user_statistics stc3 ON stc3.game_id=stc2.game_id 
				AND stc3.date=stc2.date
			LEFT JOIN weekly_operation_statistics stc4 ON stc4.game_id=stc2.game_id 
				AND stc4.date=stc2.date
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
                SUM(stc4.one_retention_count) 'y_one_retention_count',
                SUM(stc.deposit_user_count) 'deposit_user_count',
                SUM(stc.deposit_total) 'deposit_total',
			    SUM(stc3.new_user_deposit_count) 'new_user_deposit_count',
                SUM(stc3.new_user_deposit_total) 'new_user_deposit_total'
		    FROM statistics stc
		    RIGHT JOIN statistics stc2 ON stc.game_id=stc2.game_id 
				AND stc.date=DATE_ADD(stc2.date, interval 1 week)
			LEFT JOIN weekly_statistics stc3 ON stc3.game_id=stc2.game_id 
				AND stc3.date=stc2.date
			LEFT JOIN weekly_operation_statistics stc4 ON stc4.game_id=stc2.game_id 
				AND stc4.date=stc2.date
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
					stc4.one_retention_count 'y_one_retention_count',
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
							user_statistics stc
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
							user_statistics
						WHERE
							game_id = '{$game_id}'
								AND date BETWEEN '{$stc2_start_date}' AND '{$stc2_end_date}'
						GROUP BY YEAR(date) , MONTH(date)
					) stc2 ON stc.game_id = stc2.game_id
						AND stc2.year = YEAR(DATE_ADD(CONCAT(stc.year, '-', stc.date, '-01'), INTERVAL - 1 MONTH))
						AND stc2.date = MONTH(DATE_ADD(CONCAT(stc.year, '-', stc.date, '-01'), INTERVAL - 1 MONTH))
						LEFT JOIN
					monthly_user_statistics stc3 ON stc3.game_id = stc2.game_id
						AND YEAR(stc3.date) = stc2.year
						AND MONTH(stc3.date) = stc2.date
						LEFT JOIN
					monthly_operation_statistics stc4 ON stc4.game_id = stc2.game_id
						AND YEAR(stc4.date) = stc2.year
						AND MONTH(stc4.date) = stc2.date
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
	
	function user_retention()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->select("os.*, us.new_login_count, us.new_character_count")
            ->from("operation_statistics os")
            ->join("user_statistics us", "os.game_id=us.game_id and os.date=us.date")
            ->where("os.game_id", $game_id)
            ->where("os.date >=", $start_date)
            ->where("os.date <=", $end_date)->order_by("os.date", "desc")->get();
        
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
					
		$query = $this->DB2->select("
                os.*, 
                us.new_login_count, 
                us.new_login_facebook_count, 
                us.new_login_google_count, 
                us.new_login_quick_count, 
                us.new_login_longe_count, 
                us.new_character_count, 
                us.new_character_facebook_count, 
                us.new_character_google_count, 
                us.new_character_quick_count, 
                us.new_character_longe_count")
            ->from("operation_statistics os")
            ->join("user_statistics us", "os.game_id=us.game_id and os.date=us.date")
            ->where("os.game_id", $game_id)
            ->where("os.date >=", $start_date)
            ->where("os.date <=", $end_date)->order_by("os.date", "desc")->get();
        
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
				os.date,
				os.game_id,
				os.one_return_count 'one_return_count',
				os.one_return_rate 'one_return_rate',
				os.three_return_count 'three_return_count',
				os.three_return_rate 'three_return_rate',
				wos.return_count 'weekly_return_count',
				wos.return_rate 'weekly_return_rate',
				mos.return_count 'monthly_return_count',
				mos.return_rate 'monthly_return_rate'
			FROM operation_statistics os
			LEFT JOIN weekly_operation_statistics wos
				ON os.game_id = wos.game_id 
				AND os.date = wos.date 
			LEFT JOIN monthly_operation_statistics mos
				ON os.game_id = mos.game_id 
				AND os.date = mos.date 
			WHERE os.date BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND os.game_id = '{$game_id}'
		    ORDER BY os.date DESC
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
				os.date,
				os.game_id,
				os.one_return_facebook_count 'one_return_facebook_count',
				os.one_return_google_count 'one_return_google_count',
				os.one_return_longe_count 'one_return_longe_count',
				os.one_return_quick_count 'one_return_quick_count',
				os.one_return_count 'one_return_count',
				os.one_return_facebook_rate 'one_return_facebook_rate',
				os.one_return_google_rate 'one_return_google_rate',
				os.one_return_longe_rate 'one_return_longe_rate',
				os.one_return_quick_rate 'one_return_quick_rate',
				os.one_return_rate 'one_return_rate',
				os.three_return_facebook_count 'three_return_facebook_count',
				os.three_return_google_count 'three_return_google_count',
				os.three_return_longe_count 'three_return_longe_count',
				os.three_return_quick_count 'three_return_quick_count',
				os.three_return_count 'three_return_count',
				os.three_return_facebook_rate 'three_return_facebook_rate',
				os.three_return_google_rate 'three_return_google_rate',
				os.three_return_longe_rate 'three_return_longe_rate',
				os.three_return_quick_rate 'three_return_quick_rate',
				os.three_return_rate 'three_return_rate',
				wos.return_facebook_count 'weekly_return_facebook_count',
				wos.return_google_count 'weekly_return_google_count',
				wos.return_longe_count 'weekly_return_longe_count',
				wos.return_quick_count 'weekly_return_quick_count',
				wos.return_count 'weekly_return_count',
				wos.return_facebook_rate 'weekly_return_facebook_rate',
				wos.return_google_rate 'weekly_return_google_rate',
				wos.return_longe_rate 'weekly_return_longe_rate',
				wos.return_quick_rate 'weekly_return_quick_rate',
				wos.return_rate 'weekly_return_rate',
				mos.return_facebook_count 'monthly_return_facebook_count',
				mos.return_google_count 'monthly_return_google_count',
				mos.return_longe_count 'monthly_return_longe_count',
				mos.return_quick_count 'monthly_return_quick_count',
				mos.return_count 'monthly_return_count',
				mos.return_facebook_rate 'monthly_return_facebook_rate',
				mos.return_google_rate 'monthly_return_google_rate',
				mos.return_longe_rate 'monthly_return_longe_rate',
				mos.return_quick_rate 'monthly_return_quick_rate',
				mos.return_rate 'monthly_return_rate'
			FROM operation_statistics os
			LEFT JOIN weekly_operation_statistics wos
				ON os.game_id = wos.game_id 
				AND os.date = wos.date 
			LEFT JOIN monthly_operation_statistics mos
				ON os.game_id = mos.game_id 
				AND os.date = mos.date 
			WHERE os.date BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')
				AND os.game_id = '{$game_id}'
		    ORDER BY os.date DESC
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
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */