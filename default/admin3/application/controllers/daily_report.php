<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily_report extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		//error_reporting(E_ALL);
		ini_set('display_errors','On');
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

		if ($this->zacl->check_acl("game_statistics", "read")) {

            $account_query = $this->account_data();
						$h35_stat_query = $this->negame_statistics_data('h35naxx1hmt');
						$L8na_stat_query = $this->negame_statistics_data('L8na');

						//$h35_ranking = array();
						//$h35_type_data = array();
						//
						// if ($h35_stat_query->num_rows() > 0) {
						// 		foreach ($h35_stat_query->result() as $row) {
						// 			$h35_ranking[$row->oDate] =  $this->negame_daily_top_data($row->oDate,'h35naxx1hmt')->result();
						// 			$h35_type_data[$row->oDate] =  $this->negame_daily_type_data($row->oDate,'h35naxx1hmt')->result();
						// 		}
						// }
						//
						// $L8na_ranking = array();
						// $L8na_type_data = array();
						//
						// if ($L8na_stat_query->num_rows() > 0) {
						// 		foreach ($L8na_stat_query->result() as $row) {
						// 			$L8na_ranking[$row->oDate] =  $this->negame_daily_top_data($row->oDate,'L8na')->result();
						// 			$L8na_type_data[$row->oDate] =  $this->negame_daily_type_data($row->oDate,'L8na')->result();
						// 		}
						// }


            $is_game_statistics = true;
        }

		$this->g_layout
			->set("account_query", isset($account_query) ? $account_query : false)
			->set("h35_stat_query", isset($h35_stat_query) ? $h35_stat_query : false)
			->set("L8na_stat_query", isset($L8na_stat_query) ? $L8na_stat_query : false)
			->set("is_game_statistics", isset($is_game_statistics) ? $is_game_statistics : false)
			->add_js_include("fontawesome/js/fontawesome-all")
			->render();
	}

	function send_mail()
	{
		//$this->_init_statistics_layout();
		//$this->load->helper("output_table");

		//$this->zacl->check("game_statistics", "read");
		$account_query = $this->account_data();
		$statistics_query = $this->statistics_data();
		$billing_query = $this->billing_data();
		$event_query = $this->event_data();

        $message = "<html>
            <head>
                <meta charset='UTF-8'>
                <style>.hdr{font-weight:bold;}table,td{border:1px solid #000;text-align:right;}.th1{background-color:#faafaf;text-align:center;}.th2{background-color:#91a5e8;text-align:center;}.th3{background-color:#f8c88f;text-align:center;}.num{text-align:right;}</style>
            </head>
            <body>
                <p></p>";

        if ($account_query) {
            if ($account_query->num_rows() == 0) {
                $message .= '<div class="none">查無資料</div>';
            } else {
                $row = $account_query->row();
                $message .= "<div class='hdr'>龍邑平台帳號數據(即時)</div>
                <table>
                    <tr>
                        <td class='th2'>帳號類型</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."前</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-4 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-3 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-2 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-1 days"))."</td>
                        <td class='th2'>本日數量</td>
                        <td class='th2'>目前總數</td>
                    </tr>
                    <tr>
                        <td class='th1'>所有類型</td>
                        <td>".$row->y6_newuser_count."</td>
                        <td>".$row->y5_newuser_count."</td>
                        <td>".$row->y4_newuser_count."</td>
                        <td>".$row->y3_newuser_count."</td>
                        <td>".$row->y2_newuser_count."</td>
                        <td>".$row->y_newuser_count."</td>
                        <td>".$row->t_newuser_count."</td>
                        <td>".$row->newuser_count."</td>
                    </tr>
                    <tr>
                        <td class='th1'>龍邑會員</td>
                        <td>".$row->y6_newuser_longe_count."</td>
                        <td>".$row->y5_newuser_longe_count."</td>
                        <td>".$row->y4_newuser_longe_count."</td>
                        <td>".$row->y3_newuser_longe_count."</td>
                        <td>".$row->y2_newuser_longe_count."</td>
                        <td>".$row->y_newuser_longe_count."</td>
                        <td>".$row->t_newuser_longe_count."</td>
                        <td>".$row->newuser_longe_count."</td>
                    </tr>
                    <tr>
                        <td class='th1'>Facebook</td>
                        <td>".$row->y6_newuser_facebook_count."</td>
                        <td>".$row->y5_newuser_facebook_count."</td>
                        <td>".$row->y4_newuser_facebook_count."</td>
                        <td>".$row->y3_newuser_facebook_count."</td>
                        <td>".$row->y2_newuser_facebook_count."</td>
                        <td>".$row->y_newuser_facebook_count."</td>
                        <td>".$row->t_newuser_facebook_count."</td>
                        <td>".$row->newuser_facebook_count."</td>
                    </tr>
                    <tr>
                        <td class='th1'>Google</td>
                        <td>".$row->y6_newuser_google_count."</td>
                        <td>".$row->y5_newuser_google_count."</td>
                        <td>".$row->y4_newuser_google_count."</td>
                        <td>".$row->y3_newuser_google_count."</td>
                        <td>".$row->y2_newuser_google_count."</td>
                        <td>".$row->y_newuser_google_count."</td>
                        <td>".$row->t_newuser_google_count."</td>
                        <td>".$row->newuser_google_count."</td>
                    </tr>
                    <tr>
                        <td class='th1'>直接玩</td>
                        <td>".$row->y6_newuser_quick_count."</td>
                        <td>".$row->y5_newuser_quick_count."</td>
                        <td>".$row->y4_newuser_quick_count."</td>
                        <td>".$row->y3_newuser_quick_count."</td>
                        <td>".$row->y2_newuser_quick_count."</td>
                        <td>".$row->y_newuser_quick_count."</td>
                        <td>".$row->t_newuser_quick_count."</td>
                        <td>".$row->newuser_quick_count."</td>
                    </tr>
                </table>
                </td></tr><br />";
            }
        }

        if ($statistics_query) {
            $game_id = '';

            foreach($statistics_query->result() as $row) {
                if ($game_id <> '' && $game_id <> $row->game_id) {
                    $message .= "</table><br />";
                }
                if ($game_id == '' || $game_id <> $row->game_id) {
                    $game_id = $row->game_id;
                    $message .= "<div class='hdr'>".$row->name."統計數據(每日更新)</div>
                <table>
                    <tr>
                        <td class='th2'>日期</td>
                        <td class='th2'>新增用戶數</td>
                        <td class='th2'>總創角數</td>
                        <td class='th2'>不重複創角數</td>
                        <td class='th2'>DAU</td>
                        <td class='th2'>1日留存率</td>
                        <td class='th2'>3日留存率</td>
                    </tr>";
                }

                $message .= "<tr>
				<td class='th1'>".date("m/d", strtotime($row->date))."</td>
				<td>".$row->new_login_count."</td>
				<td>".$row->total_new_character_count."</td>
				<td>".$row->new_character_count."</td>
				<td>".$row->login_count."</td>
				<td>".number_format(($row->new_login_count)?$row->one_retention_count*100/$row->new_login_count:0, 2)."</td>
				<td>".number_format(($row->new_login_count)?$row->three_retention_count*100/$row->new_login_count:0, 2)."</td>
			</tr>";
            }

            $message .= "</table><br />";
        }

        if ($billing_query) {
            foreach($billing_query->result() as $row) {
                $message .= "<div class='hdr'>".$row->name."儲值數據(即時)</div>
                <table>
                    <tr>
                        <td class='th2'>儲值管道</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."前</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-4 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-3 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-2 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-1 days"))."</td>
                        <td class='th2'>本日數量</td>
                        <td class='th2'>目前總數</td>
                    </tr>
                    <tr>
                        <td class='th1'>所有管道</td>
                        <td>".$row->y6_total."</td>
                        <td>".$row->y5_total."</td>
                        <td>".$row->y4_total."</td>
                        <td>".$row->y3_total."</td>
                        <td>".$row->y2_total."</td>
                        <td>".$row->y_total."</td>
                        <td>".$row->t_total."</td>
                        <td>".$row->total."</td>
                    </tr>
                    <tr>
                        <td class='th1'>Mycard</td>
                        <td>".$row->y6_mycard_total."</td>
                        <td>".$row->y5_mycard_total."</td>
                        <td>".$row->y4_mycard_total."</td>
                        <td>".$row->y3_mycard_total."</td>
                        <td>".$row->y2_mycard_total."</td>
                        <td>".$row->y_mycard_total."</td>
                        <td>".$row->t_mycard_total."</td>
                        <td>".$row->mycard_total."</td>
                    </tr>
                    <tr>
                        <td class='th1'>天天賺</td>
                        <td>".$row->y6_funapp_total."</td>
                        <td>".$row->y5_funapp_total."</td>
                        <td>".$row->y4_funapp_total."</td>
                        <td>".$row->y3_funapp_total."</td>
                        <td>".$row->y2_funapp_total."</td>
                        <td>".$row->y_funapp_total."</td>
                        <td>".$row->t_funapp_total."</td>
                        <td>".$row->funapp_total."</td>
                    </tr>
                    <tr>
                        <td class='th1'>VIP</td>
                        <td>".$row->y6_vip_total."</td>
                        <td>".$row->y5_vip_total."</td>
                        <td>".$row->y4_vip_total."</td>
                        <td>".$row->y3_vip_total."</td>
                        <td>".$row->y2_vip_total."</td>
                        <td>".$row->y_vip_total."</td>
                        <td>".$row->t_vip_total."</td>
                        <td>".$row->vip_total."</td>
                    </tr>
                    <tr>
                        <td class='th1'>AppStore</td>
                        <td>".$row->y6_ios_total."</td>
                        <td>".$row->y5_ios_total."</td>
                        <td>".$row->y4_ios_total."</td>
                        <td>".$row->y3_ios_total."</td>
                        <td>".$row->y2_ios_total."</td>
                        <td>".$row->y_ios_total."</td>
                        <td>".$row->t_ios_total."</td>
                        <td>".$row->ios_total."</td>
                    </tr>
                    <tr>
                        <td class='th1'>GooglePlay</td>
                        <td>".$row->y6_google_total."</td>
                        <td>".$row->y5_google_total."</td>
                        <td>".$row->y4_google_total."</td>
                        <td>".$row->y3_google_total."</td>
                        <td>".$row->y2_google_total."</td>
                        <td>".$row->y_google_total."</td>
                        <td>".$row->t_google_total."</td>
                        <td>".$row->google_total."</td>
                    </tr>
                </table><br />
                </td></tr>";
            }
        }

        if ($event_query && $event_query->num_rows()>0) {
            $message .= "<div class='hdr'>活動數據(即時)</div>
                <table>
                    <tr>
                        <td class='th2'>活動名稱</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."前</td>
                        <td class='th2'>".date("m/d", strtotime("-5 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-4 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-3 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-2 days"))."</td>
                        <td class='th2'>".date("m/d", strtotime("-1 days"))."</td>
                        <td class='th2'>本日數量</td>
                        <td class='th2'>目前總數</td>
                    </tr>";
            foreach($event_query->result() as $row) {
                $message .= "
                    <tr>
                        <td class='th1'>【".$row->name."】".$row->event_name."</td>
                        <td>".$row->y6_serial_count."</td>
                        <td>".$row->y5_serial_count."</td>
                        <td>".$row->y4_serial_count."</td>
                        <td>".$row->y3_serial_count."</td>
                        <td>".$row->y2_serial_count."</td>
                        <td>".$row->y_serial_count."</td>
                        <td>".$row->t_serial_count."</td>
                        <td>".$row->serial_count."</td>
                    </tr>";
            }
            $message .= "
                </table>
                </td></tr>";
        }

        $message .= "</table>
            </body>
        </html>";

        $this->load->library('email');

		$config['protocol']  = 'smtp';
        $config['smtp_user'] = 'no-reply@longeplay.com.tw';
        $config['smtp_pass'] = 'noxj/6u4reply';
        $config['smtp_host'] = 'mail.longeplay.com.tw';
        $config['mailtype']  = 'html';

        $this->email->initialize($config);

        $this->email->from('no-reply@longeplay.com.tw', '龍邑自動報表系統');

        $tos = $this->DB2->where_in("role", array("dev", "pm", "cs_master", "mo"))->where('password IS NULL', null, false)->get("admin_users");

        $tos_string = 'joe@cooz.com.tw,';
        if ($tos) {
            foreach($tos->result() as $row) {
                $tos_string .= $row->account."@longeplay.com.tw,";
            }
        }
        $tos_string = rtrim($tos_string, ",");

        $this->email->to($tos_string);

        $this->email->subject('龍邑活動日報<'.date("Y/m/d").'>');
        $this->email->message($message);

        $this->email->send();

        die();
	}

    function account_data() {
        $query = $this->DB2->query("
          SELECT * FROM account_data_daily
		");

        return $query;
    }


		function negame_statistics_data($game_id) {

			$query = $this->DB2->query("
			SELECT  date,game_id,topup_sum,topup_count,chanel_dist,top_users
			FROM vip_daily_sum
			WHERE game_id = '{$game_id}'
			ORDER BY date desc limit 14");
			return $query;

		}


		function negame_daily_top_data($date,$game_id) {

			$query = $this->DB2->query("
			SELECT DATE_FORMAT(create_time, '%Y-%m-%d') as oDate,role_name,sum(amount) as oSum
			FROM negame_orders
			WHERE  game_id = '{$game_id}' and  DATE_FORMAT(create_time, '%Y-%m-%d') ='{$date}'
			GROUP BY DATE_FORMAT(create_time, '%Y-%m-%d'),role_name
			ORDER BY oSum desc limit 5");
			return $query;

		}


		function negame_daily_type_data($date,$game_id) {

			$query = $this->DB2->query("
			SELECT DATE_FORMAT(create_time, '%Y-%m-%d') as oDate,transaction_type,sum(amount) as oSum
			FROM negame_orders
			WHERE game_id = '{$game_id}' and DATE_FORMAT(create_time, '%Y-%m-%d') ='{$date}'
			GROUP BY DATE_FORMAT(create_time, '%Y-%m-%d'),transaction_type"
			);
			return $query;

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
            ORDER BY us.game_id DESC, us.date ASC
		");

        return $query;
    }

    function billing_data() {
        $query = $this->DB2->query("
            SELECT * FROM billing_data_daily;
		");

        return $query;
    }

    function event_data() {
        $query = $this->DB2->query("
            SELECT
                e.game_id,
                es.event_id,
                g.name,
                e.event_name,
                COUNT(*) 'serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=CURDATE() THEN 1 ELSE NULL END) 't_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 ELSE NULL END) 'y_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=DATE_SUB(CURDATE(), INTERVAL 2 DAY) THEN 1 ELSE NULL END) 'y2_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=DATE_SUB(CURDATE(), INTERVAL 3 DAY) THEN 1 ELSE NULL END) 'y3_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=DATE_SUB(CURDATE(), INTERVAL 4 DAY) THEN 1 ELSE NULL END) 'y4_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)=DATE_SUB(CURDATE(), INTERVAL 5 DAY) THEN 1 ELSE NULL END) 'y5_serial_count',
                COUNT(CASE WHEN DATE(es.create_time)<=DATE_SUB(CURDATE(), INTERVAL 6 DAY) THEN 1 ELSE NULL END) 'y6_serial_count'
            FROM
                event_serial es
            JOIN events e ON es.event_id=e.id
            JOIN games g ON e.game_id=g.game_id
            WHERE es.status=1
                AND e.status=1
                AND e.end_time>DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY e.game_id, es.event_id, e.event_name
		");

        return $query;
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
