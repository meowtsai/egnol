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
				SUM(CASE WHEN ub.transaction_type='funapp_billing' THEN amount ELSE 0 END) 'funapp_sum',
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
            case "天天賺":
                $sum_type = 'funapp_sum';
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
    $orderby = $this->input->get("orderby");
		$sum_condition = 50000;
		$report_result = [];
		if ($game_id)
		{
			header("Cache-Control: private");
			$this->DB2->start_cache();
			if ($this->input->get("start_date")) {
				$start_date = $this->DB2->escape($this->input->get("start_date"));
				if ($this->input->get("end_date")) {
					$end_date = $this->DB2->escape($this->input->get("end_date").":59");
				}

			}
			//case 'h35naxx1hmt':
			//case 'L8na':
			$sum_condition = ($game_id=='h35naxx1hmt'?50000:1);
			$report_result = $this->DB2->query("SELECT uid,char_name 'character_name',
				char_in_game_id 'character_in_game_id',
				server_name,
				deposit_total,
				DATE(account_create_time) 'create_date',
				DATE_FORMAT(last_login, '%Y-%m-%d') 'last_login',
				latest_topup_date 'latest_topup_date',
				TIMESTAMPDIFF(DAY, latest_topup_date, NOW()) 'days_since' ,
				is_added,
				DATE_FORMAT(line_date, '%Y-%m-%d') 'line_date',
				TIMESTAMPDIFF(DAY, create_time, NOW()) 'days_inserted',
				ip,
				DATE_FORMAT(vip_ranking_updated, '%Y-%m-%d') 'vip_ranking_updated',
				vip_ranking,
				DATE_FORMAT(inactive_confirm_date, '%Y-%m-%d') 'inactive_confirm_date',
				CASE
					WHEN vip_ranking_updated is NULL THEN '100'
					ELSE TIMESTAMPDIFF(DAY, vip_ranking_updated, NOW())  END as 'days_vip_updated',
				(select count(id) from vip_requests where game_id=w.site
				and role_id=w.char_in_game_id and service_type=3 and request_code=3) as inv_count
				from whale_users w where site = '{$game_id}' and deposit_total > {$sum_condition} order by {$orderby} ")->result();

			switch ($this->input->get("action"))
			{
				case "鯨魚用戶統計":
					break;
				case "輸出":
					ini_set("memory_limit","2048M");
					$filename = "output.csv";
					header("Content-type:application/vnd.ms-excel;");
					header("Content-Disposition: filename={$filename};");
					//排名	帳號	角色	原廠ID	伺服器	儲值累積	最後訂單時間	地區	未儲值/日	3日內新人	升階	加入Line	加入Line日期	最後登入日期	確認流失

					$content = "帳號,角色,原廠ID,伺服器,儲值累積,最後訂單時間,地區,未儲值/日,升階,邀請次數,加入Line,加入Line日期,最後登入日期,確認流失,VIP級別\n";
						foreach($report_result as $row) {
							$content .= "{$row->uid},{$row->character_name},{$row->character_in_game_id},{$row->server_name},{$row->deposit_total},{$row->latest_topup_date},";
							$content .= geoip_country_name_by_name($row->ip).",";
							$content .= "{$row->days_since},{$row->vip_ranking_updated},";
							$content .= "{$row->inv_count},".($row->is_added==1?"V":"").",";
							$content .= "{$row->line_date},{$row->last_login},{$row->inactive_confirm_date},";
							$content .= $this->vipcolor($game_id,$row->deposit_total);
							$content .= "\n";
						}

						echo iconv('utf-8', 'utf-8//TRANSLIT//IGNORE', $content);
						exit();
						break;
			}
			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
		}
		$this->g_layout
			->set("report_result", isset($report_result) ? $report_result : false)
			->set("game_id", $game_id)
      ->set("orderby", $orderby)
			->add_js_include("game/whale_users")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("fontawesome/js/fontawesome-all")
			->render();
	}

