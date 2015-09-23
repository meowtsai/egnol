<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->DB1 = $this->load->database('long_e', TRUE);
        $this->DB2 = $this->load->database('long_e_2', TRUE);			
	}
	
	function generate_statistics_blank($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
				$save_table = "weekly_statistics";
				break;
			
			case "monthly":
				$save_table = "monthly_statistics";
				break;
				
			default:
				$save_table = "statistics";
				break;
		}

        $query = $this->DB2->from("games")->where("is_active", 1)->get();

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
                $query2 = $this->DB2->from($save_table)->where("game_id", $row->game_id)->limit(1)->get();

		        if ($query2->num_rows() > 0) {
					
			        $data = array(
			            'game_id' => $row->game_id,
			            'date' => $date
			        );
			
			        $this->save_statistics($data, $save_table);
					
		        } elseif($span=="daily") {
					
			        $date_blanks = date("Y-m-d",strtotime("-32 days"));
		            for ($i=$date_blanks;$i <= $date;$i = date("Y-m-d",strtotime("+1 day", strtotime($i)))) {
			            $data2 = array(
				            'game_id' => $row->game_id,
				            'date' => $i
			            );
			
			            $this->save_statistics($data2, $save_table);
		            }
		        }
		    }
		}
		
		echo "generate_".$span."_statistics_blank done - ".$date.PHP_EOL;
	}
	
	function generate_nation_by_ip($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
			
    	$query = $this->DB2->select("uid")->from("user_info")
    		->where("nation", NULL)->get();

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'new_character_count' => $row->character_cnt
			    );
			
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_new_character_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_login_statistics($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
			    $span_query = "YEAR(create_time) = YEAR('{$date}') AND WEEKOFYEAR(create_time) = WEEKOFYEAR('{$date}')";
				$save_table = "weekly_statistics";
				break;
			
			case "monthly":
			    $span_query = "YEAR(create_time) = YEAR('{$date}') AND MONTH(create_time) = MONTH('{$date}')";
				$save_table = "monthly_statistics";
				break;
				
			default:
				$span_query = "DATE(create_time) = '{$date}'";
				$save_table = "statistics";
				break;
		}

        $query = $this->DB2->query("
			SELECT
				game_id, COUNT(uid) 'login_count', SUM(first_login) 'new_login_count'
			FROM
				(SELECT
					game_id, uid, SUM(is_first) 'first_login'
				FROM
					log_game_logins
				WHERE
					".$span_query."
				GROUP BY game_id, uid) tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'login_count' => $row->login_count,
				    'new_login_count' => $row->new_login_count
			    );
			
			    $this->save_statistics($data, $save_table);
		    }
		}
		
		echo "generate_login_".$span."_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_character_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$stop_time=date("Y-m-d H:i:s", strtotime("+1 days 4 hours", strtotime($date))); 
			
        $query = $this->DB2->query("
			SELECT 
				game_id, SUM(new_character) 'character_cnt'
			FROM
			(
				SELECT 
					lgl.game_id, 1 'new_character'
				FROM
					log_game_logins AS lgl,
				(
					SELECT 
						uid, game_id, characters.create_time
					FROM
						characters
						JOIN servers ON characters.server_id = servers.server_id
					WHERE
						characters.create_time BETWEEN '{$date}' AND '{$stop_time}'
				) AS new_characters
				WHERE
					DATE(lgl.create_time) = '{$date}'
						AND lgl.is_first = 1
						AND new_characters.uid = lgl.uid
						AND new_characters.game_id = lgl.game_id
						AND new_characters.create_time >= lgl.create_time
				GROUP BY lgl.game_id , lgl.uid
			) AS tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'new_character_count' => $row->character_cnt
			    );
			
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_new_character_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_retention_statistics($date="", $interval=1, $span="daily", $is_first=TRUE)
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($interval+1)." days"));
		
		switch ($interval) {
			case 1:
			    $update_field = 'one_retention';
				break;
			case 3:
			    $update_field = 'three_retention';
				break;
			case 7:
			    $update_field = 'seven_retention';
				break;
			case 14:
			    $update_field = 'fourteen_retention';
				break;
			case 30:
			    $update_field = 'thirty_retention';
				break;
			default:
			    echo "Only 1,3,7,14,30 allowed for second parameter!";
				return 0;
		}
		
		$update_field = ($is_first) ?  $update_field.'_count' : $update_field.'_all_count';

        switch($span) {
			case "weekly":
				$span_query1 = "create_time BETWEEN '".date("Y-m-d", strtotime("-6 days", strtotime($date)))."'
								AND '".date("Y-m-d", strtotime($date))." 23:59:59'";
				$span_query2 = "create_time BETWEEN '".date("Y-m-d", strtotime("+1 day", strtotime($date)))."'
								AND '".date("Y-m-d", strtotime("+7 days", strtotime($date)))." 23:59:59'";
				$save_table = "weekly_statistics";
				break;
			
			case "monthly":
				$span_query1 = "create_time BETWEEN '".date("Y-m", strtotime($date))."-01'
								AND '".date("Y-m-t", strtotime($date))." 23:59:59'";
				$span_query2 = "create_time BETWEEN '".date("Y-m", strtotime("+1 day", strtotime($date)))."-01'
								AND '".date("Y-m-t", strtotime("+1 day", strtotime($date)))." 23:59:59'";
				$save_table = "monthly_statistics";
				break;
				
			default:
			    $span_query1 = "DATE(create_time) = '{$date}'";
			    $span_query2 = " DATE(create_time) = DATE_ADD(DATE('{$date}'), INTERVAL {$interval} DAY)";
				$save_table = "statistics";
				break;
		}
		
        $query = $this->DB2->query("
			SELECT 
				game_id, SUM(is_retention) 'retention'
			FROM
			(
				SELECT 
					lgl2.game_id, 1 'is_retention'
				FROM
				(
					SELECT 
						uid, game_id, MIN(create_time)
					FROM
						log_game_logins
					WHERE
						".$span_query1."
							".(($is_first) ? " AND is_first = 1 " : "")."
					GROUP BY uid, game_id
				) AS lgl,
				(
					SELECT 
						uid, game_id, MIN(create_time)
					FROM
						log_game_logins
					WHERE
						".$span_query2."
					GROUP BY uid, game_id
				) AS lgl2
				WHERE
					lgl2.uid = lgl.uid
						AND lgl2.game_id = lgl.game_id
			) AS tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
		
				$data = array(
					'game_id' => $row->game_id,
					'date' => $date,
					$update_field => $row->retention
				);
				
			    $this->save_statistics($data, $save_table);
		    }
		}
		
		echo "generate_".$interval."_retention_".$span."_statistics(".(($is_first) ? "first" : "all").") done - ".$date.PHP_EOL;
	}
	
	function generate_return_statistics($date="", $interval=1, $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($interval+1)." days"));
		
        switch($span) {
			case "weekly":
				$update_field = 'return_count';
			    $span_query1 = "YEAR(create_time) = YEAR('{$date}') AND WEEKOFYEAR(create_time) = WEEKOFYEAR('{$date}')";
				$span_query2 = "YEAR(create_time) = YEAR(DATE_SUB(DATE('{$date}'), INTERVAL 1 WEEK))
				                AND WEEKOFYEAR(create_time) = WEEKOFYEAR(DATE_SUB(DATE('{$date}'), INTERVAL 1 WEEK))";
				$save_table = "weekly_statistics";
				break;
			
			case "monthly":
				$update_field = 'return_count';
			    $span_query1 = "YEAR(create_time) = YEAR('{$date}') AND MONTH(create_time) = MONTH('{$date}')";
			    $span_query2 = "YEAR(log_game_logins.create_time) = YEAR(DATE_ADD(DATE('{$date}'), INTERVAL 1 MONTH))
				               AND MONTH(log_game_logins.create_time) = MONTH(DATE_ADD(DATE('{$date}'), INTERVAL 1 MONTH))";
				$save_table = "monthly_statistics";
				break;
				
			default:
				switch ($interval) {
					case 1:
						$update_field = 'one_return_count';
						$span_query2 = "DATE(create_time) = DATE_SUB(DATE('{$date}'), INTERVAL 1 DAY)";
						break;
					case 3:
						$update_field = 'three_return_count';
						$span_query2 = "DATE(create_time) BETWEEN DATE_SUB(DATE('{$date}'), INTERVAL 3 DAY) AND DATE_SUB(DATE('{$date}'), INTERVAL 1 DAY)";
						break;
				}
				$span_query1 = "DATE(create_time) = '{$date}'";
				$save_table = "statistics";
				break;
		}
		
        $query = $this->DB2->query("
			SELECT 
				lgl.game_id, COUNT(lgl.uid) '{$update_field}'
			FROM
			(
				SELECT 
					uid, game_id, MIN(create_time)
				FROM
					log_game_logins lgl
				WHERE
					".$span_query1."
						AND is_first <> 1
				GROUP BY uid, game_id
			) AS lgl
				LEFT JOIN
			(
				SELECT 
					uid, game_id, MIN(create_time)
				FROM
					log_game_logins
				WHERE
					".$span_query2."
				GROUP BY uid, game_id
			) AS lgl2 ON lgl.game_id = lgl2.game_id AND lgl.uid = lgl2.uid 
			WHERE
				lgl2.uid IS NULL
			GROUP BY lgl.game_id
		");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
		
				$data = array(
					'game_id' => $row->game_id,
					'date' => $date,
					$update_field => $row->$update_field 
				);
				
			    $this->save_statistics($data, $save_table);
		    }
		}
		
		echo "generate_".$interval."_return_".$span."_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_billing_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->DB2->query("
			SELECT 
				game_id,
				COUNT(uid) 'deposit_user_count',
				SUM(amount_total) 'deposit_total',
				SUM(is_first) 'new_deposit_user_count'
			FROM
			(   
				SELECT 
					ub.uid,
					sv.game_id,
					SUM(ub.amount) 'amount_total',
					(
						SELECT 
							IF(COUNT(*) > 0, 0, 1)
						FROM
							user_billing
						JOIN servers ON user_billing.server_id = servers.server_id
						WHERE
							user_billing.uid = ub.uid
								AND servers.game_id = sv.game_id
								AND user_billing.create_time < ub.create_time
								AND user_billing.billing_type = 2
								AND user_billing.result = 1
						LIMIT 1
					) 'is_first'
				FROM
					user_billing ub
				JOIN servers sv ON ub.server_id = sv.server_id
				WHERE
					DATE(ub.create_time) = '{$date}'
						AND ub.billing_type = 2
						AND ub.result = 1
				GROUP BY ub.uid , sv.game_id
			) tmp
			GROUP BY game_id");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'deposit_user_count' => $row->deposit_user_count,
				    'deposit_total' => $row->deposit_total,
				    'new_deposit_user_count' => $row->new_deposit_user_count
			    );
				
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_billing_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_consume_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->DB2->query("
			SELECT 
				game_id,
				COUNT(uid) 'consume_user_count',
				SUM(amount_total) 'consume_total'
			FROM
			(
				SELECT 
					lgc.uid,
					lgc.game_id,
					SUM(lgc.amount) 'amount_total'
				FROM
					log_game_consumes lgc
				WHERE
					DATE(lgc.create_time) = '{$date}'
				GROUP BY lgc.uid , lgc.game_id
			) tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'consume_user_count' => $row->consume_user_count,
				    'consume_total' => $row->consume_total
			    );
				
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_consume_statistics done - ".$date.PHP_EOL;
	}	
	
	function generate_new_consume_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
			
		$query = $this->DB2->query("
			SELECT 
				game_id,
				COUNT(uid) 'new_consume_user_count'
			FROM
			(
				SELECT 
					uid, game_id, MIN(create_time) 'first_time'
				FROM
					log_game_consumes
				WHERE
					DATE(create_time) <= '{$date}'
				GROUP BY uid, game_id
			) AS lgc
			WHERE
				DATE(first_time) = '{$date}'
			GROUP BY game_id");		

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'new_consume_user_count' => $row->new_consume_user_count
			    );
				
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_new_consume_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_game_time_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->DB2->query("
			SELECT 
				game_id,
				SUM(game_time) 'total_time'
			FROM
			(
				SELECT 
					lgl.uid,
					lgl.game_id,
					TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time) 'game_time'
				FROM
					log_game_logins lgl
				WHERE
					DATE(lgl.create_time) = '{$date}'
						AND lgl.logout_time IS NOT NULL
				GROUP BY lgl.uid , lgl.game_id
			) tmp
			GROUP BY game_id");		

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'total_time' => $row->total_time
			    );
			
			    $this->save_statistics($data);
		    }
		}
		echo "generate_game_time_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_paid_game_time_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
			
		$query = $this->DB2->query("
			SELECT 
				game_id,
				SUM(paid_game_time) 'paid_total_time'
			FROM
			(
				SELECT 
					lgl.uid,
					lgl.game_id,
					TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time) 'paid_game_time'
				FROM
					log_game_logins lgl,
					(
						SELECT 
							uid, game_id, MIN(create_time)
						FROM
							user_billing
							JOIN servers ON user_billing.server_id=servers.server_id
						WHERE
							billing_type = 2
								AND result = 1
								AND DATE(create_time) = '{$date}'
						GROUP BY uid, game_id
					) AS paid_users
				WHERE
					DATE(lgl.create_time) = '{$date}'
						AND lgl.logout_time IS NOT NULL
						AND lgl.uid = paid_users.uid
						AND lgl.game_id = paid_users.game_id
				GROUP BY lgl.uid , lgl.game_id
			) tmp
			GROUP BY game_id");		

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'paid_total_time' => $row->paid_total_time
			    );
			
			    $this->save_statistics($data);
		    }
		}
		echo "generate_paid_game_time_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_peak_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));	
		
		$query = $this->DB2->query("
			SELECT 
				game_id,
				SUM(online_0) 'count_0',
				SUM(online_1) 'count_1',
				SUM(online_2) 'count_2',
				SUM(online_3) 'count_3',
				SUM(online_4) 'count_4',
				SUM(online_5) 'count_5',
				SUM(online_6) 'count_6',
				SUM(online_7) 'count_7',
				SUM(online_8) 'count_8',
				SUM(online_9) 'count_9',
				SUM(online_10) 'count_10',
				SUM(online_11) 'count_11',
				SUM(online_12) 'count_12',
				SUM(online_13) 'count_13',
				SUM(online_14) 'count_14',
				SUM(online_15) 'count_15',
				SUM(online_16) 'count_16',
				SUM(online_17) 'count_17',
				SUM(online_18) 'count_18',
				SUM(online_19) 'count_19',
				SUM(online_20) 'count_20',
				SUM(online_21) 'count_21',
				SUM(online_22) 'count_22',
				SUM(online_23) 'count_23'
			FROM
			(
				SELECT 
					game_id,
						IF(create_time <= '{$date} 00:00:00'
							AND logout_time > '{$date} 00:00:00', 1, 0) 'online_0',
						IF(create_time <= '{$date} 01:00:00'
							AND logout_time > '{$date} 01:00:00', 1, 0) 'online_1',
						IF(create_time <= '{$date} 02:00:00'
							AND logout_time > '{$date} 02:00:00', 1, 0) 'online_2',
						IF(create_time <= '{$date} 03:00:00'
							AND logout_time > '{$date} 03:00:00', 1, 0) 'online_3',
						IF(create_time <= '{$date} 04:00:00'
							AND logout_time > '{$date} 04:00:00', 1, 0) 'online_4',
						IF(create_time <= '{$date} 05:00:00'
							AND logout_time > '{$date} 05:00:00', 1, 0) 'online_5',
						IF(create_time <= '{$date} 06:00:00'
							AND logout_time > '{$date} 06:00:00', 1, 0) 'online_6',
						IF(create_time <= '{$date} 07:00:00'
							AND logout_time > '{$date} 07:00:00', 1, 0) 'online_7',
						IF(create_time <= '{$date} 08:00:00'
							AND logout_time > '{$date} 08:00:00', 1, 0) 'online_8',
						IF(create_time <= '{$date} 09:00:00'
							AND logout_time > '{$date} 09:00:00', 1, 0) 'online_9',
						IF(create_time <= '{$date} 10:00:00'
							AND logout_time > '{$date} 10:00:00', 1, 0) 'online_10',
						IF(create_time <= '{$date} 11:00:00'
							AND logout_time > '{$date} 11:00:00', 1, 0) 'online_11',
						IF(create_time <= '{$date} 12:00:00'
							AND logout_time > '{$date} 12:00:00', 1, 0) 'online_12',
						IF(create_time <= '{$date} 13:00:00'
							AND logout_time > '{$date} 13:00:00', 1, 0) 'online_13',
						IF(create_time <= '{$date} 14:00:00'
							AND logout_time > '{$date} 14:00:00', 1, 0) 'online_14',
						IF(create_time <= '{$date} 15:00:00'
							AND logout_time > '{$date} 15:00:00', 1, 0) 'online_15',
						IF(create_time <= '{$date} 16:00:00'
							AND logout_time > '{$date} 16:00:00', 1, 0) 'online_16',
						IF(create_time <= '{$date} 17:00:00'
							AND logout_time > '{$date} 17:00:00', 1, 0) 'online_17',
						IF(create_time <= '{$date} 18:00:00'
							AND logout_time > '{$date} 18:00:00', 1, 0) 'online_18',
						IF(create_time <= '{$date} 19:00:00'
							AND logout_time > '{$date} 19:00:00', 1, 0) 'online_19',
						IF(create_time <= '{$date} 20:00:00'
							AND logout_time > '{$date} 20:00:00', 1, 0) 'online_20',
						IF(create_time <= '{$date} 21:00:00'
							AND logout_time > '{$date} 21:00:00', 1, 0) 'online_21',
						IF(create_time <= '{$date} 22:00:00'
							AND logout_time > '{$date} 22:00:00', 1, 0) 'online_22',
						IF(create_time <= '{$date} 23:00:00'
							AND logout_time > '{$date} 23:00:00', 1, 0) 'online_23'
				FROM
					log_game_logins
				WHERE
					DATE(create_time) = '{$date}'
						OR DATE(logout_time) = '{$date}'
			) tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $online_users_statistics_data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
				        'count_0' => $row->count_0,
				        'count_1' => $row->count_1,
				        'count_2' => $row->count_2,
				        'count_3' => $row->count_3,
				        'count_4' => $row->count_4,
				        'count_5' => $row->count_5,
				        'count_6' => $row->count_6,
				        'count_7' => $row->count_7,
				        'count_8' => $row->count_8,
				        'count_9' => $row->count_9,
				        'count_10' => $row->count_10,
				        'count_11' => $row->count_11,
				        'count_12' => $row->count_12,
				        'count_13' => $row->count_13,
				        'count_14' => $row->count_14,
				        'count_15' => $row->count_15,
				        'count_16' => $row->count_16,
				        'count_17' => $row->count_17,
				        'count_18' => $row->count_18,
				        'count_19' => $row->count_19,
				        'count_20' => $row->count_20,
				        'count_21' => $row->count_21,
				        'count_22' => $row->count_22,
				        'count_23' => $row->count_23
			        );
					
					$this->save_statistics($online_users_statistics_data, "online_users_statistics");
					
				    $peak_user_count = max($row->count_0, $row->count_1, $row->count_2, $row->count_3, $row->count_4, $row->count_5, $row->count_6, 
					    $row->count_7, $row->count_8, $row->count_9, $row->count_10, $row->count_11, $row->count_12, $row->count_13, $row->count_14, $row->count_15, 
						$row->count_16, $row->count_17, $row->count_18, $row->count_19, $row->count_20, $row->count_21, $row->count_22, $row->count_23);
					
					$statistics_data = array(
						'game_id' => $row->game_id,
						'date' => $date,
						'peak_user_count' => $peak_user_count
					);
				
					$this->save_statistics($statistics_data);
			    }
		    }
		}
		echo "generate_peak_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_game_length_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));			
		
			$query = $this->DB2->query("
				SELECT new_gt.game_id, new_gt.date,
					new_login_count,
					new_login_count_15,
					new_login_count_30,
					new_login_count_60,
					new_login_count_90,
					new_login_count_120,
					new_login_count_more,
					all_login_count,
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
				FROM 
				(
					SELECT game_id, DATE(create_time) 'date',
						COUNT(uid) 'all_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 15 THEN 1 ELSE NULL END) 'login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 30 THEN 1 ELSE NULL END) 'login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 60 THEN 1 ELSE NULL END) 'login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 90 THEN 1 ELSE NULL END) 'login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 120 THEN 1 ELSE NULL END) 'login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 120 THEN 1 ELSE NULL END) 'login_count_more'
					FROM log_game_logins
					WHERE create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
					GROUP BY game_id, DATE(create_time)
				) AS all_gt
					LEFT JOIN
				(
					SELECT game_id, DATE(create_time) 'date',
						COUNT(uid) 'new_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 15 THEN 1 ELSE NULL END) 'new_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 30 THEN 1 ELSE NULL END) 'new_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 60 THEN 1 ELSE NULL END) 'new_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 90 THEN 1 ELSE NULL END) 'new_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 120 THEN 1 ELSE NULL END) 'new_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 120 THEN 1 ELSE NULL END) 'new_login_count_more'
					FROM log_game_logins
					WHERE create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND is_first = 1
					GROUP BY game_id, DATE(create_time)
				) AS new_gt ON all_gt.game_id=new_gt.game_id AND all_gt.date=new_gt.date
					LEFT JOIN
				(
					SELECT lgl.game_id, DATE(lgl.create_time) 'date',
						COUNT(lgl.uid) 'deposit_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 15 THEN 1 ELSE NULL END) 'deposit_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 30 THEN 1 ELSE NULL END) 'deposit_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 60 THEN 1 ELSE NULL END) 'deposit_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 90 THEN 1 ELSE NULL END) 'deposit_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 120 THEN 1 ELSE NULL END) 'deposit_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 120 THEN 1 ELSE NULL END) 'deposit_login_count_more'
					FROM log_game_logins lgl
					JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND lgl.create_time >= ub.create_time
					WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND ub.billing_type = 2 
						AND ub.result = 1
					GROUP BY lgl.game_id, DATE(lgl.create_time)
				) AS deposit_gt ON all_gt.game_id=deposit_gt.game_id AND all_gt.date=deposit_gt.date
					LEFT JOIN
				(
					SELECT lgl.game_id, DATE(lgl.create_time) 'date',
						COUNT(lgl.uid) 'new_deposit_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 15 THEN 1 ELSE NULL END) 'new_deposit_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 30 THEN 1 ELSE NULL END) 'new_deposit_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 60 THEN 1 ELSE NULL END) 'new_deposit_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 90 THEN 1 ELSE NULL END) 'new_deposit_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 120 THEN 1 ELSE NULL END) 'new_deposit_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 120 THEN 1 ELSE NULL END) 'new_deposit_login_count_more'
					FROM log_game_logins lgl
					JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND DATE(lgl.create_time) = DATE(ub.create_time)
					WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND ub.billing_type = 2 
						AND ub.result = 1
					GROUP BY lgl.game_id, DATE(lgl.create_time)
				) AS new_deposit_gt ON all_gt.game_id=new_deposit_gt.game_id AND all_gt.date=new_deposit_gt.date
			");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						'new_login_count_15' => $row->new_login_count_15,
						'new_login_count_30' => $row->new_login_count_30,
						'new_login_count_60' => $row->new_login_count_60,
						'new_login_count_90' => $row->new_login_count_90,
						'new_login_count_120' => $row->new_login_count_120,
						'new_login_count_more' => $row->new_login_count_more,
						'login_count_15' => $row->login_count_15,
						'login_count_30' => $row->login_count_30,
						'login_count_60' => $row->login_count_60,
						'login_count_90' => $row->login_count_90,
						'login_count_120' => $row->login_count_120,
						'login_count_more' => $row->login_count_more,
						'deposit_login_count' => $row->deposit_login_count,
						'deposit_login_count_15' => $row->deposit_login_count_15,
						'deposit_login_count_30' => $row->deposit_login_count_30,
						'deposit_login_count_60' => $row->deposit_login_count_60,
						'deposit_login_count_90' => $row->deposit_login_count_90,
						'deposit_login_count_120' => $row->deposit_login_count_120,
						'deposit_login_count_more' => $row->deposit_login_count_more,
						'new_deposit_login_count' => $row->new_deposit_login_count,
						'new_deposit_login_count_15' => $row->new_deposit_login_count_15,
						'new_deposit_login_count_30' => $row->new_deposit_login_count_30,
						'new_deposit_login_count_60' => $row->new_deposit_login_count_60,
						'new_deposit_login_count_90' => $row->new_deposit_login_count_90,
						'new_deposit_login_count_120' => $row->new_deposit_login_count_120,
						'new_deposit_login_count_more' => $row->new_deposit_login_count_more
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_game_length_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_user_game_length_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));			
		
			$query = $this->DB2->query("
				SELECT game_id, date,
					login_count_15,
					login_count_30,
					login_count_60,
					login_count_90,
					login_count_120,
					login_count_more
				FROM 
				(
					SELECT game_id, DATE(create_time) 'date',
						COUNT(uid) 'all_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 15 THEN 1 ELSE NULL END) 'login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 30 THEN 1 ELSE NULL END) 'login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 60 THEN 1 ELSE NULL END) 'login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 90 THEN 1 ELSE NULL END) 'login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 120 THEN 1 ELSE NULL END) 'login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 120 THEN 1 ELSE NULL END) 'login_count_more'
					FROM log_game_logins
					WHERE create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
					GROUP BY game_id, DATE(create_time)
				) AS all_gt
			");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						'login_count_15' => $row->login_count_15,
						'login_count_30' => $row->login_count_30,
						'login_count_60' => $row->login_count_60,
						'login_count_90' => $row->login_count_90,
						'login_count_120' => $row->login_count_120,
						'login_count_more' => $row->login_count_more
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_user_game_length_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_user_game_length_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));			
		
			$query = $this->DB2->query("
				SELECT game_id, date,
					new_login_count,
					new_login_count_15,
					new_login_count_30,
					new_login_count_60,
					new_login_count_90,
					new_login_count_120,
					new_login_count_more
				FROM 
				(
					SELECT game_id, DATE(create_time) 'date',
						COUNT(uid) 'new_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 15 THEN 1 ELSE NULL END) 'new_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 30 THEN 1 ELSE NULL END) 'new_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 60 THEN 1 ELSE NULL END) 'new_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 90 THEN 1 ELSE NULL END) 'new_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, create_time, logout_time) < 120 THEN 1 ELSE NULL END) 'new_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, create_time, logout_time) >= 120 THEN 1 ELSE NULL END) 'new_login_count_more'
					FROM log_game_logins
					WHERE create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND is_first = 1
					GROUP BY game_id, DATE(create_time)
				) AS new_gt
			");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						'new_login_count_15' => $row->new_login_count_15,
						'new_login_count_30' => $row->new_login_count_30,
						'new_login_count_60' => $row->new_login_count_60,
						'new_login_count_90' => $row->new_login_count_90,
						'new_login_count_120' => $row->new_login_count_120,
						'new_login_count_more' => $row->new_login_count_more
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_new_user_game_length_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_deposit_user_game_length_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));			
		
			$query = $this->DB2->query("
				SELECT game_id, date,
					deposit_login_count,
					deposit_login_count_15,
					deposit_login_count_30,
					deposit_login_count_60,
					deposit_login_count_90,
					deposit_login_count_120,
					deposit_login_count_more
				FROM 
				(
					SELECT lgl.game_id, DATE(lgl.create_time) 'date',
						COUNT(lgl.uid) 'deposit_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 15 THEN 1 ELSE NULL END) 'deposit_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 30 THEN 1 ELSE NULL END) 'deposit_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 60 THEN 1 ELSE NULL END) 'deposit_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 90 THEN 1 ELSE NULL END) 'deposit_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 120 THEN 1 ELSE NULL END) 'deposit_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 120 THEN 1 ELSE NULL END) 'deposit_login_count_more'
					FROM log_game_logins lgl
					JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND lgl.create_time >= ub.create_time
					WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND ub.billing_type = 2 
						AND ub.result = 1
					GROUP BY lgl.game_id, DATE(lgl.create_time)
				) AS deposit_gt
			");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						'deposit_login_count' => $row->deposit_login_count,
						'deposit_login_count_15' => $row->deposit_login_count_15,
						'deposit_login_count_30' => $row->deposit_login_count_30,
						'deposit_login_count_60' => $row->deposit_login_count_60,
						'deposit_login_count_90' => $row->deposit_login_count_90,
						'deposit_login_count_120' => $row->deposit_login_count_120,
						'deposit_login_count_more' => $row->deposit_login_count_more
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_deposit_user_game_length_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_deposit_user_game_length_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));			
		
			$query = $this->DB2->query("
				SELECT game_id, date,
					new_deposit_login_count,
					new_deposit_login_count_15,
					new_deposit_login_count_30,
					new_deposit_login_count_60,
					new_deposit_login_count_90,
					new_deposit_login_count_120,
					new_deposit_login_count_more
				FROM 
				(
					SELECT lgl.game_id, DATE(lgl.create_time) 'date',
						COUNT(lgl.uid) 'new_deposit_login_count',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 15 THEN 1 ELSE NULL END) 'new_deposit_login_count_15',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 15 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 30 THEN 1 ELSE NULL END) 'new_deposit_login_count_30',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 30 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 60 THEN 1 ELSE NULL END) 'new_deposit_login_count_60',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 60 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 90 THEN 1 ELSE NULL END) 'new_deposit_login_count_90',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 90 AND TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) < 120 THEN 1 ELSE NULL END) 'new_deposit_login_count_120',
						COUNT(CASE WHEN TIMESTAMPDIFF(MINUTE, lgl.create_time, lgl.logout_time) >= 120 THEN 1 ELSE NULL END) 'new_deposit_login_count_more'
					FROM log_game_logins lgl
					JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND DATE(lgl.create_time) = DATE(ub.create_time)
					WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
						AND ub.billing_type = 2 
						AND ub.result = 1
					GROUP BY lgl.game_id, DATE(lgl.create_time)
				) AS new_deposit_gt
			");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						'new_deposit_login_count' => $row->new_deposit_login_count,
						'new_deposit_login_count_15' => $row->new_deposit_login_count_15,
						'new_deposit_login_count_30' => $row->new_deposit_login_count_30,
						'new_deposit_login_count_60' => $row->new_deposit_login_count_60,
						'new_deposit_login_count_90' => $row->new_deposit_login_count_90,
						'new_deposit_login_count_120' => $row->new_deposit_login_count_120,
						'new_deposit_login_count_more' => $row->new_deposit_login_count_more
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_new_deposit_user_game_length_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_user_lifetime_value_statistics($date="", $interval=1)
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($interval+1)." days"));
		$end_date=date("Y-m-d",strtotime("+".$interval." days", strtotime($date)));
		
		switch ($interval) {
			case 1:
			    $update_field = 'one_ltv';
				break;
			case 7:
			    $update_field = 'seven_ltv';
				break;
			case 14:
			    $update_field = 'fourteen_ltv';
				break;
			case 30:
			    $update_field = 'thirty_ltv';
				break;
			case 60:
			    $update_field = 'sixty_ltv';
				break;
			case 90:
			    $update_field = 'ninety_ltv';
				break;
			default:
			    echo "Only 1,7,14,30,60,90 allowed for second parameter!";
				return 0;
				break;
		}
		
		$query = $this->DB2->query("
			SELECT lgl.game_id, DATE(lgl.create_time) 'date',
				SUM(ub.amount) 'life_time_value'
			FROM log_game_logins lgl
			JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id
			WHERE lgl.is_first = 1
				AND lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
				AND ub.billing_type = 2 
				AND ub.result = 1
				AND ub.create_time BETWEEN '{$date} 00:00:00' AND '{$end_date} 00:00:00'
			GROUP BY lgl.game_id, DATE(lgl.create_time)
		");

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				if (isset($row->game_id)) {
			        $data = array(
				        'game_id' => $row->game_id,
				        'date' => $date,
						$update_field => $row->life_time_value
			        );
				
					$this->save_statistics($data);
			    }
		    }
		}
		echo "generate_new_user_".$interval."_lifetime_value_statistics done - ".$date.PHP_EOL;
	}
	
	function save_statistics($data, $save_table="statistics") {
		
		$statistics = $this->DB2->where("game_id", $data['game_id'])->where("date", $data['date'])->get($save_table);
		
		if ($statistics->num_rows() > 0) {
            echo "[update]";
			$this->DB1->where("game_id", $data['game_id'])->where("date", $data['date'])->update($save_table, $data);
		} else {
            echo "[insert]";
			$this->DB1->insert($save_table, $data);
		}
        echo "[".$save_table."][".$data['game_id']."][".$data['date']."]";
	}
	
	function cron_bundle($date="") {
		ini_set('max_execution_time', 9999);
		
		if (empty($date)) {
			$check_date=date("Y-m-d",strtotime("-1 days"));
		    $date_1="";
		    $date_3="";
		    $date_7="";
		    $date_14="";
		    $date_30="";
	    } else {
			$check_date=$date;
		    $date_1=date("Y-m-d",strtotime("-1 days", strtotime($date)));
		    $date_3=date("Y-m-d",strtotime("-3 days", strtotime($date)));
		    $date_7=date("Y-m-d",strtotime("-7 days", strtotime($date)));
		    $date_14=date("Y-m-d",strtotime("-14 days", strtotime($date)));
		    $date_30=date("Y-m-d",strtotime("-30 days", strtotime($date)));
		}
		$this->generate_statistics_blank($date);
		$this->generate_login_statistics($date);
		$this->generate_new_character_statistics($date);
		$this->generate_retention_statistics($date_1, 1);
		$this->generate_retention_statistics($date_3, 3);
		$this->generate_retention_statistics($date_7, 7);
		$this->generate_retention_statistics($date_14, 14);
		$this->generate_retention_statistics($date_30, 30);
		$this->generate_retention_statistics($date_1, 1, 'daily', FALSE);
		$this->generate_return_statistics($date, 1);
		$this->generate_return_statistics($date, 3);
		$this->generate_billing_statistics($date);
		$this->generate_consume_statistics($date);
		$this->generate_new_consume_statistics($date);
		$this->generate_game_time_statistics($date);
		$this->generate_paid_game_time_statistics($date);
		$this->generate_peak_statistics($date);
		$this->generate_user_game_length_statistics($date);
		$this->generate_new_user_game_length_statistics($date);
		$this->generate_deposit_user_game_length_statistics($date);
		$this->generate_new_deposit_user_game_length_statistics($date);
		$this->generate_new_user_lifetime_value_statistics($date, 1);
		$this->generate_new_user_lifetime_value_statistics($date, 7);
		$this->generate_new_user_lifetime_value_statistics($date, 14);
		$this->generate_new_user_lifetime_value_statistics($date, 30);
		$this->generate_new_user_lifetime_value_statistics($date, 60);
		$this->generate_new_user_lifetime_value_statistics($date, 90);
		
		if ("7"==date("N", strtotime($check_date))) {
		    $this->generate_statistics_blank($date, 'weekly');
			$this->generate_login_statistics($date, 'weekly');
			$date_week=date("Y-m-d",strtotime("-1 week", strtotime($check_date)));
			$this->generate_retention_statistics($date_week, 1, 'weekly');
			$this->generate_retention_statistics($date_week, 1, 'weekly', FALSE);
			$this->generate_return_statistics($date, 1, 'weekly');
		}
		
		if ($date==date("Y-m-t", strtotime($check_date))) {
		    $this->generate_statistics_blank($date, 'monthly');
			$this->generate_login_statistics($date, 'monthly');
			$date_month=date("Y-m-t",strtotime("-31 days", strtotime($check_date)));
			$this->generate_retention_statistics($date_month, 1, 'monthly');
			$this->generate_retention_statistics($date_month, 1, 'monthly', FALSE);
			$this->generate_return_statistics($date, 1, 'monthly');
		}
	}
	
	function echo_passed_time($start_time) {
		
		$end_time = time();
		$passed_time = ($end_time - $start_time)/60;
		echo 'Time spent: '.$passed_time.'m'.PHP_EOL;
		
		return $end_time;
	}
	
	function cron_bundle_que($date) {
		ini_set('max_execution_time', 99999);
		
		$run_date = $date;
		
		for ($run_date; $run_date <= date('Y-m-d'); $run_date=date("Y-m-d",strtotime('+1 day', strtotime($run_date)))) {
			echo '['.$run_date.']'.PHP_EOL;
			$this->cron_bundle($run_date);
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
