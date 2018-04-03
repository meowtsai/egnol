<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class H35vip_statistics extends MY_Controller {
  function __construct()
  {
    parent::__construct();
		error_reporting(E_ALL);
		ini_set('display_errors','On');

	}
  function _init_statistics_layout($game_id)
  {
    $this->zacl->check_login(true);
    $this->zacl->check("whale_users_statistics", "read");
    $game_name = "光明之戰";
    switch ($game_id) {
      case 'l8na':
        $game_name = '三少爺的劍';
        break;

      default:
        $game_name = '光明之戰';
        break;
    }


    return $this->_init_layout()
      ->add_breadcrumb("{$game_name} VIP統計報表", "h35vip_statistics/overview/{$game_id}");
  }

  function overview($game_id)
	{
		$this->_init_statistics_layout($game_id);
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

    $start_week = $this->input->get("start_week");
    $end_week = $this->input->get("end_week");


    if ($this->input->get("is_added"))
    {
      $is_added = 1;
    }
    else {
      $is_added = 0;
    }

    $tmpquery = "SELECT *,STR_TO_DATE(CONCAT(year, week,' Monday') , '%x%v %W') as first_date,(general + silver+gold+platinum+black) as week_total from h35vip_weekly_data
    where tag={$is_added} and game_id='{$game_id}' ";
    if ($start_week)
    {
      $tmpquery.= " and YEARWEEK(STR_TO_DATE(CONCAT(year, week,' Monday') , '%x%v %W'),1) between '{$start_week}'";
    }

    if ($end_week)
    {
      $tmpquery.= " and '{$end_week}'";
    }
		$query = $this->DB2->query($tmpquery);



    $week_sql ="SELECT YEARWEEK as myyearweek, MONTH(STR_TO_DATE(CONCAT(YEARWEEK,' Monday') , '%x%v %W')) as mymonth
    from  (SELECT YEARWEEK(create_time,1) as YEARWEEK FROM negame_orders WHERE game_id='{$game_id}' GROUP BY YEARWEEK(create_time,1)) a";


    $week_data = $this->DB2->query($week_sql)->result();

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("week_data", $week_data)
      ->set("game_id", $game_id)
			->render();
	}

  function topup_status($game_id)
	{
		$this->_init_statistics_layout($game_id);
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");

    if ($this->input->get("is_added"))
    {

      $tmpquery = "and account
      in(select uid from  whale_users
      where site ='{$game_id}'
      and is_added ='1'";

      $is_added = $this->input->get("is_added");
      if ($is_added=="R")
      {
        $tmpquery .= " and vip_ranking is not null)";
      }
      else {
        $tmpquery .= " )";
      }

    }
    else {
      $tmpquery = "";
    }




    $start_week = $this->input->get("start_week");
    $end_week = $this->input->get("end_week");

    if ($start_week)
    {
      $tmpquery.= " and YEARWEEK(create_time,1) between $start_week";
    }

    if ($end_week)
    {
      $tmpquery.= " and $end_week";
    }

    $query_comp = $this->DB2->query(" SELECT GROUP_CONCAT(DISTINCT CONCAT(
        'SUM(CASE WHEN server = \"',
        server,
        '\" THEN sumTotal ELSE 0 END) as \"',
         server ,
        '\" '
      )) as server_subq
      FROM (select distinct name as server from servers WHERE game_id='{$game_id}'  and server_status='public' order by server_id) serverList")->result()[0]->server_subq;


    if ($game_id=="h35naxx1hmt")
    {
      $server_condition = "  a.server = b.server_id ";
    }
    else {
      $server_condition = "  a.server = b.address ";
    }

		$query = $this->DB2->query("
    select left(myyearweek,4) as year,
    right(myyearweek,2) as week,
    STR_TO_DATE(CONCAT(myyearweek,' Monday') , '%x%v %W') as first_date,
    {$query_comp}
    from
    (
      select YEARWEEK(create_time,1) as myyearweek, b.name  as server ,sum(amount) as sumTotal from
      negame_orders a left join
      (select * from servers where game_id='{$game_id}') b on {$server_condition}
      WHERE a.game_id='{$game_id}'
      {$tmpquery}
      group by YEARWEEK(create_time,1),server
      ) a
    group by myyearweek
    ");

    $week_data = $this->DB2->query("SELECT YEARWEEK as myyearweek, MONTH(STR_TO_DATE(CONCAT(YEARWEEK,' Monday') , '%x%v %W')) as mymonth
    from
    (SELECT YEARWEEK(create_time,1) as YEARWEEK
    FROM negame_orders WHERE game_id='{$game_id}'
    GROUP BY YEARWEEK(create_time,1)) a")->result();


		$this->g_layout
			->set("query", isset($query) ? $query : false)
      ->set("week_data", $week_data)
			->set("game_id", $game_id)
    	->render();
	}

  function vip_distribution()
	{
		$this->_init_statistics_layout($game_id);
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    select   server_name,
    SUM(CASE WHEN deposit_total <200000 and deposit_total>=150000 THEN 1 ELSE 0 END) AS 'general',
    SUM(CASE WHEN deposit_total >= 200000 AND deposit_total <400000 THEN 1 ELSE 0 END) AS 'silver',
    SUM(CASE WHEN deposit_total >= 400000 AND deposit_total <700000 THEN 1 ELSE 0 END) AS 'gold',
    SUM(CASE WHEN deposit_total >= 700000 AND deposit_total <1000000 THEN 1 ELSE 0 END) AS 'platinum',
    SUM(CASE WHEN deposit_total >= 1000000  THEN 1 ELSE 0 END) AS 'black'
    from
    (select uid, sum(deposit_total) as deposit_total,server_name from whale_users
    where site ='h35naxx1hmt'
    group by uid,server_name) a
    group by server_name
    ");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->render();
	}


  function daily_topup()
  {
    $this->_init_statistics_layout($game_id);
    $this->load->helper("output_table");

    $this->zacl->check("whale_users_statistics", "read");

    $select_month =  date("Y-m");

    if ($this->input->get("select_month")) {
      $select_month = $this->input->get("select_month");
    }


    if ($this->input->get("is_added"))
    {
      $is_added = "AND REPLACE(account,'@netease_global.win.163.com','') in(select uid from  whale_users where site ='h35naxx1hmt' and is_added ='1')";
    }
    else {
      $is_added = "";
    }

    $span = $this->input->get("span");
    $query = $this->DB2->query("
    Select DATE_FORMAT(create_time,'%Y-%m-%d') as day,
    SUM(CASE WHEN server = '10001' THEN amount ELSE 0 END) AS 's10001',
    SUM(CASE WHEN server = '10002' THEN amount ELSE 0 END) AS 's10002',
    SUM(CASE WHEN server = '10003' THEN amount ELSE 0 END) AS 's10003',
    SUM(CASE WHEN server = '10004' THEN amount ELSE 0 END) AS 's10004',
    SUM(CASE WHEN server = '10005' THEN amount ELSE 0 END) AS 's10005'
    from h35vip_orders
    where DATE_FORMAT(create_time,'%Y-%m')='{$select_month}'
    {$is_added}
    group by  DATE_FORMAT(create_time,'%Y-%m-%d')
    ");


    $month_data = $this->DB2->query("Select  distinct DATE_FORMAT(create_time,'%Y-%m') as month
    from h35vip_orders")->result();



    $this->g_layout
      ->set("query", isset($query) ? $query : false)
      ->set("month_data", $month_data)
      ->render();
  }


  function hourly_topup()
  {
    $this->_init_statistics_layout($game_id);
    $this->load->helper("output_table");

    $this->zacl->check("whale_users_statistics", "read");

    $select_month =  date("Y-m");

    if ($this->input->get("select_month")) {
      $select_month = $this->input->get("select_month");
    }


    if ($this->input->get("is_added"))
    {
      $is_added = "AND REPLACE(account,'@netease_global.win.163.com','') in(select uid from  whale_users where site ='h35naxx1hmt' and is_added ='1')";
    }
    else {
      $is_added = "";
    }

    $span = $this->input->get("span");
    $query = $this->DB2->query("
    Select  DATE_FORMAT(create_time,'%H') as hour,
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Sun' THEN amount ELSE 0 END) as 'Sun',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Mon' THEN amount ELSE 0 END) as 'Mon',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Tue' THEN amount ELSE 0 END) as 'Tue',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Wed' THEN amount ELSE 0 END) as 'Wed',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Thu' THEN amount ELSE 0 END) as 'Thu',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Fri' THEN amount ELSE 0 END) as 'Fri',
    SUM(CASE WHEN DATE_FORMAT(create_time,'%a') = 'Sat' THEN amount ELSE 0 END) as 'Sat'
    from h35vip_orders
    where DATE_FORMAT(create_time,'%Y-%m')='{$select_month}'
    {$is_added}
    group by  DATE_FORMAT(create_time,'%H')
    order by  DATE_FORMAT(create_time,'%H')
    ");


    $month_data = $this->DB2->query("Select  distinct DATE_FORMAT(create_time,'%Y-%m') as month
    from h35vip_orders")->result();



    $this->g_layout
      ->set("query", isset($query) ? $query : false)
      ->set("month_data", $month_data)
      ->render();
  }

  function country_distribution($game_id)
	{
		$this->_init_statistics_layout($game_id);
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    SELECT country,
    count(*) as cnt,
    SUM(CASE WHEN is_added = '1' THEN 1 ELSE 0 END) AS 'added_cnt',
    sum(deposit_total) as amount,
    sum(CASE WHEN is_added = '1' THEN deposit_total ELSE 0 END) as 'added_amount'
    FROM whale_users WHERE site ='{$game_id}' and  vip_ranking is not null
    and deposit_total>150000
    group by country order by count(*) desc
    ");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->render();
	}
  function ranking_detail($game_id,$country_code,$is_add)
	{
    $vip_ranking = $this->config->item('vip_ranking');
// select vip_ranking,count(*) from whale_users where vip_ranking is not null and site='h35naxx1hmt' and deposit_total>150000 and country='tw' group by vip_ranking;

    if ($is_add==1)
    {
      $is_add_condition = "and is_added = '{$is_add}'";
    }
    $query = $this->DB2->query("
    select vip_ranking,count(*) as cnt
    FROM whale_users
    WHERE vip_ranking is not null
    and site='{$game_id}'
    and deposit_total>150000
    and country='{$country_code}'
    {$is_add_condition}
    group by vip_ranking");

    $data = array();
    foreach($query->result() as $row) {
        if ($row->vip_ranking)
        {
          $vip_ranking_text = $vip_ranking[$row->vip_ranking];
        }

        $data[] = array(
          'vip_ranking' =>  $row->vip_ranking,
          'cnt' => $row->cnt,
        );
      }

      //$result_obj = new stdClass()
      //header('Access-Control-Allow-Origin: *');
      die(json_encode($data));
  }

  function monthly_topup($game_id)
  {
    $this->_init_statistics_layout($game_id);
    $this->load->helper("output_table");

    $this->zacl->check("whale_users_statistics", "read");

    $span = $this->input->get("span");

    if ($this->input->get("is_added"))
    {

      $tmpquery = "and account
      in(select uid from  whale_users
      where site ='{$game_id}'
      and is_added ='1'";

      $is_added = $this->input->get("is_added");
      if ($is_added=="R")
      {
        $tmpquery .= " and vip_ranking is not null)";
      }
      else {
        $tmpquery .= " )";
      }

    }
    else {
      $tmpquery = "";
    }

    $select_month = $this->input->get("select_month");
    $select_month_end = $this->input->get("select_month_end");

    if ($select_month)
    {
      $tmpquery.= " and DATE_FORMAT(create_time,'%Y-%m') between '$select_month'";
    }

    if ($select_month_end)
    {
      $tmpquery.= " and '$select_month_end'";
    }

    $query_comp = $this->DB2->query(" SELECT GROUP_CONCAT(DISTINCT CONCAT(
        'SUM(CASE WHEN server = \"',
        server,
        '\" THEN sumTotal ELSE 0 END) as \"',
         server ,
        '\" '
      )) as server_subq
      FROM (select distinct name as server from servers WHERE game_id='{$game_id}'  and server_status='public' order by server_id) serverList")->result()[0]->server_subq;


    if ($game_id=="h35naxx1hmt")
    {
      $server_condition = "  a.server = b.server_id ";
    }
    else {
      $server_condition = "  a.server = b.address ";
    }

    $query = $this->DB2->query("
    select myyearmonth,
    {$query_comp}
    from
    (
      select DATE_FORMAT(create_time,'%Y-%m') as myyearmonth, b.name  as server ,sum(amount) as sumTotal from
      negame_orders a left join
      (select * from servers where game_id='{$game_id}') b on {$server_condition}
      WHERE a.game_id='{$game_id}'
      {$tmpquery}
      group by DATE_FORMAT(create_time,'%Y-%m'),server
      ) a
    group by myyearmonth
    ");

    $month_data = $this->DB2->query("Select  distinct DATE_FORMAT(create_time,'%Y-%m') as month
    FROM negame_orders WHERE game_id='{$game_id}'")->result();



    $this->g_layout
      ->set("query", isset($query) ? $query : false)
      ->set("month_data", $month_data)
      ->set("game_id", $game_id)
      ->render();
  }

  function monthly_topup_backup($game_id)
  {
    $this->_init_statistics_layout($game_id);
    $this->load->helper("output_table");

    $this->zacl->check("whale_users_statistics", "read");

    $select_month =  date("Y-m",mktime(0, 0, 0, 11, 15, 2017));
    $select_month_end =  date("Y-m");

    if ($this->input->get("select_month")) {
      $select_month = $this->input->get("select_month");
    }


    if ($this->input->get("select_month_end")) {
      $select_month_end = $this->input->get("select_month_end");
    }


    if ($this->input->get("is_added"))
    {
      $is_added = "AND account in(select uid from  whale_users  where site ='{$game_id}' and is_added ='1' ";

      $tmp_val = $this->input->get("is_added");
      if ($tmp_val=="R")
      {
        $is_added .= " and vip_ranking is not null)";
      }
      else {
        $is_added .= " )";
      }
    }
    else {
      $is_added = "";
    }

    $query = $this->DB2->query("
    Select DATE_FORMAT(create_time,'%Y-%m') as month,
    SUM(CASE WHEN server = '10001' THEN amount ELSE 0 END) AS 's10001',
    SUM(CASE WHEN server = '10002' THEN amount ELSE 0 END) AS 's10002',
    SUM(CASE WHEN server = '10003' THEN amount ELSE 0 END) AS 's10003',
    SUM(CASE WHEN server = '10004' THEN amount ELSE 0 END) AS 's10004',
    SUM(CASE WHEN server = '10005' THEN amount ELSE 0 END) AS 's10005'
    from h35vip_orders
    where DATE_FORMAT(create_time,'%Y-%m') between '{$select_month}' and  '{$select_month_end}'
    {$is_added}
    group by  DATE_FORMAT(create_time,'%Y-%m')
    ");


    $month_data = $this->DB2->query("Select  distinct DATE_FORMAT(create_time,'%Y-%m') as month
    from h35vip_orders")->result();



    $this->g_layout
      ->set("query", isset($query) ? $query : false)
      ->set("month_data", $month_data)
      ->render();
  }

  function overview_monthly($game_id)
  {
    $this->_init_statistics_layout($game_id);
    $this->load->helper("output_table");

    $this->zacl->check("whale_users_statistics", "read");

    $start_month = $this->input->get("start_month");
    $end_month = $this->input->get("end_month");

    if ($this->input->get("is_added"))
    {
      $is_added = 1;
    }
    else {
      $is_added = 0;
    }

    $tmpquery = "select *,(general + silver+gold+platinum+black) as month_total from h35vip_monthly_data
    where tag={$is_added} and game_id='{$game_id}' ";
    if ($start_month)
    {
      $tmpquery.= " and yearmonth between '{$start_month}'";
    }

    if ($end_month)
    {
      $tmpquery.= " and '{$end_month}'";
    }
    $query = $this->DB2->query($tmpquery);



    $month_data = $this->DB2->query("SELECT distinct yearmonth
    from
    h35vip_monthly_data WHERE game_id='{$game_id}'")->result();

    $this->g_layout
      ->set("query", isset($query) ? $query : false)
      ->set("month_data", $month_data)
      ->set("game_id", $game_id)
      ->render();
  }
//is_added=Y&start_date=2018-03-01&end_date=2018-03-20&action=篩選
  function contribution_piechart($game_id)
	{
		$this->_init_statistics_layout($game_id);
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");

    if ($this->input->get("is_added"))
    {
      $is_added = "and is_added ='1'";
    }
    else {
      $is_added = "";
    }

    $start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d",strtotime("-30 days"));;
    $end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");

		$query = $this->DB2->query("
    select vip_ranking, sum(range_amount) as range_amount,sum(total_amount) as total_amount
    FROM
    (
    select vip_ranking,sum(amount) as range_amount,0 as total_amount from negame_orders a
    inner join (select char_in_game_id,vip_ranking from whale_users
    where site ='{$game_id}' and vip_ranking is not null {$is_added}) b
    on a.role_id = b.char_in_game_id
    where a.game_id='{$game_id}' and DATE_FORMAT(create_time,'%Y-%m-%d') between '{$start_date}' and '{$end_date}'
    group by vip_ranking

    union
    select vip_ranking,0 as range_amount, sum(amount) as total_amount from negame_orders a
    inner join (select char_in_game_id,vip_ranking from whale_users
    where site ='{$game_id}' and vip_ranking is not null {$is_added}) b
    on a.role_id = b.char_in_game_id
    where a.game_id='{$game_id}' and DATE_FORMAT(create_time,'%Y-%m-%d') between '2017-11-15' and now()
    group by vip_ranking
    ) a
    group by vip_ranking
    ORDER BY FIELD(vip_ranking, 'general','silver','gold','platinum','black')
    ");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
      ->set("start_date", $start_date)
      ->set("end_date", $end_date)
			->set("game_id", $game_id)
			->render();
	}


}
