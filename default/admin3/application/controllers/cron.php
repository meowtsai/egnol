<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	//function __construct() 
	//{
	//	parent::__construct();					
	//}
	
	function generate_temporary_lgl($date="") {
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
		$this->drop_temporary_lgl();
		
        $query = $this->db->query("
			CREATE TEMPORARY TABLE lgl
			(
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `uid` int(11) NOT NULL,
			  `game_id` varchar(20) DEFAULT NULL,
			  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=MEMORY;");	
		
        $query = $this->db->query("
			INSERT INTO lgl (uid, game_id, create_time) 
			SELECT 
			    uid, game_id, create_time FROM log_game_logins 
			WHERE 
			    DATE(create_time) = '{$date}' 
				    AND is_first = 1");	
	}
	
	function drop_temporary_lgl() {
		$this->lang->load('db_lang', 'zh-TW');
		
		$query = $this->db->query("DROP TABLE IF EXISTS `lgl`");	
	}
	
	function generate_login_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));

        $query = $this->db->query("
			SELECT
				game_id, COUNT(uid) 'login_count', SUM(first_login) 'new_login_count'
			FROM
				(SELECT
					game_id, uid, SUM(is_first) 'first_login'
				FROM
					log_game_logins
				WHERE
					DATE(create_time) = '{$date}'
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
			
			    unset($statistics);
			    $statistics = $this->db->where('game_id', $row->game_id)->where('date', $date)->get('statistics');
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_login_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_character_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$stop_time=date("Y-m-d H:i:s", strtotime("+1 days 4 hours", strtotime($date))); 
			
        $query = $this->db->query("
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
				    //'new_login_count' => $row->login_cnt,
				    'new_character_count' => $row->character_cnt
			    );
			
			    unset($statistics);
			    $statistics = $this->db->where('game_id', $row->game_id)->where('date', $date)->get('statistics');
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_new_character_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_retention_statistics($date="", $days=1, $is_first=TRUE)
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($days+1)." days"));
		
		switch ($days) {
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

        $query = $this->db->query("
			SELECT 
				game_id, SUM(is_retention) 'retention'
			FROM
			(
				SELECT 
					log_game_logins.game_id, 1 'is_retention'
				FROM
					log_game_logins,
				(
					SELECT 
						uid, game_id, server_id, create_time
					FROM
						log_game_logins
					WHERE
						DATE(create_time) = '{$date}'
							".(($is_first) ? " AND is_first = 1 " : "")."
				) AS lgl
				WHERE
					log_game_logins.uid = lgl.uid
						AND log_game_logins.game_id = lgl.game_id
						AND DATE(log_game_logins.create_time) = DATE_ADD(DATE('{$date}'), INTERVAL {$days} DAY)
				GROUP BY log_game_logins.game_id , log_game_logins.uid
			) AS tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    $update_field => $row->retention
			    );
			
			    unset($statistics);
			    $statistics = $this->db->where('game_id', $row->game_id)->where('date', $date)->get('statistics');
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_".$days."_retention_statistics(".(($is_first) ? "first" : "all").") done - ".$date.PHP_EOL;
	}
	
	function generate_billing_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->db->query("
			SELECT 
				game_id,
				COUNT(uid) 'deposit_user_count',
				SUM(amount_total) 'deposit_total',
				SUM(is_first) 'new_deposit_user_count'
			FROM
				(SELECT 
					ub.uid,
						sv.game_id,
						SUM(ub.amount) 'amount_total',
						(SELECT 
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
							LIMIT 1) 'is_first'
				FROM
					user_billing ub
				JOIN servers sv ON ub.server_id = sv.server_id
				WHERE
					DATE(ub.create_time) = '{$date}'
						AND ub.billing_type = 2
						AND ub.result = 1
				GROUP BY ub.uid , sv.game_id) tmp
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
			
			    unset($statistics);
			    $statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_billing_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_consume_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->db->query("
			SELECT 
				game_id,
				COUNT(uid) 'consume_user_count',
				SUM(amount_total) 'consume_total'
			FROM
				(SELECT 
					lgc.uid,
					lgc.game_id,
					SUM(lgc.amount) 'amount_total'
				FROM
					log_game_consumes lgc
				WHERE
					DATE(lgc.create_time) = '{$date}'
				GROUP BY lgc.uid , lgc.game_id) tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'consume_user_count' => $row->consume_user_count,
				    'consume_total' => $row->consume_total
			    );
			
			    unset($statistics);
			    $statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_consume_statistics done - ".$date.PHP_EOL;
	}	
	
	function generate_new_consume_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
			
		$query = $this->db->query("
			SELECT 
				game_id,
				COUNT(uid) 'new_consume_user_count'
			FROM
				(SELECT 
					uid, game_id, MIN(create_time) 'first_time'
				FROM
					log_game_consumes
				WHERE
					DATE(create_time) <= '{$date}'
				GROUP BY uid, game_id) AS lgc
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
			
			    unset($statistics);
			    $statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		
		echo "generate_new_consume_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_game_time_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		$query = $this->db->query("
			SELECT 
				game_id,
				SUM(game_time) 'total_time'
			FROM
				(SELECT 
					lgl.uid,
					lgl.game_id,
					TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time) 'game_time'
				FROM
					log_game_logins lgl
				WHERE
					DATE(lgl.create_time) = '{$date}'
						AND lgl.logout_time IS NOT NULL
				GROUP BY lgl.uid , lgl.game_id) tmp
			GROUP BY game_id");		

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'total_time' => $row->total_time
			    );
			
			    unset($statistics);
			    $statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		echo "generate_game_time_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_paid_game_time_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
			
		$query = $this->db->query("
			SELECT 
				game_id,
				SUM(paid_game_time) 'paid_total_time'
			FROM
				(SELECT 
					lgl.uid,
					lgl.game_id,
					TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time) 'paid_game_time'
				FROM
					log_game_logins lgl,
					(SELECT 
						uid, game_id, MIN(create_time)
							FROM
								user_billing
								JOIN servers ON user_billing.server_id=servers.server_id
							WHERE
								billing_type = 2
									AND result = 1
									AND DATE(create_time) <= '{$date}'
							GROUP BY uid, game_id) AS paid_users
				WHERE
					DATE(lgl.create_time) = '{$date}'
						AND lgl.logout_time IS NOT NULL
						AND lgl.uid = paid_users.uid
						AND lgl.game_id = paid_users.game_id
				GROUP BY lgl.uid , lgl.game_id) tmp
			GROUP BY game_id");		

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'paid_total_time' => $row->paid_total_time
			    );
			
			    unset($statistics);
			    $statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
			
		        if ($statistics->num_rows() > 0) {
				    $this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $data);
			    } else {
				    $this->db->insert("statistics", $data);
			    }
		    }
		}
		echo "generate_paid_game_time_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_peak_statistics($date="")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));	
		
		$query = $this->db->query("
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
				(SELECT 
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
						OR DATE(logout_time) = '{$date}') tmp
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
			
			        unset($statistics);
			        $online_users_statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("online_users_statistics");
			
		            if ($online_users_statistics->num_rows() > 0) {
				        $this->db->where("game_id", $row->game_id)->where("date", $date)->update("online_users_statistics", $online_users_statistics_data);
			        } else {
				        $this->db->insert("online_users_statistics", $online_users_statistics_data);
			        }
					
				    $peak_user_count = max($row->count_0, $row->count_1, $row->count_2, $row->count_3, $row->count_4, $row->count_5, $row->count_6, 
					    $row->count_7, $row->count_8, $row->count_9, $row->count_10, $row->count_11, $row->count_12, $row->count_13, $row->count_14, $row->count_15, 
						$row->count_16, $row->count_17, $row->count_18, $row->count_19, $row->count_20, $row->count_21, $row->count_22, $row->count_23);
					
					$statistics_data = array(
						'game_id' => $row->game_id,
						'date' => $date,
						'peak_user_count' => $peak_user_count
					);
				
					unset($statistics);
					$statistics = $this->db->where("game_id", $row->game_id)->where("date", $date)->get("statistics");
				
					if ($statistics->num_rows() > 0) {
						$this->db->where("game_id", $row->game_id)->where("date", $date)->update("statistics", $statistics_data);
					} else {
						$this->db->insert("statistics", $statistics_data);
					}
			    }
		    }
		}
		echo "generate_peak_statistics done - ".$date.PHP_EOL;
	}
	
	function cron_bundle($date) {
		ini_set('max_execution_time', 9999);
		
		$start_time = time();
		$this->generate_login_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_new_character_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 1);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 3);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 7);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 14);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 30);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_retention_statistics($date, 1, FALSE);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_billing_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_consume_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_new_consume_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_game_time_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_paid_game_time_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
		$this->generate_peak_statistics($date);
		$start_time = $this->echo_passed_time($start_time);
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