function vipcolor($game_id,$deposit_number){
		switch ($game_id) {
			case 'h35naxx1hmt':
				if ($deposit_number>=1000000)
				{
					//return "#FFFFFF";
					return "黑R";
				}
				else if ($deposit_number>=700000 and $deposit_number<1000000)
				{
					//return "#E5E4E2";
					return "白金R";
				}
				else if ($deposit_number>=400000 and $deposit_number<700000)
				{
					//return "#D4AF37";
					return "金R";
				}
				else if ($deposit_number>=200000 and $deposit_number<400000)
				{
					//return "#C0C0C0";
					return "銀R";
				}
				else if ($deposit_number>=150000 and $deposit_number<200000)
				{
					//return "#FAEBD7";
					return "普R";
				}
				else {
					//return "#FFFFFF";
					return "No R";
				}
				break;
			case 'L8na':
				if ($deposit_number>=100000)
				{
					return "#D4AF37";
				}
				else if ($deposit_number>=50000 and $deposit_number<100000)
				{
					return "#C0C0C0";
				}
				else if ($deposit_number>=30000 and $deposit_number<50000)
				{
					return "#FAEBD7";
				}
				else {
					return "#FFFFFF";
				}

				break;
			default:
				# code...
				break;
		}

}


function whale_users_set_status($uid, $status)
{
	if ($status==1){
	$this->DB2->where("uid", $uid)
	->update("whale_users" ,array("line_date"=> now() , "is_added"=> $status));
} else {
	$this->DB2->where("uid", $uid)
	->update("whale_users" ,array("line_date"=> null , "is_added"=> $status));
}
	// $this->DB2->where("uid", $uid)
	// ->set("is_added", $status)
	// ->update("whale_users" , $status ==1 ? array("line_date"=> "DATE_FORMAT(now(), '%Y-%m-%d')"):array("line_date"=> null) );
	//echo json_failure($status ==1);
	echo $this->DB2->affected_rows()>0 ? json_success() : json_failure("無變更");
}

function whale_users_set_lastlogin()
{
	if ( ! $this->zacl->check_acl("service", "modify")) die(json_failure("沒有權限"));
	//$this->DB1->where("status", "2")->where("is_read", "1")->where("create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)", null, false)->update("questions", array("status"=>"4"));
	//$game_id,$role_id, $last_login'

	$game_id = $this->input->post("game_id");
	$role_id = $this->input->post("role_id");
	$last_login = $this->input->post("last_login");
	$opt = $this->input->post("opt");
	if ($opt== "reset")
	{
		$this->DB2->where("site", $game_id)->where("char_in_game_id", $role_id)
		->update("whale_users", array("last_login"=> null,"inactive_confirm_date"=>null));
	}
	elseif ($game_id && $role_id && $last_login)
	{
		$this->DB2->where("site", $game_id)->where("char_in_game_id", $role_id)
		->set("last_login", $last_login)
		->set("inactive_confirm_date", "now()", false)
		->update("whale_users");
	}
	else
	{
		die(json_failure("請輸入完整資料"));
	}

	if ($this->DB2->affected_rows() > 0) {
		die(json_success());
	}
	else {
		die(json_success($game_id.",".$role_id.",".$last_login.",".$opt));
	}

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



	function pivot_tbl(){

		$this->zacl->check_login(false);



		$m_sql="select DATE_FORMAT(create_time,'%Y-%m') as month
		from questions
		where create_time between '2018-01-01' and now()
		group by DATE_FORMAT(create_time,'%Y-%m') order by month desc ";

		$month_data = $this->DB2->query($m_sql)->result();

		$this->_init_layout()->add_css_link('pivot')
			->add_js_include("@pivot/dist/pivot")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("month_data", $month_data)
			->render();
	}

	function daily_count_json()
	{
		// $where_allow_games = (in_array('all_game', $this->zacl->allow_games))?"":" and g.game_id in ('".implode("','",$this->zacl->allow_games)."')";
		 $date = date('Y-m',(strtotime ( "now" , strtotime ( date('Y-m')) ) ));
		//
		if ($this->input->get("date")) {
			$date = $this->input->get("date");
		}




		$stat = $this->DB2->query("select au.name as '人員', g.name as '遊戲',DATE_FORMAT(qr.create_time, '%Y-%m-%d')  as '日期',count(*) as '數量'
		from questions q
		left join question_replies qr on q.id=qr.question_id
		LEFT JOIN servers gi
		ON gi.server_id=q.server_id
		LEFT JOIN games g on g.game_id=gi.game_id
		LEFT JOIN admin_users au on qr.admin_uid=au.uid
		where qr.create_time between '{$date}-01 00:00:00' and '{$date}-31 23:59:59'
		and qr.admin_uid in(86,87,116,151) and qr.is_official=1
		group by qr.admin_uid,g.name,DATE_FORMAT(qr.create_time, '%Y-%m-%d')
		order by `人員`,DATE_FORMAT(qr.create_time, '%Y-%m-%d')
		")->result();




		echo json_message(array("stat" => $stat  ));

	}

}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
