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
    if ($this->zacl->check_acl("all_game", "all") == false) {
      if ($this->game_id) $this->zacl->check($this->game_id, "read");
    }

    return $this->_init_layout()
      ->add_breadcrumb("光明之戰VIP統計報表", "h35vip_statistics/overview");
  }

  function overview()
	{
		$this->_init_statistics_layout();
		$this->load->helper("output_table");

		$this->zacl->check("game_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("select *,(general + silver+gold+platinum+black) as week_total from h35vip_weekly_data");

		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("span", $span)
			->render();
	}

  function topup_status()
	{
		$this->_init_statistics_layout();
		$this->load->helper("output_table");

		$this->zacl->check("game_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    select left(myyearweek,4) as year,
    right(myyearweek,2) as week,
    SUM(CASE WHEN a.server = '10001' THEN sumTotal ELSE 0 END) AS 's10001',
    SUM(CASE WHEN a.server = '10002' THEN sumTotal ELSE 0 END) AS 's10002',
    SUM(CASE WHEN a.server = '10003' THEN sumTotal ELSE 0 END) AS 's10003',
    SUM(CASE WHEN a.server = '10004' THEN sumTotal ELSE 0 END) AS 's10004',
    SUM(CASE WHEN a.server = '10005' THEN sumTotal ELSE 0 END) AS 's10005' from
    (select YEARWEEK(create_time) as myyearweek, server ,sum(amount) as sumTotal from h35vip_orders
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

		$this->zacl->check("game_statistics", "read");

		$span = $this->input->get("span");
		$query = $this->DB2->query("
    select   server_name,
    SUM(CASE WHEN deposit_total <100000 and deposit_total>=50000 THEN 1 ELSE 0 END) AS 'general',
    SUM(CASE WHEN deposit_total >= 100000 AND deposit_total <300000 THEN 1 ELSE 0 END) AS 'silver',
    SUM(CASE WHEN deposit_total >= 300000 AND deposit_total <600000 THEN 1 ELSE 0 END) AS 'gold',
    SUM(CASE WHEN deposit_total >= 600000 AND deposit_total <1000000 THEN 1 ELSE 0 END) AS 'platinum',
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


}
