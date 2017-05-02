<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->global_dir = BASEPATH.'../global/';
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
        
		$this->DB1 = $this->load->database('long_e', TRUE);
        $this->DB2 = $this->load->database('long_e_2', TRUE);
			
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
	
	function generate_statistics_blank($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
				$save_table1 = "weekly_user_statistics";
				$save_table2 = "weekly_operation_statistics";
				break;
			
			case "monthly":
				$save_table1 = "monthly_user_statistics";
				$save_table2 = "monthly_operation_statistics";
				break;
				
			default:
				$save_table1 = "user_statistics";
				$save_table2 = "operation_statistics";
				break;
		}

        $query = $this->DB2->from("games")->where("is_active", 1)->get();
        //$query = $this->DB2->from("games")->where("game_id", "r2g")->get();

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
                $query2 = $this->DB2->from($save_table1)->where("game_id", $row->game_id)->limit(1)->get();

		        if ($query2->num_rows() > 0) {
					
					$this->DB1->where("date", $date)->where("game_id", $row->game_id)->delete($save_table1);
					$this->DB1->where("date", $date)->where("game_id", $row->game_id)->delete($save_table2);
					
			        $data = array(
			            'game_id' => $row->game_id,
			            'date' => $date
			        );
			
			        $this->save_statistics($data, $save_table1);
			        $this->save_statistics($data, $save_table2);
					
		        } elseif($span=="daily") {
					
			        $date_blanks = date("Y-m-d",strtotime("-32 days"));
		            for ($i=$date_blanks;$i <= $date;$i = date("Y-m-d",strtotime("+1 day", strtotime($i)))) {
			            $data2 = array(
				            'game_id' => $row->game_id,
				            'date' => $i
			            );
			
			            $this->save_statistics($data2, $save_table1);
			            $this->save_statistics($data2, $save_table2);
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
			    $span_query = "YEARWEEK(create_time, 3) = YEARWEEK('{$date}', 3) AND DATE(create_time)<='{$date}'";
				$save_table = "weekly_user_statistics";
                $span_select= "
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'new_login_facebook_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%google' THEN 1 ELSE NULL END) 'new_login_google_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id IS NULL THEN 1 ELSE NULL END) 'new_login_longe_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%device' THEN 1 ELSE NULL END) 'new_login_quick_count',
                    COUNT(CASE WHEN u.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'login_facebook_count',
                    COUNT(CASE WHEN u.external_id LIKE '%google' THEN 1 ELSE NULL END) 'login_google_count',
                    COUNT(CASE WHEN u.external_id IS NULL THEN 1 ELSE NULL END) 'login_longe_count',
                    COUNT(CASE WHEN u.external_id LIKE '%device' THEN 1 ELSE NULL END) 'login_quick_count'";
				break;
			
			case "monthly":
			    $span_query = "YEAR(create_time) = YEAR('{$date}') AND MONTH(create_time) = MONTH('{$date}') AND DATE(create_time)<='{$date}'";
				$save_table = "monthly_user_statistics";
                $span_select= "
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'new_login_facebook_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%google' THEN 1 ELSE NULL END) 'new_login_google_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id IS NULL THEN 1 ELSE NULL END) 'new_login_longe_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%device' THEN 1 ELSE NULL END) 'new_login_quick_count',
                    COUNT(CASE WHEN u.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'login_facebook_count',
                    COUNT(CASE WHEN u.external_id LIKE '%google' THEN 1 ELSE NULL END) 'login_google_count',
                    COUNT(CASE WHEN u.external_id IS NULL THEN 1 ELSE NULL END) 'login_longe_count',
                    COUNT(CASE WHEN u.external_id LIKE '%device' THEN 1 ELSE NULL END) 'login_quick_count'";
				break;
				
			default:
				$span_query = "DATE(create_time) = '{$date}'";
				$save_table = "user_statistics";
                $span_select= "
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'new_login_facebook_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%google' THEN 1 ELSE NULL END) 'new_login_google_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id IS NULL THEN 1 ELSE NULL END) 'new_login_longe_count',
                    COUNT(CASE WHEN tmp.first_login IS NOT NULL AND u.external_id LIKE '%device' THEN 1 ELSE NULL END) 'new_login_quick_count'";
				break;
		}
            
        $query = $this->DB2->query("
			SELECT
				tmp.game_id, 
                COUNT(tmp.uid) 'login_count', 
                COUNT(tmp.first_login) 'new_login_count',
				{$span_select}
			FROM
				(SELECT
					lgl.game_id, lgl.uid, all_first.create_time 'first_login'
				FROM
                    (SELECT
                        game_id, uid, MIN(create_time) 'create_time'
                    FROM 
                        log_game_logins
                    WHERE
                        ".$span_query."
                            ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                    GROUP BY game_id, uid
                    ) lgl
				LEFT JOIN
                    (SELECT
                        game_id, uid, MIN(create_time) 'create_time'
                    FROM 
                        user_server_first_logins
                    WHERE
                        1=1
                            ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                    GROUP BY game_id, uid
                    ) all_first ON lgl.game_id=all_first.game_id AND lgl.uid=all_first.uid AND DATE(lgl.create_time)=DATE(all_first.create_time)
				) tmp
            JOIN users u ON tmp.uid=u.uid
			GROUP BY tmp.game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'login_count' => $row->login_count,
				    'new_login_count' => $row->new_login_count
			    );
                
                if ($span=='weekly' || $span=='monthly') {
                    $data['login_facebook_count'] = $row->login_facebook_count;
                    $data['login_google_count'] = $row->login_google_count;
                    $data['login_longe_count'] = $row->login_longe_count;
                    $data['login_quick_count'] = $row->login_quick_count;
                }
                
                $data['new_login_facebook_count'] = $row->new_login_facebook_count;
                $data['new_login_google_count'] = $row->new_login_google_count;
                $data['new_login_longe_count'] = $row->new_login_longe_count;
                $data['new_login_quick_count'] = $row->new_login_quick_count;
                
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
				game_id, 
                SUM(new_character) 'character_cnt',
                COUNT(facebook_login) 'facebook_count',
                COUNT(google_login) 'google_count',
                COUNT(longe_login) 'longe_count',
                COUNT(quick_login) 'quick_count'
			FROM
			(
				SELECT 
					new_ch.game_id, 
                    1 'new_character',
                    CASE WHEN u.external_id LIKE '%facebook' THEN 1 ELSE NULL END 'facebook_login',
                    CASE WHEN u.external_id LIKE '%google' THEN 1 ELSE NULL END 'google_login',
                    CASE WHEN u.external_id IS NULL THEN 1 ELSE NULL END 'longe_login',
                    CASE WHEN u.external_id LIKE '%device' THEN 1 ELSE NULL END 'quick_login'
				FROM
                    (
                        SELECT 
                            ch.uid, servers.game_id, MIN(ch.create_time) 'create_time'
                        FROM
                            characters as ch
                        JOIN servers ON ch.server_id = servers.server_id
                        WHERE
                            1=1
                                ".(($this->testaccounts)?" AND ch.uid NOT IN (".$this->testaccounts.") ":"")."
                        GROUP BY ch.uid, servers.game_id
                    ) AS new_ch
                LEFT JOIN users u ON new_ch.uid=u.uid
                WHERE
                    DATE(new_ch.create_time) = '{$date}'
				GROUP BY new_ch.game_id , new_ch.uid
			) AS tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'new_character_count' => $row->character_cnt,
				    'new_character_facebook_count' => $row->facebook_count,
				    'new_character_google_count' => $row->google_count,
				    'new_character_longe_count' => $row->longe_count,
				    'new_character_quick_count' => $row->quick_count
			    );
			
			    $this->save_statistics($data);
		    }
		}
		
		echo "generate_new_character_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_device_statistics($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
			    $span_query = "YEARWEEK(create_time, 3) = YEARWEEK('{$date}', 3) AND DATE(create_time)<='{$date}'";
				$save_table = "weekly_user_statistics";
				break;
			
			case "monthly":
			    $span_query = "YEAR(create_time) = YEAR('{$date}') AND MONTH(create_time) = MONTH('{$date}') AND DATE(create_time)<='{$date}'";
				$save_table = "monthly_user_statistics";
				break;
				
			default:
				$span_query = "DATE(create_time) = '{$date}'";
				$save_table = "user_statistics";
				break;
		}
            
        $query = $this->DB2->query("
			SELECT
				game_id, COUNT(device_id) 'device_count'
			FROM
				(SELECT
					game_id, device_id
				FROM
					log_game_logins
				WHERE
					".$span_query."
                    AND device_id IS NOT NULL
                        ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
				GROUP BY game_id, device_id) tmp
			GROUP BY game_id");	

		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
			    $data = array(
				    'game_id' => $row->game_id,
				    'date' => $date,
				    'device_count' => $row->device_count
			    );
			
			    $this->save_statistics($data, $save_table);
		    }
		}
        
        if ($span=="daily") {
            $new_query = $this->DB2->query("
                SELECT
                    game_id, 
                    COUNT(device_id) 'new_device_count',
                    COUNT(CASE WHEN external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'new_device_facebook_count',
                    COUNT(CASE WHEN external_id LIKE '%google' THEN 1 ELSE NULL END) 'new_device_google_count',
                    COUNT(CASE WHEN external_id IS NULL THEN 1 ELSE NULL END) 'new_device_longe_count',
                    COUNT(CASE WHEN external_id LIKE '%device' THEN 1 ELSE NULL END) 'new_device_quick_count'
                FROM
                    (SELECT
                        game_id, device_id, external_id
                    FROM
                        (SELECT
                            l.game_id, l.device_id, MIN(l.create_time) 'create_time', MIN(u.external_id) 'external_id'
                        FROM
                            log_game_logins l
                        JOIN users u ON l.uid=u.uid
                        WHERE
                            DATE(l.create_time) <= '{$date}'
                            AND l.device_id IS NOT NULL
                                ".(($this->testaccounts)?" AND l.uid NOT IN (".$this->testaccounts.") ":"")."
                        GROUP BY l.game_id, l.device_id) first
                    WHERE
                        ".$span_query."
                        AND device_id IS NOT NULL
                    GROUP BY game_id, device_id) tmp
                GROUP BY game_id");

            if ($new_query->num_rows() > 0) {
                foreach ($new_query->result() as $row) {
                    $data = array(
                        'game_id' => $row->game_id,
                        'date' => $date,
                        'new_device_count' => $row->new_device_count,
                        'new_device_facebook_count' => $row->new_device_facebook_count,
                        'new_device_google_count' => $row->new_device_google_count,
                        'new_device_longe_count' => $row->new_device_longe_count,
                        'new_device_quick_count' => $row->new_device_quick_count
                    );
                
                    $this->save_statistics($data, $save_table);
                }
            }
        }
		
		echo "generate_device_".$span."_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_retention_statistics($date="", $interval=1, $span="daily", $is_first=TRUE)
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($interval+1)." days"));
		
		switch ($interval) {
			case 1:
			    $retention_text = 'one_retention';
				break;
			case 2:
			    $retention_text = 'three_retention';
				break;
			case 6:
			    $retention_text = 'seven_retention';
				break;
			case 13:
			    $retention_text = 'fourteen_retention';
				break;
			case 29:
			    $retention_text = 'thirty_retention';
				break;
			default:
			    echo "Only 1,3,7,14,30 allowed for second parameter!";
				return 0;
		}
		
		$update_field = ($is_first) ?  $retention_text.'_count' : $retention_text.'_all_count';

        switch($span) {
			case "weekly":
				$span_query1 = "create_time BETWEEN '".date("Y-m-d", strtotime("-6 days", strtotime($date)))."'
								AND '".date("Y-m-d", strtotime($date))." 23:59:59'";
				$span_query2 = "l.create_time BETWEEN '".date("Y-m-d", strtotime("+1 day", strtotime($date)))."'
								AND '".date("Y-m-d", strtotime("+7 days", strtotime($date)))." 23:59:59'";
				$save_table = "weekly_operation_statistics";
				break;
			
			case "monthly":
				$span_query1 = "create_time BETWEEN '".date("Y-m", strtotime($date))."-01'
								AND '".date("Y-m-t", strtotime($date))." 23:59:59'";
				$span_query2 = "l.create_time BETWEEN '".date("Y-m", strtotime("+1 day", strtotime($date)))."-01'
								AND '".date("Y-m-t", strtotime("+1 day", strtotime($date)))." 23:59:59'";
				$save_table = "monthly_operation_statistics";
				break;
				
			default:
			    $span_query1 = "DATE(create_time) = '{$date}'";
			    $span_query2 = " DATE(l.create_time) = DATE_ADD(DATE('{$date}'), INTERVAL {$interval} DAY)";
				$save_table = "operation_statistics";
				break;
		}
        
		if ($span=="daily") {
            $query = $this->DB2->query("
                SELECT 
                    game_id,
                    SUM(is_retention) 'retention',
                    COUNT(facebook_login) 'facebook_count',
                    COUNT(google_login) 'google_count',
                    COUNT(longe_login) 'longe_count',
                    COUNT(quick_login) 'quick_count'
                FROM
                (
                    SELECT 
                        lgl2.game_id,
                        1 'is_retention',
                        CASE WHEN lgl2.external_id LIKE '%facebook' THEN 1 ELSE NULL END 'facebook_login',
                        CASE WHEN lgl2.external_id LIKE '%google' THEN 1 ELSE NULL END 'google_login',
                        CASE WHEN lgl2.external_id IS NULL THEN 1 ELSE NULL END 'longe_login',
                        CASE WHEN lgl2.external_id LIKE '%device' THEN 1 ELSE NULL END 'quick_login'
                    FROM
                    (
						".(($is_first) ? "
                        SELECT 
                            uid, game_id, create_time
                        FROM
                        (
							SELECT 
								uid, game_id, MIN(create_time) 'create_time'
							FROM
								user_server_first_logins
							GROUP BY uid, game_id
						) as fl
                        WHERE
                            DATE(create_time) = '{$date}'
						" : "
						SELECT 
							uid, game_id, MIN(create_time) 'create_time'
						FROM
							log_game_logins
						WHERE
							DATE(create_time) = '{$date}'
						GROUP BY uid, game_id
						")."
                    ) AS lgl,
                    (
                        SELECT 
                            l.uid, l.game_id, MIN(l.create_time), MIN(u.external_id) 'external_id'
                        FROM
                            log_game_logins l
                        JOIN users u ON l.uid=u.uid
                        WHERE
                            ".$span_query2."
                        GROUP BY l.uid, l.game_id
                    ) AS lgl2
                    WHERE
                        lgl2.uid = lgl.uid
                            AND lgl2.game_id = lgl.game_id
                            ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
                ) AS tmp
                GROUP BY game_id");	

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    if ($is_first) {
                        $data = array(
                            'game_id' => $row->game_id,
                            'date' => $date,
                            $update_field => $row->retention,
                            $retention_text.'_facebook_count' => $row->facebook_count,
                            $retention_text.'_google_count' => $row->google_count,
                            $retention_text.'_longe_count' => $row->longe_count,
                            $retention_text.'_quick_count' => $row->quick_count
                        );
                    } else {
                        $data = array(
                            'game_id' => $row->game_id,
                            'date' => $date,
                            $update_field => $row->retention
                        );
                    }
                    
                    $this->save_statistics($data, $save_table);
                }
            }
        } else {
            $query = $this->DB2->query("
                SELECT 
                    game_id, SUM(is_retention) 'retention'
                FROM
                (
                    SELECT 
                        lgl2.game_id, 1 'is_retention'
                    FROM
                    (
						".(($is_first) ? "
                         SELECT 
                            uid, game_id, create_time
                        FROM
                        (
							SELECT 
								uid, game_id, MIN(create_time) 'create_time'
							FROM
								user_server_first_logins
							GROUP BY uid, game_id
						) as fl
                        WHERE
                            {$span_query1}
						" : "
						SELECT 
							uid, game_id, MIN(create_time) 'create_time'
						FROM
							log_game_logins
						WHERE
							{$span_query1}
						GROUP BY uid, game_id
						")."
                    ) AS lgl,
                    (
                        SELECT 
                            l.uid, l.game_id, MIN(l.create_time)
                        FROM
                            log_game_logins l
                        WHERE
                            ".$span_query2."
                        GROUP BY l.uid, l.game_id
                    ) AS lgl2
                    WHERE
                        lgl2.uid = lgl.uid
                            AND lgl2.game_id = lgl.game_id
                            ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
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
        }
		
		echo "generate_".$interval."_retention_".$span."_statistics(".(($is_first) ? "first" : "all").") done - ".$date.PHP_EOL;
	}
	
	function generate_return_statistics($date="", $interval=1, $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-".($interval+1)." days"));
		
        switch($span) {
			case "weekly":
                $return_text = 'return';
				$update_field = 'return_count';
			    $span_query1 = "YEARWEEK(l.create_time, 3) = YEARWEEK('{$date}', 3) AND DATE(l.create_time)<='{$date}'";
                $date_1_week_ago=date("Y-m-d",strtotime("-1 week", strtotime($date)));
                $date_2_week_ago=date("Y-m-d",strtotime("-2 week", strtotime($date)));
                $span_query2 = "create_time <= DATE('{$date_1_week_ago}') AND create_time > DATE('{$date_2_week_ago}')";
				$span_query3 = "DATE(l.create_time) <= DATE('{$date_2_week_ago}')";
				$save_table = "weekly_operation_statistics";
				break;
			
			case "monthly":
                $return_text = 'return';
				$update_field = 'return_count';
			    $span_query1 = "YEAR(l.create_time) = YEAR('{$date}') AND MONTH(l.create_time) = MONTH('{$date}') AND DATE(l.create_time)<='{$date}'";
                $date_1_month_ago=date("Y-m-t",strtotime("-31 days", strtotime($date)));
                $date_2_month_ago=date("Y-m-t",strtotime("-62 days", strtotime($date)));
                $span_query2 = "create_time <= DATE('{$date_1_month_ago}') AND create_time > DATE('{$date_2_month_ago}')";
			    $span_query3 = "DATE(l.create_time) <= DATE('{$date_2_month_ago}')";
				$save_table = "monthly_operation_statistics";
				break;
				
			default:
				switch ($interval) {
					case 1:
                        $return_text = 'one_return';
						$span_query2 = "DATE(create_time) = DATE_SUB(DATE('{$date}'), INTERVAL 1 DAY)";
						$span_query3 = "DATE(l.create_time) <= DATE_SUB(DATE('{$date}'), INTERVAL 2 DAY)";
						break;
					case 3:
                        $return_text = 'three_return';
						$span_query2 = "DATE(create_time) BETWEEN DATE_SUB(DATE('{$date}'), INTERVAL 3 DAY) AND DATE_SUB(DATE('{$date}'), INTERVAL 1 DAY)";
						$span_query3 = "DATE(l.create_time) <= DATE_SUB(DATE('{$date}'), INTERVAL 4 DAY)";
						break;
				}
				$span_query1 = "DATE(l.create_time) = '{$date}'";
				$save_table = "operation_statistics";
				break;
		}
		
        $query = $this->DB2->query("
        SELECT
            lgl_return.game_id,
            lgl_return.{$return_text}_count,
            lgl_return.facebook_count,
            lgl_return.google_count,
            lgl_return.longe_count,
            lgl_return.quick_count,
            lgl_total.total_count,
            lgl_total.total_facebook_count,
            lgl_total.total_google_count,
            lgl_total.total_longe_count,
            lgl_total.total_quick_count
        FROM
        (
			SELECT 
				lgl.game_id,
                COUNT(lgl.uid) '{$return_text}_count',
                COUNT(CASE WHEN lgl.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'facebook_count',
                COUNT(CASE WHEN lgl.external_id LIKE '%google' THEN 1 ELSE NULL END) 'google_count',
                COUNT(CASE WHEN lgl.external_id IS NULL THEN 1 ELSE NULL END) 'longe_count',
                COUNT(CASE WHEN lgl.external_id LIKE '%device' THEN 1 ELSE NULL END) 'quick_count'
			FROM
			(
				SELECT 
					l.uid, l.game_id, MIN(l.create_time) 'create_time', MIN(u.external_id) 'external_id'
				FROM
					log_game_logins l
                JOIN users u ON l.uid=u.uid
				WHERE
					".$span_query1."
                        ".(($this->testaccounts)?" AND l.uid NOT IN (".$this->testaccounts.") ":"")."
				GROUP BY l.uid, l.game_id
			) AS lgl
                JOIN
            (
                SELECT
                    uid, game_id, MIN(create_time) 'create_time'
                FROM 
                    log_game_logins
                WHERE
                    1=1
                GROUP BY uid, game_id
            ) AS all_first ON all_first.uid = lgl.uid AND all_first.game_id = lgl.game_id
				LEFT JOIN
			(
				SELECT 
					uid, game_id, MIN(create_time)
				FROM
					log_game_logins
				WHERE
					".$span_query2."
                        ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
				GROUP BY uid, game_id
			) AS lgl_nologin ON lgl.game_id = lgl_nologin.game_id AND lgl.uid = lgl_nologin.uid
			WHERE
				lgl_nologin.uid IS NULL
				AND lgl.create_time <> all_first.create_time
			GROUP BY lgl.game_id
        ) AS lgl_return
            JOIN
        (
            SELECT 
				lgl_t.game_id,
                COUNT(lgl_t.uid) 'total_count',
                COUNT(CASE WHEN lgl_t.external_id LIKE '%facebook' THEN 1 ELSE NULL END) 'total_facebook_count',
                COUNT(CASE WHEN lgl_t.external_id LIKE '%google' THEN 1 ELSE NULL END) 'total_google_count',
                COUNT(CASE WHEN lgl_t.external_id IS NULL THEN 1 ELSE NULL END) 'total_longe_count',
                COUNT(CASE WHEN lgl_t.external_id LIKE '%device' THEN 1 ELSE NULL END) 'total_quick_count'
			FROM
			(
				SELECT 
					l.uid, l.game_id, MIN(l.create_time), MIN(u.external_id) 'external_id'
				FROM
					(SELECT
                        game_id, uid, MIN(create_time) 'create_time'
                    FROM 
                        log_game_logins
                    WHERE
                        1=1
                            ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                    GROUP BY game_id, uid
                    ) l
                JOIN users u ON l.uid=u.uid
				WHERE
					".$span_query3."
				GROUP BY l.uid, l.game_id
			) AS lgl_t
			GROUP BY lgl_t.game_id
        ) AS lgl_total ON lgl_return.game_id=lgl_total.game_id
		");	
        
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
                $return_count = $return_text.'_count';
		
				$data = array(
					'game_id' => $row->game_id,
					'date' => $date,
					$return_text.'_count' => $row->$return_count,
                    $return_text.'_facebook_count' => $row->facebook_count,
                    $return_text.'_google_count' => $row->google_count,
                    $return_text.'_longe_count' => $row->longe_count,
                    $return_text.'_quick_count' => $row->quick_count,
					$return_text.'_rate' => ($row->total_count)?$row->$return_count/$row->total_count:0,
                    $return_text.'_facebook_rate' => ($row->total_facebook_count)?$row->facebook_count/$row->total_facebook_count:0,
                    $return_text.'_google_rate' => ($row->total_google_count)?$row->google_count/$row->total_google_count:0,
                    $return_text.'_longe_rate' => ($row->total_longe_count)?$row->longe_count/$row->total_longe_count:0,
                    $return_text.'_quick_rate' => ($row->total_quick_count)?$row->quick_count/$row->total_quick_count:0,
				);
				
			    $this->save_statistics($data, $save_table);
		    }
		}
		
		echo "generate_".$interval."_return_".$span."_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_billing_statistics($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
			    $span_query = "YEARWEEK(ub.create_time, 3) = YEARWEEK('{$date}', 3) AND DATE(ub.create_time)<='{$date}'";
				$save_table = "weekly_user_statistics";
				break;
			
			case "monthly":
			    $span_query = "YEAR(ub.create_time)=YEAR('{$date}') AND MONTH(ub.create_time)=MONTH('{$date}') AND DATE(ub.create_time)<='{$date}'";
				$save_table = "monthly_user_statistics";
				break;
				
			default:
				$span_query = "DATE(ub.create_time) = '{$date}'";
				$save_table = "user_statistics";
				break;
		}
		
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
					{$span_query}
						AND ub.billing_type = 2
						AND ub.result = 1
                        ".(($this->testaccounts)?" AND ub.uid NOT IN (".$this->testaccounts.") ":"")."
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
				
			    $this->save_statistics($data, $save_table);
		    }
		}
		echo "generate_billing_{$span}_statistics done - ".$date.PHP_EOL;
	}
	
	function generate_new_user_billing_statistics($date="", $span="daily")
	{
		$this->lang->load('db_lang', 'zh-TW');
		
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
		
        switch($span) {
			case "weekly":
			    $span_query1 = "YEARWEEK(lgl.create_time, 3) = YEARWEEK(ub.create_time, 3)";
			    $span_query2 = "YEARWEEK(ub.create_time, 3) = YEARWEEK('{$date}', 3) AND DATE(ub.create_time)<='{$date}'";
				$save_table = "weekly_user_statistics";
				break;
			
			case "monthly":
			    $span_query1 = "YEAR(lgl.create_time)=YEAR(ub.create_time) AND MONTH(lgl.create_time)=MONTH(ub.create_time)";
			    $span_query2 = "YEAR(ub.create_time) = YEAR('{$date}') AND MONTH(ub.create_time) = MONTH('{$date}') AND DATE(ub.create_time)<='{$date}'";
				$save_table = "monthly_user_statistics";
				break;
				
			default:
				$span_query1 = "DATE(lgl.create_time)=DATE(ub.create_time)";
				$span_query2 = "DATE(ub.create_time) = '{$date}'";
				$save_table = "user_statistics";
				break;
		}
		
		$query = $this->DB2->query("
			SELECT 
				game_id,
				COUNT(uid) 'new_user_deposit_count',
				SUM(amount_total) 'new_user_deposit_total'
			FROM
			(   
				SELECT 
					ub.uid,
					sv.game_id,
					SUM(ub.amount) 'amount_total'
				FROM
					user_billing ub
				JOIN servers sv ON ub.server_id = sv.server_id
                JOIN (SELECT
                        game_id, uid, MIN(create_time) 'create_time'
                    FROM 
                        log_game_logins
                    WHERE
                        1=1
                            ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                    GROUP BY game_id, uid
                    ) lgl ON {$span_query1} AND sv.game_id=lgl.game_id AND ub.uid=lgl.uid
				WHERE 
                    {$span_query2}
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
				    'new_user_deposit_count' => $row->new_user_deposit_count,
				    'new_user_deposit_total' => $row->new_user_deposit_total,
			    );
				
			    $this->save_statistics($data, $save_table);
		    }
		}
		
		echo "generate_new_user_billing_{$span}_statistics done - ".$date.PHP_EOL;
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
                        ".(($this->testaccounts)?" AND lgc.uid NOT IN (".$this->testaccounts.") ":"")."
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
                        ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
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
                        ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
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
                        ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
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
                        ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
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
					SELECT game_id, date,
						COUNT(uid) 'all_login_count',
						COUNT(CASE WHEN user_game_length < 900 THEN 1 ELSE NULL END) 'login_count_15',
						COUNT(CASE WHEN user_game_length >= 900 AND user_game_length < 1800 THEN 1 ELSE NULL END) 'login_count_30',
						COUNT(CASE WHEN user_game_length >= 1800 AND user_game_length < 3600 THEN 1 ELSE NULL END) 'login_count_60',
						COUNT(CASE WHEN user_game_length >= 3600 AND user_game_length < 5400 THEN 1 ELSE NULL END) 'login_count_90',
						COUNT(CASE WHEN user_game_length >= 5400 AND user_game_length < 7200 THEN 1 ELSE NULL END) 'login_count_120',
						COUNT(CASE WHEN user_game_length >= 7200 THEN 1 ELSE NULL END) 'login_count_more'
					FROM
                    (
                        SELECT game_id, DATE(create_time) 'date', uid, SUM(TIMESTAMPDIFF(SECOND, create_time, logout_time)) 'user_game_length' FROM
                            log_game_logins
                        WHERE create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
                            ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                        GROUP BY game_id, DATE(create_time), uid
                    ) AS ugl
					GROUP BY game_id, date
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
					SELECT game_id, date,
						COUNT(uid) 'new_login_count',
						COUNT(CASE WHEN user_game_length < 900 THEN 1 ELSE NULL END) 'new_login_count_15',
						COUNT(CASE WHEN user_game_length >= 900 AND user_game_length < 1800 THEN 1 ELSE NULL END) 'new_login_count_30',
						COUNT(CASE WHEN user_game_length >= 1800 AND user_game_length < 3600 THEN 1 ELSE NULL END) 'new_login_count_60',
						COUNT(CASE WHEN user_game_length >= 3600 AND user_game_length < 5400 THEN 1 ELSE NULL END) 'new_login_count_90',
						COUNT(CASE WHEN user_game_length >= 5400 AND user_game_length < 7200 THEN 1 ELSE NULL END) 'new_login_count_120',
						COUNT(CASE WHEN user_game_length >= 7200 THEN 1 ELSE NULL END) 'new_login_count_more'
					FROM
                    (
                        SELECT all_first.game_id, DATE(all_first.create_time) 'date', all_first.uid, SUM(TIMESTAMPDIFF(SECOND, all_first.create_time, all_first.logout_time)) 'user_game_length' 
                        FROM
                            (SELECT
                                game_id, uid, MIN(create_time) 'create_time', MIN(logout_time) 'logout_time'
                            FROM 
                                log_game_logins
                            WHERE
                                1=1
                                AND logout_time <> '0000-00-00 00:00:00'
                                    ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                            GROUP BY game_id, uid
                            ) all_first
                        WHERE all_first.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
                        GROUP BY all_first.game_id, DATE(all_first.create_time), all_first.uid
                    ) AS ugl2
					GROUP BY game_id, date
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
					SELECT game_id, date,
						COUNT(uid) 'deposit_login_count',
						COUNT(CASE WHEN user_game_length < 900 THEN 1 ELSE NULL END) 'deposit_login_count_15',
						COUNT(CASE WHEN user_game_length >= 900 AND user_game_length < 1800 THEN 1 ELSE NULL END) 'deposit_login_count_30',
						COUNT(CASE WHEN user_game_length >= 1800 AND user_game_length < 3600 THEN 1 ELSE NULL END) 'deposit_login_count_60',
						COUNT(CASE WHEN user_game_length >= 3600 AND user_game_length < 5400 THEN 1 ELSE NULL END) 'deposit_login_count_90',
						COUNT(CASE WHEN user_game_length >= 5400 AND user_game_length < 7200 THEN 1 ELSE NULL END) 'deposit_login_count_120',
						COUNT(CASE WHEN user_game_length >= 7200 THEN 1 ELSE NULL END) 'deposit_login_count_more'
					FROM 
                    (
                        SELECT lgl.game_id, DATE(lgl.create_time) 'date', lgl.uid, SUM(TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time)) 'user_game_length' FROM
                            log_game_logins lgl
                        JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND lgl.create_time >= ub.create_time
                        WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
                            AND ub.billing_type = 2 
                            AND ub.result = 1
                            ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
                        GROUP BY lgl.game_id, DATE(lgl.create_time), lgl.uid
                    ) AS ugl3
					GROUP BY game_id, date
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
					SELECT game_id, date,
						COUNT(uid) 'new_deposit_login_count',
						COUNT(CASE WHEN user_game_length < 900 THEN 1 ELSE NULL END) 'new_deposit_login_count_15',
						COUNT(CASE WHEN user_game_length >= 900 AND user_game_length < 1800 THEN 1 ELSE NULL END) 'new_deposit_login_count_30',
						COUNT(CASE WHEN user_game_length >= 1800 AND user_game_length < 3600 THEN 1 ELSE NULL END) 'new_deposit_login_count_60',
						COUNT(CASE WHEN user_game_length >= 3600 AND user_game_length < 5400 THEN 1 ELSE NULL END) 'new_deposit_login_count_90',
						COUNT(CASE WHEN user_game_length >= 5400 AND user_game_length < 7200 THEN 1 ELSE NULL END) 'new_deposit_login_count_120',
						COUNT(CASE WHEN user_game_length >= 7200 THEN 1 ELSE NULL END) 'new_deposit_login_count_more'
					FROM
                    (
                        SELECT lgl.game_id, DATE(lgl.create_time) 'date', lgl.uid, SUM(TIMESTAMPDIFF(SECOND, lgl.create_time, lgl.logout_time)) 'user_game_length' FROM
                            log_game_logins lgl
                        JOIN user_billing ub ON lgl.uid=ub.uid AND lgl.server_id=ub.server_id AND DATE(lgl.create_time) = DATE(ub.create_time)
                        WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
                            AND ub.billing_type = 2 
                            AND ub.result = 1
                            ".(($this->testaccounts)?" AND lgl.uid NOT IN (".$this->testaccounts.") ":"")."
                        GROUP BY lgl.game_id, DATE(lgl.create_time), lgl.uid
                    ) AS ugl4
					GROUP BY game_id, date
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
			case 2:
			    $update_field = 'two_ltv';
				break;
			case 3:
			    $update_field = 'three_ltv';
				break;
			case 4:
			    $update_field = 'four_ltv';
				break;
			case 5:
			    $update_field = 'five_ltv';
				break;
			case 6:
			    $update_field = 'six_ltv';
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
			    echo "Only 1,2,3,4,5,6,7,14,30,60,90 allowed for second parameter!";
				return 0;
				break;
		}
		
		$query = $this->DB2->query("
			SELECT lgl.game_id, DATE(lgl.create_time) 'date',
				SUM(ub.amount) 'life_time_value'
			FROM 
			    user_billing ub
                JOIN servers s ON ub.server_id=s.server_id
                JOIN
                (SELECT
                    game_id, uid, MIN(create_time) 'create_time'
                FROM 
                    log_game_logins
                WHERE
                    1=1
                        ".(($this->testaccounts)?" AND uid NOT IN (".$this->testaccounts.") ":"")."
                GROUP BY game_id, uid
                ) lgl ON lgl.uid=ub.uid AND s.game_id=lgl.game_id
			WHERE lgl.create_time BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
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
				
					$this->save_statistics($data, "operation_statistics");
			    }
		    }
		}
		echo "generate_new_user_".$interval."_lifetime_value_statistics done - ".$date.PHP_EOL;
	}
	
	function save_statistics($data, $save_table="user_statistics") {
		
        if ($save_table=='marketing_statistics') $this->DB2->where("platform", $data['platform'])->where("country_code", $data['country_code'])->where("media", $data['media']);
		$statistics = $this->DB2->where("game_id", $data['game_id'])->where("date", $data['date'])->get($save_table);
		
		if ($statistics->num_rows() > 0) {
            echo "[update]";
        	if ($save_table=='marketing_statistics') $this->DB1->where("platform", $data['platform'])->where("country_code", $data['country_code'])->where("media", $data['media']);
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
		    $date_3=date("Y-m-d",strtotime("-2 days", strtotime($date)));
		    $date_7=date("Y-m-d",strtotime("-6 days", strtotime($date)));
		    $date_14=date("Y-m-d",strtotime("-13 days", strtotime($date)));
		    $date_30=date("Y-m-d",strtotime("-29 days", strtotime($date)));
		}
        
		$this->generate_statistics_blank($date);
		$this->generate_login_statistics($date);
		$this->generate_new_character_statistics($date);
		$this->generate_device_statistics($date);
		$this->generate_retention_statistics($date_1, 1);
		$this->generate_retention_statistics($date_3, 2);
		$this->generate_retention_statistics($date_7, 6);
		$this->generate_retention_statistics($date_14, 13);
		$this->generate_retention_statistics($date_30, 29);
		$this->generate_retention_statistics($date_1, 1, 'daily', FALSE);
		$this->generate_return_statistics($date, 1);
		$this->generate_return_statistics($date, 3);
		$this->generate_billing_statistics($date);
		$this->generate_new_user_billing_statistics($date);
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
		$this->generate_new_user_lifetime_value_statistics($date, 2);
		$this->generate_new_user_lifetime_value_statistics($date, 3);
		$this->generate_new_user_lifetime_value_statistics($date, 4);
		$this->generate_new_user_lifetime_value_statistics($date, 5);
		$this->generate_new_user_lifetime_value_statistics($date, 6);
		$this->generate_new_user_lifetime_value_statistics($date, 7);
		$this->generate_new_user_lifetime_value_statistics($date, 14);
		$this->generate_new_user_lifetime_value_statistics($date, 30);
		$this->generate_new_user_lifetime_value_statistics($date, 60);
		$this->generate_new_user_lifetime_value_statistics($date, 90);
        
		
		if ("7"==date("N", strtotime($check_date))) {
			$this_sunday = $date;
			$date_week=date("Y-m-d",strtotime("-1 week", strtotime($check_date)));
		} else {
			if ("1"==date("N", strtotime($check_date))) {$start_monday = date("Y-m-d");}
			else {$start_monday = date("Y-m-d", strtotime('last monday'));}

			$this_sunday = date("Ymd", strtotime($start_monday.' this sunday'));
			$date_week=date("Y-m-d",strtotime("-1 week", strtotime($this_sunday)));
		}
		$this->generate_statistics_blank($this_sunday, 'weekly');
		$this->generate_device_statistics($this_sunday, 'weekly');
		$this->generate_login_statistics($this_sunday, 'weekly');
		$this->generate_return_statistics($this_sunday, 1, 'weekly');
		$this->generate_billing_statistics($this_sunday, 'weekly');
		$this->generate_new_user_billing_statistics($this_sunday, 'weekly');

		$this->generate_retention_statistics($date_week, 1, 'weekly');
		$this->generate_retention_statistics($date_week, 1, 'weekly', FALSE);
		
		
		$month_end = date("Y-m-t", strtotime($check_date));

		$this->generate_statistics_blank($month_end, 'monthly');
		$this->generate_device_statistics($month_end, 'monthly');
		$this->generate_login_statistics($month_end, 'monthly');
		$this->generate_return_statistics($month_end, 1, 'monthly');
		$this->generate_billing_statistics($month_end, 'monthly');
		$this->generate_new_user_billing_statistics($month_end, 'monthly');

		$date_month=date("Y-m-t",strtotime("-31 days", strtotime($check_date)));
		$this->generate_retention_statistics($date_month, 1, 'monthly');
		$this->generate_retention_statistics($date_month, 1, 'monthly', FALSE);
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
    
    function mongo_log_data($date="") {
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        
        $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
        $query = new MongoDB\Driver\Query([
            "uid" => null,
            $query_time => ['$gte' => $date]
        ]);
        
        $cursor = $manager->executeQuery("longe_log.le_AppStart", $query);

        $result = [];
        
        foreach ($cursor as $document) {
            $result[] = $document;
        }
            
        foreach($result as $row) {
            $user_info = $this->db->from("user_info")
                ->where("uid", $row['uid'])->get()->row();
                
            if (strcasecmp($row['le_deviceType'], 'Android') == 0 && !$user_info->is_android_device) {
                $this->db->where("uid", $row['uid'])->update("user_info", array("is_android_device" => 1));
            } elseif(strcasecmp($row['le_deviceType'], 'iOS') == 0 && !$user_info->is_ios_device) {
                $this->db->where("uid", $row['uid'])->update("user_info", array("is_ios_device" => 1));
            }
        }
    }
            
    function mongo_user_online_sharp() {
        $date = date('Y-m-d', time()); 
        $hour = date('G', time());
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        
        $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
        $query = new MongoDB\Driver\Query([]);
        
        $cursor = $manager->executeQuery("longe_log.user_count", $query);

        $result = [];
        
        foreach ($cursor as $document) {
            $result[] = $document;
        }
        
        foreach($result as $row) {
            $filter = ['game_id' => $row->game_id, "server_id" => $row->server_id, "date" => $date, "hour" => intval($hour)];
            $newObj = ['$set' => ['sharp' => $row->count]];
            
            $options = ["multi" => false, "upsert" => true];
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update($filter, $newObj, $options);

            $manager->executeBulkWrite("longe_log.user_online", $bulk);
            unset($bulk);
        }
    }
            
    function mongo_user_recount() {
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        
        $manager = new MongoDB\Driver\Manager($g_mongodb['url']);
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete([], ["limit" => 0]);

        $deleteresult = $manager->executeBulkWrite("longe_log.user_count", $bulk);
        unset($bulk);
        
        
        $query = new MongoDB\Driver\Query([]);
        
        $cursor = $manager->executeQuery("longe_log.users", $query);

        $result = [];
        
        foreach ($cursor as $document) {
            $result[] = $document;
        }
        
        $user_online = array();
        
        foreach($result as $row) {
            if (!isset($user_online[$row->game_id][$row->server_id])) $user_online[$row->game_id][$row->server_id] = 0;
            
            $idle_time = time() - $row->latest_update_time;
            
            if ($idle_time < 2*60*60) $user_online[$row->game_id][$row->server_id]+=1;
        }
        
        foreach($user_online as $game => $servers) {
            foreach($servers as $server => $new_count) {
                $filter = ['game_id' => $game, "server_id" => $server];
                $newObj = ['$set' => ['count' => $new_count]];
                
                $options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($filter, $newObj, $options);

                $manager->executeBulkWrite("longe_log.user_count", $bulk);
                unset($bulk);
            }
        }
    }
    
    function appannie_data($date="", $device="ios") {
        
		if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
        $product_id = "328855";
        $countries = array("", "TW", "HK", "MO", "SG", "MY");
        
        foreach ($countries as $country_code) {
            
            $authorization = "Authorization: Bearer 8b042668428cc0edda9b57e34af139c953e6a587";
            $url = "https://api.appannie.com/v1.2/accounts/{$product_id}/sales?start_date={$date}&end_date={$date}";
            
            $url .= ($country_code)?"&countries={$country_code}":"";
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            $result_json = json_decode($result);
            curl_close($ch);
            
            $update_field = ($country_code)?"{$device}_".strtolower($country_code)."_download_count":"{$device}_download_count";
            
            $data = array(
                'game_id' => 'stm',
                'date' => $date,
                $update_field => (isset($result_json->sales_list[0]->units->product->downloads))?$result_json->sales_list[0]->units->product->downloads:0
            );
        
            $this->save_statistics($data);
        }
    }
	
	function appannie_que($date) {
		ini_set('max_execution_time', 99999);
		
		$run_date = $date;
		
		for ($run_date; $run_date <= date('Y-m-d'); $run_date=date("Y-m-d",strtotime('+1 day', strtotime($run_date)))) {
			echo '['.$run_date.']'.PHP_EOL;
			$this->appannie_data($run_date);
		}
	}
    
    function appsflyer_data($start_date="", $end_date="") {
            
		if (empty($start_date)) $start_date=date("Y-m-d",strtotime("-1 days"));
		if (empty($end_date)) $end_date=$start_date;
        
        $this->load->config('appsflyer');
        $appsflyer_api = $this->config->item("appsflyer_api");
        
        foreach ($appsflyer_api as $game_id => $devices) {
            foreach ($devices as $device => $codes) {
                
                $app_id = $codes['app_id'];
                $api_token = $codes['api_token'];
                
                $downloadUrl = "https://hq.appsflyer.com/export/{$app_id}/geo_by_date_report/v4?api_token={$api_token}&from={$start_date}&to={$end_date}";      

                $filePath = "p/data/appsflyer.csv";
                $file = fopen($filePath, "w+");
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $downloadUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //curl_setopt($ch, CURLOPT_HEADER, true); 
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                
                $result = curl_exec($ch);
                
                fwrite($file, $result);

                fclose($file);
                curl_close($ch);
                
                $file = fopen("p/data/appsflyer.csv", "r");
                
                $insert_arr = array();
                $title_arr =array();
                $i = 0;
                
                while (!feof($file)) {
                    $data = fgetcsv($file);
                    
                    if ($i == 0) {
                        $title_arr = array_flip($data);
                    }
                    
                    if ($i && isset($data[0])) {
                        $d = date("Y-m-d",strtotime($data[0]));
                        
                        $country = $data[1];
                        if (stripos($data[3], "Facebook") !== false) {
                            $media = 'facebook';
                        } elseif (stripos($data[3], "Appsflyer") !== false) {
                            $media = 'appsflyer';
                        } else {
                            $media = 'organic';
                        }
                        
                        $clicks                         = $data[6];
                        $installs                       = $data[8];
                        $af_login_unique                = $data[$title_arr['af_login (Unique users)']];
                        $af_login                       = $data[$title_arr['af_login (Event counter)']];
                        $af_login_sales                 = $data[$title_arr['af_login (Sales in USD)']];
                        $le_usercharactercreate_unique  = $data[$title_arr['le_usercharactercreate (Unique users)']];
                        $le_usercharactercreate         = $data[$title_arr['le_usercharactercreate (Event counter)']];
                        $le_usercharactercreate_sales   = $data[$title_arr['le_usercharactercreate (Sales in USD)']];
                        $le_usercharacterlevelup_unique = $data[$title_arr['le_usercharacterlevelup (Unique users)']];
                        $le_usercharacterlevelup        = $data[$title_arr['le_usercharacterlevelup (Event counter)']];
                        $le_usercharacterlevelup_sales  = $data[$title_arr['le_usercharacterlevelup (Sales in USD)']];
                        $pay_unique_event_count         = $data[$title_arr['af_purchase (Unique users)']];
                        $pay_event_count                = $data[$title_arr['af_purchase (Event counter)']];
                        $pay_amount                     = $data[$title_arr['af_purchase (Sales in USD)']];
                        
                        if (isset($insert_arr[$d][$country][$media]['clicks'])) $insert_arr[$d][$country][$media]['clicks']                                                 += intval($clicks);
                        else $insert_arr[$d][$country][$media]['clicks'] = intval($clicks);
                        if (isset($insert_arr[$d][$country][$media]['installs'])) $insert_arr[$d][$country][$media]['installs']                                             += intval($installs);
                        else $insert_arr[$d][$country][$media]['installs'] = intval($installs);
                        if (isset($insert_arr[$d][$country][$media]['af_login_unique'])) $insert_arr[$d][$country][$media]['af_login_unique']                               += $af_login_unique;
                        else $insert_arr[$d][$country][$media]['af_login_unique'] = $af_login_unique;
                        if (isset($insert_arr[$d][$country][$media]['af_login'])) $insert_arr[$d][$country][$media]['af_login']                                             += $af_login;
                        else $insert_arr[$d][$country][$media]['af_login'] = $af_login;
                        if (isset($insert_arr[$d][$country][$media]['af_login_sales'])) $insert_arr[$d][$country][$media]['af_login_sales']                                 += $af_login_sales;
                        else $insert_arr[$d][$country][$media]['af_login_sales'] = $af_login_sales;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharactercreate_unique'])) $insert_arr[$d][$country][$media]['le_usercharactercreate_unique']   += $le_usercharactercreate_unique;
                        else $insert_arr[$d][$country][$media]['le_usercharactercreate_unique'] = $le_usercharactercreate_unique;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharactercreate'])) $insert_arr[$d][$country][$media]['le_usercharactercreate']                 += $le_usercharactercreate;
                        else $insert_arr[$d][$country][$media]['le_usercharactercreate'] = $le_usercharactercreate;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharactercreate_sales'])) $insert_arr[$d][$country][$media]['le_usercharactercreate_sales']     += $le_usercharactercreate_sales;
                        else $insert_arr[$d][$country][$media]['le_usercharactercreate_sales'] = $le_usercharactercreate_sales;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharacterlevelup_unique'])) $insert_arr[$d][$country][$media]['le_usercharacterlevelup_unique'] += $le_usercharacterlevelup_unique;
                        else $insert_arr[$d][$country][$media]['le_usercharacterlevelup_unique'] = $le_usercharacterlevelup_unique;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharacterlevelup'])) $insert_arr[$d][$country][$media]['le_usercharacterlevelup']               += $le_usercharacterlevelup;
                        else $insert_arr[$d][$country][$media]['le_usercharacterlevelup'] = $le_usercharacterlevelup;
                        if (isset($insert_arr[$d][$country][$media]['le_usercharacterlevelup_sales'])) $insert_arr[$d][$country][$media]['le_usercharacterlevelup_sales']   += $le_usercharacterlevelup_sales;
                        else $insert_arr[$d][$country][$media]['le_usercharacterlevelup_sales'] = $le_usercharacterlevelup_sales;
                        if (isset($insert_arr[$d][$country][$media]['pay_unique_event_count'])) $insert_arr[$d][$country][$media]['pay_unique_event_count']                 += $pay_unique_event_count;
                        else $insert_arr[$d][$country][$media]['pay_unique_event_count'] = $pay_unique_event_count;
                        if (isset($insert_arr[$d][$country][$media]['pay_event_count'])) $insert_arr[$d][$country][$media]['pay_event_count']                               += $pay_event_count;
                        else $insert_arr[$d][$country][$media]['pay_event_count'] = $pay_event_count;
                        if (isset($insert_arr[$d][$country][$media]['pay_amount'])) $insert_arr[$d][$country][$media]['pay_amount']                                         += $pay_amount;
                        else $insert_arr[$d][$country][$media]['pay_amount'] = $pay_amount;
                    }
                    $i++;
                }
                
                if ($i) {
                    foreach ($insert_arr as $in_date => $countries) {
                        foreach ($countries as $country => $medias) {
                            foreach ($medias as $media => $data) {
                                
                                $in_data = array(
                                    'date'                           => $in_date,
                                    'game_id'                        => $game_id,
                                    'platform'                       => $device,
                                    'country_code'                   => $country,
                                    'media'                          => $media,
                                    'click_count'                    => $data['clicks'],
                                    'install_count'                  => $data['installs'],
                                    'af_login_unique'                => $data['af_login_unique'],
                                    'af_login'                       => $data['af_login'],
                                    'af_login_sales'                 => $data['af_login_sales'],
                                    'le_usercharactercreate_unique'  => $data['le_usercharactercreate_unique'],
                                    'le_usercharactercreate'         => $data['le_usercharactercreate'],
                                    'le_usercharactercreate_sales'   => $data['le_usercharactercreate_sales'],
                                    'le_usercharacterlevelup_unique' => $data['le_usercharacterlevelup_unique'],
                                    'le_usercharacterlevelup'        => $data['le_usercharacterlevelup'],
                                    'le_usercharacterlevelup_sales'  => $data['le_usercharacterlevelup_sales'],
                                    'pay_unique_event_count'         => $data['pay_unique_event_count'],
                                    'pay_event_count'                => $data['pay_event_count'],
                                    'pay_amount'                     => $data['pay_amount']
                                );
                                
                                $this->save_statistics($in_data, 'marketing_statistics');
                            }
                        }
                    }
                }

                fclose($file);
            }
        }
    }
    
	function appsflyer_que($date) {
		ini_set('max_execution_time', 99999);
		
		$run_date = $date;
		
		for ($run_date; $run_date <= date('Y-m-d'); $run_date=date("Y-m-d",strtotime('+1 day', strtotime($run_date)))) {
			echo '['.$run_date.']'.PHP_EOL;
			$this->appsflyer_data($run_date);
		}
	}
    
    
    function Update_Country_by_ip($date="")
    {
        
        if (empty($date)) $date=date("Y-m-d",strtotime("-1 days"));
        $resultString="";
        //找出user_info 中國家欄位是空的
        $query = $this->DB1->from("user_info")->where("country",NULL)->order_by("id desc")->limit(300)->get();
        //var $tmpCount=0;

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                // 用uid 到 login_logs 找出ip
                $queryIP = $this->DB1->from("log_logins")->where("uid",$row->uid)->order_by("create_time desc")->limit(1)->get()->row()->ip;
                if (!empty($queryIP)  ) { 
                // geoip_country_code3_by_name 找出國家
                    $user_countryCode= geoip_country_code3_by_name($queryIP);
                    if (!empty($user_countryCode))
                    {
                    $this->DB1->where("uid", $row->uid)->update("user_info", array("country" =>$user_countryCode ));
                    //$tmpCount++;
                    
                    }

                }
            }
        }
        
       // echo "Update_Country_by_ip DONE, Total records updated: ".$tmpCount ;

    }
    function Update_Whale_Users()
    {
        
            $game_id='vxz';
            $query = $this->DB2->query("
			SELECT 
				whales.uid 'uid',
				chr.name 'character_name',
                chr.in_game_id  'character_in_game_id',
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
                    HAVING SUM(ub.amount) >= 10000
					ORDER BY SUM(ub.amount) DESC
				) whales
					JOIN games gm ON whales.game_id = gm.game_id
					LEFT JOIN 
				( 
					SELECT
						uid,
						server_id,
						MIN(create_time) 'create_time',
						name, in_game_id
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
		
        //run那個很複雜的query 把京魚用戶找出
        
  
    
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                // 用uid 找最後上線紀錄

                $last_login = $this->DB2->from("log_game_logins")->where("uid",$row->uid)->order_by("create_time desc")->limit(1)->get()->row()->create_time;
                if (!empty($last_login)  ) { 
                    $updateSql="INSERT INTO whale_users (uid,char_name,char_in_game_id,server_name,deposit_total,account_create_time,last_login,site) 
                        VALUES('$row->uid','{$row->character_name}' ,'{$row->character_in_game_id}', '{$row->server_name}', '{$row->deposit_total}', '{$row->create_date}', '{$last_login}','{$game_id}')
                        ON DUPLICATE KEY UPDATE    
                        last_login='{$last_login}',deposit_total='{$row->deposit_total}'";

                    // 一筆一筆查看whale_users 中是否存在, 存在的就更新最後上線日期和目前儲值點數的欄位, 不存在就整筆新增

                    $query = $this->DB1->query($updateSql);


                }
            }
        }
    
    
    
        //把第一階段的儲值滿五千且有加入line的15個人加入
        
        
        $query = $this->DB2->query("
			SELECT 
				whales.uid 'uid',
				chr.name 'character_name',
                chr.in_game_id  'character_in_game_id',
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
                        ub.uid in('16069','120538','114478','31863','120414','107690','29748','80848','104696','59680','94859','75821','81044','78252','72022')
						AND ub.billing_type = 2
						AND ub.result = 1
						AND svr.game_id = '{$game_id}'
						AND ta.uid IS NULL
					GROUP BY ub.uid
					ORDER BY SUM(ub.amount) DESC
				) whales
					JOIN games gm ON whales.game_id = gm.game_id
					LEFT JOIN 
				( 
					SELECT
						uid,
						server_id,
						MIN(create_time) 'create_time',
						name, in_game_id
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
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                // 用uid 找最後上線紀錄

                $last_login = $this->DB2->from("log_game_logins")->where("uid",$row->uid)->order_by("create_time desc")->limit(1)->get()->row()->create_time;
                if (!empty($last_login)  ) { 
                    $updateSql="INSERT INTO whale_users (uid,char_name,char_in_game_id,server_name,deposit_total,account_create_time,last_login,site) 
                        VALUES('$row->uid','{$row->character_name}' ,'{$row->character_in_game_id}', '{$row->server_name}', '{$row->deposit_total}', '{$row->create_date}', '{$last_login}','{$game_id}')
                        ON DUPLICATE KEY UPDATE    
                        last_login='{$last_login}',deposit_total='{$row->deposit_total}'";

                    // 一筆一筆查看whale_users 中是否存在, 存在的就更新最後上線日期和目前儲值點數的欄位, 不存在就整筆新增

                    $query = $this->DB1->query($updateSql);


                }
            }
        }
        
    
        
       // echo "Update_Country_by_ip DONE, Total records updated: ".$tmpCount ;

    }

	function bulletin_news()
	{
		$query = $this->DB2
			->where("type", 99)
			->where("now() between start_time and end_time", null, false)
			->order_by("game_id, start_time")->get("bulletins");
		
		if ($query->num_rows() > 0) {
		    foreach ($query->result() as $row) {
				
				$filePath = "p/file/";
				$filePath .= "news";
				$filePath .= ($row->game_id) ? "_".$row->game_id : "";
				$filePath .= ".txt";

				$file = fopen($filePath, "w+");

				fwrite($file, $row->content);

				fclose($file);
			}
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
