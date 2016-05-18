<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing_statistics extends MY_Controller {
	
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
	
	function overview()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$span = $this->input->get_post("span");
		$start_date = $this->input->get_post("start_date") ? $this->input->get_post("start_date") : date("Y-m-d");
		$end_date = $this->input->get_post("end_date") ? $this->input->get_post("end_date") : date("Y-m-d");
		$game_id = $this->input->get_post("game_id");
		if (empty($this->input->get_post("start_date")) && empty($this->input->get_post("end_date"))) {
			$start_date = date("Y-m-d",strtotime("-8 days"));
			$end_date = date("Y-m-d",strtotime("-1 days"));
		} 
		
        switch($span) {
			case "weekly":
                $select = "s.date 'date'";
                $s_select = "YEARWEEK((date),3) 'date'";
                $group = "YEARWEEK((date),3)";
                $order = "s.date DESC";
				break;
			
			case "monthly":
                $select = "s.year 'year',
                           s.date 'date'";
                $s_select = "YEAR(date) 'year',
                           MONTH(date) 'date'";
                $group = "YEAR(date),
                          MONTH(date)";
                $order = "s.year DESC,
                          s.date DESC";
				break;
				
			default:
                $select = "s.date 'date'";
                $s_select = "date";
                $group = "date";
                $order = "s.date DESC";
				break;
		}
        
        $query = $this->DB2->query("
            SELECT 
                {$select},
                s.game_id,
                s.login_count 'login_count',
                s.new_login_count 'new_login_count',
                s.device_count 'device_count',
                apk_ms.login_count 'apk_login_count',
                ios_ms.install_count 'ios_download_count',
                ios_ms.tw_install_count 'ios_tw_download_count',
                ios_ms.hk_install_count 'ios_hk_download_count',
                ios_ms.mo_install_count 'ios_mo_download_count',
                ios_ms.sg_install_count 'ios_sg_download_count',
                ios_ms.my_install_count 'ios_my_download_count',
                google_ms.install_count 'google_download_count',
                google_ms.tw_install_count 'google_tw_download_count',
                google_ms.hk_install_count 'google_hk_download_count',
                google_ms.mo_install_count 'google_mo_download_count',
                google_ms.sg_install_count 'google_sg_download_count',
                google_ms.my_install_count 'google_my_download_count',
                apk_ms.install_count 'apk_download_count',
                apk_ms.tw_install_count 'apk_tw_download_count',
                apk_ms.hk_install_count 'apk_hk_download_count',
                apk_ms.mo_install_count 'apk_mo_download_count',
                apk_ms.sg_install_count 'apk_sg_download_count',
                apk_ms.my_install_count 'apk_my_download_count'
            FROM
            (
                SELECT {$s_select}, game_id, SUM(login_count) 'login_count', SUM(new_login_count) 'new_login_count', SUM(device_count) 'device_count'
                FROM statistics
                WHERE date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS s
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'ios'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS ios_ms ON s.date=ios_ms.date ".(($span=='monthly')?"AND s.year=ios_ms.year":"")."
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'google'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS google_ms ON s.date=google_ms.date ".(($span=='monthly')?"AND s.year=google_ms.year":"")."
            LEFT JOIN
            (
                SELECT 
                    {$s_select},
                    SUM(af_login) 'login_count', 
                    SUM(install_count) 'install_count',
                    SUM(CASE WHEN country_code='TW' THEN install_count ELSE 0 END) 'tw_install_count',
                    SUM(CASE WHEN country_code='HK' THEN install_count ELSE 0 END) 'hk_install_count',
                    SUM(CASE WHEN country_code='MO' THEN install_count ELSE 0 END) 'mo_install_count',
                    SUM(CASE WHEN country_code='SG' THEN install_count ELSE 0 END) 'sg_install_count',
                    SUM(CASE WHEN country_code='MY' THEN install_count ELSE 0 END) 'my_install_count'
                FROM marketing_statistics
                WHERE platform = 'apk'
                    AND date BETWEEN '{$start_date}' AND '{$end_date}'
                    AND game_id = '{$game_id}'
                GROUP BY {$group}
            ) AS apk_ms ON s.date=apk_ms.date ".(($span=='monthly')?"AND s.year=apk_ms.year":"")."
            ORDER BY {$order}
        ");
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			->set("span", $span)
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
    
	function appsflyer()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		//$game_id = $this->input->get("game_id");
					
		$query = $this->DB2->where("date >=", $start_date)->where("date <=", $end_date)->order_by("date", "desc")->order_by("game_id", "asc")->order_by("platform", "asc")->order_by("country_code", "asc")->order_by("media", "asc")->get("marketing_statistics");
        
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			//->set("game_id", $game_id)
			->set("start_date", $start_date)
			->set("end_date", $end_date)
			//->set("servers", $this->DB2->where("game_id", $this->game_id)->from("servers")->order_by("server_id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
    
	function facebook_installs()
	{			
		$this->_init_statistics_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id");
		
		$query = $this->DB2->query("
			SELECT
                date,
                install_count,
                platform,
                
		");
        
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
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */