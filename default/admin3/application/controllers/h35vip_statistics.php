<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class H35vip_statistics extends MY_Controller {
  function __construct()
  {
    parent::__construct();
		error_reporting(E_ALL);
		ini_set('display_errors','On');

	}
  function _init_statistics_layout()
  {
    $this->zacl->check_login(true);
    $this->zacl->check("whale_users_statistics", "read");


    return $this->_init_layout()
      ->add_breadcrumb("光明之戰VIP統計報表", "h35vip_statistics/overview");
  }

  function overview()
	{
		$this->_init_statistics_layout();
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

    //is_added=Y&start_week=&end_week=&action=篩選

		///$span = $this->input->get("span");

    $start_week = $this->input->get("start_week");
    $end_week = $this->input->get("end_week");


    if ($this->input->get("is_added"))
    {
      $is_added = 1;
    }
    else {
      $is_added = 0;
    }

    $tmpquery = "select *,STR_TO_DATE(CONCAT(year, week,' Sunday') , '%X%V %W') as first_date,(general + silver+gold+platinum+black) as week_total from h35vip_weekly_data
    where tag={$is_added}";
    if ($start_week)
    {
      $tmpquery.= " and YEARWEEK(STR_TO_DATE(CONCAT(year, week,' Sunday') , '%X%V %W')) between $start_week";
    }

    if ($end_week)
    {
      $tmpquery.= " and $end_week";
    }
		$query = $this->DB2->query($tmpquery);


    $week_data = $this->DB2->query("SELECT YEARWEEK as myyearweek, MONTH(STR_TO_DATE(CONCAT(YEARWEEK,' Sunday') , '%X%V %W')) as mymonth
    from
    (SELECT YEARWEEK(create_time) as YEARWEEK
    FROM h35vip_orders
    GROUP BY YEARWEEK(create_time)) a")->result();

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("week_data", $week_data)
			->render();
	}

  function topup_status()
	{
		$this->_init_statistics_layout();
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    select left(myyearweek,4) as year,
    right(myyearweek,2) as week,
    STR_TO_DATE(CONCAT(myyearweek,' Sunday') , '%X%V %W') as first_date,
    SUM(CASE WHEN a.server = '10001' THEN sumTotal ELSE 0 END) AS 's10001',
    SUM(CASE WHEN a.server = '10002' THEN sumTotal ELSE 0 END) AS 's10002',
    SUM(CASE WHEN a.server = '10003' THEN sumTotal ELSE 0 END) AS 's10003',
    SUM(CASE WHEN a.server = '10004' THEN sumTotal ELSE 0 END) AS 's10004',
    SUM(CASE WHEN a.server = '10005' THEN sumTotal ELSE 0 END) AS 's10005' from
    (select YEARWEEK(create_time) as myyearweek, server ,sum(amount) as sumTotal from h35vip_orders
    where REPLACE(account,'@netease_global.win.163.com','') in(select uid from  whale_users where site ='h35naxx1hmt' and is_added ='1')
    group by YEARWEEK(create_time),server) a
    group by myyearweek
    ");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->render();
	}

  function vip_distribution()
	{
		$this->_init_statistics_layout();
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
    $this->_init_statistics_layout();
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
    $this->_init_statistics_layout();
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

  function country_distribution()
	{
		$this->_init_statistics_layout();
		$this->load->helper("output_table");

		$this->zacl->check("whale_users_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    SELECT country,
    count(*) as cnt,
    SUM(CASE WHEN is_added = '1' THEN 1 ELSE 0 END) AS 'added_cnt',
    sum(deposit_total) as amount,
    sum(CASE WHEN is_added = '1' THEN deposit_total ELSE 0 END) as 'added_amount'
    FROM whale_users WHERE site ='h35naxx1hmt'
    group by country order by count(*) desc
    ");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->render();
	}


}
