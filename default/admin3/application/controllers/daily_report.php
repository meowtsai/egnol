<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_report extends MY_Controller {
	
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
    
    function account_data() {
        $query = $this->DB2->query("
            SELECT 
                COUNT(*) 'newuser_count',
                COUNT(CASE WHEN external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'newuser_facebook_count',
                COUNT(CASE WHEN external_id LIKE '%google' THEN 1 ELSE NULL END) 'newuser_google_count',
                COUNT(CASE WHEN external_id IS NULL THEN 1 ELSE NULL END) 'newuser_longe_count',
                COUNT(CASE WHEN external_id LIKE '%device' THEN 1 ELSE NULL END) 'newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() THEN 1 ELSE NULL END) 't_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 't_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%google' THEN 1 ELSE NULL END) 't_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id IS NULL THEN 1 ELSE NULL END) 't_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=CURDATE() AND external_id LIKE '%device' THEN 1 ELSE NULL END) 't_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 ELSE NULL END) 'y_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN 1 ELSE NULL END) 'y2_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y2_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y2_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y2_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y2_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) THEN 1 ELSE NULL END) 'y3_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y3_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y3_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y3_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y3_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) THEN 1 ELSE NULL END) 'y4_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y4_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y4_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y4_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y4_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) THEN 1 ELSE NULL END) 'y5_newuser_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y5_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y5_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y5_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y5_newuser_quick_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) THEN 1 ELSE NULL END) 'y6_newuser_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'y6_newuser_facebook_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%google' THEN 1 ELSE NULL END) 'y6_newuser_google_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id IS NULL THEN 1 ELSE NULL END) 'y6_newuser_longe_count',
                COUNT(CASE WHEN DATE(create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND external_id LIKE '%device' THEN 1 ELSE NULL END) 'y6_newuser_quick_count'
            FROM users 
		");
    }
    
    function statistics_data() {
        $query = $this->DB2->query("
            SELECT 
                g.name,
                us.game_id,
                us.date,
                us.new_login_count,
                nc.count 'total_new_character_count',
                us.new_character_count,
                us.login_count,
                os.one_retention_count,
                os.three_retention_count
            FROM user_statistics us
            LEFT JOIN operation_statistics os ON us.game_id=os.game_id AND us.date=os.date
            LEFT JOIN games g ON us.game_id=g.game_id
            LEFT JOIN (
                SELECT
                    s.game_id,
                    DATE(ch.create_time) 'create_date',
                    COUNT(*) 'count'
                FROM characters ch
                JOIN servers s ON ch.server_id=s.server_id
                WHERE DATE(ch.create_time) < CURDATE() AND DATE(ch.create_time) >= DATE_SUB(CURDATE(), INTERVAL 5 DAY)
                GROUP BY s.game_id, DATE(ch.create_time)
            ) as nc ON us.game_id=nc.game_id AND us.date=nc.create_date
            WHERE g.is_active=1 AND us.date < CURDATE() AND us.date >= DATE_SUB(CURDATE(), INTERVAL 5 DAY)
		");
    }
    
    function billing_data() {
        $query = $this->DB2->query("
            SELECT 
            FROM user_billing
            WHERE billing_type=1 AND result=1
            
            SELECT SUM(u.amount) AS money 
            FROM user_billing AS u,gash_billing AS g 
            WHERE u.gash_billing_id=g.id AND u.billing_type=1 AND u.result=1 AND g.server_id<>'r2gtest' AND u.create_time>'2016-04-26 12:00:00' AND u.create_time<?
		");
    }
    
    function event_data() {
        
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */