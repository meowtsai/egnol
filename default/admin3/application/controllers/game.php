<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();					
		
		error_reporting(E_ALL);
		ini_set('display_errors','On');		
	}		
	
	function _init_game_layout()
	{
		$this->zacl->check_login(true);
		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}
		
		return $this->_init_layout()
			->add_breadcrumb("遊戲", "game");
	}
	
	function index()
	{
		$query = $this->db->from("games")->order_by("rank")->get();
		$this->_init_game_layout()
			->set("query", $query)
			->render();	
	}
	
	function sort()
	{
		$query = $this->db->from("games")->order_by("rank")->get();
		$this->_init_game_layout()
			->add_breadcrumb("排序")
			->set("query", $query)
			->render();		
	}
	
	function save_sort()
	{
		if ($data = $this->input->post("data")) {
			$arr = explode(",", $data);
			$i = 1; 
			foreach($arr as $game) {
				$this->db->query("update games set rank=".($i++)." where game_id=".$this->db->escape($game)."; ");
			}			
			die(json_success());
		}
		else die(json_failure("缺少參數"));
	}
	
	function modify()
	{	
		$this->_init_game_layout();			
		$this->zacl->check("game_setting", "modify");
		
		if ($post = $this->input->post()) 
		{
			$msg = array();
			
			if ($this->input->post("tags")) {
				$tags = implode(",", $this->input->post("tags")).",".$this->input->post("type");
			} 
			else {
				$tags = $this->input->post("type");
			}
			
			$this->db->where("game_id", $this->game_id)
				->update("games", array(
						"name" => $this->input->post("name"),
						"abbr" => $this->input->post("abbr"),
						"exchange_rate" => $this->input->post("exchange_rate"),
						"currency" => $this->input->post("currency"),
						"is_active" => $this->input->post("is_active"),
						"tags" => $tags,						
					));			
			
			$this->load->library('upload');
			$config['upload_path'] = g_conf("http_document_root")."long_e/p/img/game/";
			$config['allowed_types'] = 'png|jpg|jpeg';
			$config['max_size']	= '600';
			$config['max_width'] = '2048';
			$config['max_height'] = '768';		
			$config['overwrite'] = true;
			
			$upload_cnt = 0;
			if ( ! empty($_FILES["file01"]["name"])) {
				$config['file_name'] = "{$this->game_id}_01";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file01"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					$upload_cnt++;
				}
			}
			
			if ( ! empty($_FILES["file02"]["name"])) {
				$config['file_name'] = "{$this->game_id}_02";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file02"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					$upload_cnt++;
				}			
			}

			if ( ! empty($_FILES["file03"]["name"])) {
				$config['file_name'] = "{$this->game_id}_03";
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file03"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					$upload_cnt++;
				}			
			}		

			if ( ! empty($_FILES["file04"]["name"])) {
				$config['file_name'] = "{$this->game_id}";
				$config['allowed_types'] = '*';
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload("file04"))
				{
					$msg[] = $this->upload->display_errors('', '');
				}
				else
				{
					$upload_cnt++;
				}			
			}				
			
			if ($this->db->affected_rows()==0 && $upload_cnt==0) $msg[] = '資料無變更';
			
			$this->g_layout->set("msg", $msg);
		}		
		
		if ($this->game_id) {
			$where = array("game_id" => $this->game_id);
		}
		else {
			$where = array("id" => $id);
		}
		
		$query = $this->db->get_where("games", $where);
		
		
		if ($query->num_rows() == 0) {
			die("無此遊戲");
		}
		else {
			$row = $query->row();
		}
		
		$this->g_layout
			->add_breadcrumb("修改")
			->set("row", $row)
			->render();		
	}
	
	function statistics()
	{			
		$this->_init_game_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		if ($this->input->get("action")) 
		{											
			$date_field = "";
			
			switch ($this->input->get("action"))
			{
				case "流失率統計":
					
					$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
					$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
					
					$where = " where 1=1";
					
					if ($server = $this->input->get("server")) {
						$where .= " and gi.server_id={$server} ";
					} else $where .= " and gi.game_id='{$this->game_id}' ";					
					
					if ($channel = $this->input->get("channel")) {
						if ($channel == 'long_e') $where .= " and lgl.account not like '%@%' ";
						else {
							$where .= " and lgl.account like '%@{$channel}' ";
						}
					}
					
					if ($ad_channel = $this->input->get("ad_channel")) {
						$where .= " and lgl.ad like '{$ad_channel}%' ";
					}
			
					$query = $this->db->query("
							
select d, count(*) 'login_cnt', sum(role) 'role_cnt', 
	round(1-sum(role)/count(*), 3) 'role_p',
	sum(role)-sum(c1) 'c1', 
	round((sum(role)-sum(c1)) / sum(role), 3) 'c1_p',
	sum(role)-sum(c7) 'c7', 
	round((sum(role)-sum(c7)) / sum(role), 3) 'c7_p',
	sum(role)-sum(c15) 'c15', 
	round((sum(role)-sum(c15)) / sum(role), 3) 'c15_p',
	sum(role)-sum(c30) 'c30', 
	round((sum(role)-sum(c30)) / sum(role), 3) 'c30_p'
from 
(
	SELECT 
		date(lgl.create_time) 'd', lgl.uid, lgl.server_id, character_name,
		if (gsr.id is null, '0', '1') 'role',
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id 
				and create_time >= date_add(date(gsr.create_time), interval 1 day))
		) 'c1', 
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id 
				and create_time >= date_add(date(gsr.create_time), interval 6 day))
		) 'c7', 
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id 
				and create_time >= date_add(date(gsr.create_time), interval 14 day)) 
		) 'c15', 
		if (gsr.id is null, '0', (select if(count(*)>0, 1, 0) from log_game_logins
			where uid=gsr.uid and server_id=gsr.server_id 
				and create_time >= date_add(date(gsr.create_time), interval 29 day)) 
		) 'c30'
	FROM log_game_logins lgl
	join servers gi on lgl.server_id=gi.server_id
	left join characters gsr on date(gsr.create_time)=date(lgl.create_time) and gsr.uid=lgl.uid and gsr.server_id=lgl.server_id
	{$where}
	and lgl.create_time between '{$start_date}' and '{$end_date} 23:59:59'
	and not exists(select * from characters where 
		create_time < lgl.create_time and uid=lgl.uid and server_id=lgl.server_id
	)	
	group by date(lgl.create_time), lgl.uid, lgl.server_id 
) tmp
group by d														
					");
					break;
					
				case "廣告時段統計":	
					$query = $this->db->select("ga.ad, LEFT(ga.create_time, {$len}) time, COUNT(*) cnt", false)
						->from('characters ga')
						->join("servers gi", "ga.server_id=gi.server_id")
						->group_by("time, ga.ad")
						->order_by("time desc, ga.ad")->get();
					//die($this->db->last_query());
					break;					
			}			
			
			if (empty($query)) {
				if ($this->input->get("start_date")) {
					$start_date = $this->db->escape($this->input->get("start_date"));
					if ($this->input->get("end_date")) {
						$end_date = $this->db->escape($this->input->get("end_date").":59");
						$this->db->where("{$date_field} between {$start_date} and {$end_date}", null, false);	
					}	
					else $this->db->where("{$date_field} >= {$start_date}", null, false);
				}			
				
				$query = $this->db->get();
			}
		}
		else {
			$default_value = array(
				'use_default' => true,
				'time_unit' => 'day',
				'start_date' => date("Y-m-d"),
				'single_date' => date("Y-m-d"),
			);
			$_GET = $default_value;	
		}		
		
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("servers", $this->db->where("game_id", $this->game_id)->from("servers")->order_by("id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function retention_statistics()
	{			
		$this->_init_game_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id_1") ? $this->input->get("game_id_1") : ($this->input->get("game_id_2") ? $this->input->get("game_id_2") : ($this->input->get("game_id_3") ? $this->input->get("game_id_3") : ""));
					
		$query = $this->db->where("game_id", $game_id)->where("date >=", $start_date)->where("date <=", $end_date)->get("statistics");
        
		// AND gsr.create_time<date_add(lgl.create_time,interval 1 day)
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("game_id_1", $this->input->get("game_id_1"))
			->set("game_id_2", $this->input->get("game_id_2"))
			->set("game_id_3", $this->input->get("game_id_3"))
			->set("servers", $this->db->where("game_id", $this->game_id)->from("servers")->order_by("id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
	function operation_statistics()
	{			
		$this->_init_game_layout();			
		$this->load->helper("output_table");
		
		$this->zacl->check("game_statistics", "read");
		
		$start_date = $this->input->get("start_date") ? $this->input->get("start_date") : date("Y-m-d");
		$end_date = $this->input->get("end_date") ? $this->input->get("end_date") : date("Y-m-d");
		$game_id = $this->input->get("game_id_1") ? $this->input->get("game_id_1") : ($this->input->get("game_id_2") ? $this->input->get("game_id_2") : ($this->input->get("game_id_3") ? $this->input->get("game_id_3") : ""));
					
		$query = $this->db->query("
SELECT d, COUNT(*) 'login_cnt', SUM(role) 'role_cnt', 
	ROUND(SUM(role)/COUNT(*), 3) 'role_p',
	SUM(c1) 'c1', 
	ROUND((SUM(c1)) / SUM(role), 3) 'c1_p',
	SUM(c3) 'c3', 
	ROUND((SUM(c3)) / SUM(role), 3) 'c3_p',
	SUM(c7) 'c7', 
	ROUND((SUM(c7)) / SUM(role), 3) 'c7_p',
	SUM(c14) 'c14', 
	ROUND((SUM(c14)) / SUM(role), 3) 'c14_p',
	SUM(c30) 'c30', 
	ROUND((SUM(c30)) / SUM(role), 3) 'c30_p'
FROM 
(
    SELECT DATE(lgl.create_time) 'd', lgl.uid, lgl.server_id,
        (SELECT IF(COUNT(*)>0, 1, 0) FROM characters
		    JOIN servers ON characters.server_id = servers.id
            WHERE characters.uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND characters.create_time >= lgl.create_time
        ) 'role',
        (SELECT IF(COUNT(*)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 1 day)
        ) 'c1', 
        if(1=1,0,(SELECT IF(COUNT(*)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 3 day)
        )) 'c3', 
        if(1=1,0,(SELECT IF(COUNT(*)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 7 day)
        )) 'c7', 
        if(1=1,0,(SELECT IF(COUNT(*)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 14 day)
        )) 'c14', 
        if(1=1,0,(SELECT IF(COUNT(*)>0, 1, 0) FROM log_game_logins
		    JOIN servers ON log_game_logins.server_id = servers.id
            WHERE uid=lgl.uid AND servers.game_id=lgl.game_id 
                AND DATE(create_time) = DATE_ADD(DATE(lgl.create_time), interval 30 day)
        )) 'c30'
FROM
log_game_logins lgl
WHERE lgl.create_time >= '{$start_date}'
AND lgl.create_time <= '{$end_date} 23:59:59'
AND lgl.game_id = '{$game_id}'
AND lgl.is_first = 1
GROUP BY DATE(lgl.create_time), lgl.uid, lgl.game_id
) tmp
GROUP BY d
ORDER BY d");	
		// AND gsr.create_time<date_add(lgl.create_time,interval 1 day)
		$this->g_layout
			->set("query", isset($query) ? $query : false)
			->set("game_id", $game_id)
			->set("game_id_1", $this->input->get("game_id_1"))
			->set("game_id_2", $this->input->get("game_id_2"))
			->set("game_id_3", $this->input->get("game_id_3"))
			->set("servers", $this->db->where("game_id", $this->game_id)->from("servers")->order_by("id")->get())
			->add_js_include("game/statistics")
			->add_js_include("jquery-ui-timepicker-addon")
			->render();
	}
	
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */