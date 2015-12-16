<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

class Ad extends MY_Controller {

	function trace() 
	{		
		$query = $this->db->select("ag.*, a.ad")
			->from("ads a")
			->join("ad_groups ag", "a.group_id=ag.id")
			->where("ad", $this->input->get('ad'))->get();			

		if ($query->num_rows() == 0) die('查無廣告');
				
		$row = $query->row();
		$data = array(
			"ad" => $row->ad,
			"game" => $row->game,
			"ip" => get_ip(),
		);		
		$this->db
			->set("create_time", "NOW()", FALSE)
			->insert("ad_traces", $data);
		
		if (get_mobile_os() == 'ios') {
			$url = $row->open_url_ios;
		}
		else {
			$url = $row->open_url_android;
		}
		
		die("
			<script type='text/javascript'>
				top.location.href = '{$url}';
			</script>		
		");
	}
	
	function g_jp()
	{
		die("
			<script type='text/javascript'>
				top.location.href = 'https://play.google.com/store/apps/details?id={$this->input->get("id")}';
			</script>		
		");
	}
	
	function check() 
	{
		$game = $this->input->post("game");
		$brand = $this->input->post("brand");
		$model = $this->input->post("model");
		$os = $this->input->post("os");
		$time = $this->input->post("time");
		$device_id = $this->input->post("device_id");
		$hash = $this->input->post("hash");
		$ip = get_ip();
		
		//log_message('error', print_r($this->input->post(), true));
		//log_message('error', "ip: ".$ip);
		if ($hash <> md5($game.$brand.$model.$os.$time.$device_id."t%race")) {
			output_json(RESPONSE_FAILD, "驗證碼錯誤");
		}
		
		$cnt = $this->db->where("game", $game)->where("device_id", $device_id)->from("ad_traces")->count_all_results();
		if ($cnt > 0) output_json(RESPONSE_FAILD, "已標記");
		
		$query = $this->db->where("create_time > date_sub(now(), interval 12 hour)", null ,false)
					->where("game", $game)->where("ip", $ip)
					->from("ad_traces")->order_by("id desc")->get();
		if ($query->num_rows() == 0) output_json(RESPONSE_FAILD, "無此記錄");
		
		$row = $query->row(); //抓最新的一筆
		
		$this->db
			->where("id", $row->id)	
			->update("ad_traces", array(
						"os" => $os,
						"brand" => $brand,
						"model" => $model,
						"device_id" => $device_id
					));		
		if ($this->db->affected_rows() > 0) {
			output_json(RESPONSE_OK, "", array("ad" => $row->ad));
		} else output_json(RESPONSE_FAILD, "update 失敗");
	}
}


function output_json($result, $err="", $arr=array()) {
	
	//if ( ! (array_key_exists("iab" ,$arr) && $result=RESPONSE_OK)) 
	
	//log_message('error', "result({$result})".(empty($err) ? '' : ", err:{$err}").(empty($arr) ? '' : ', arr: '.print_r($arr, true)));
		
	$output_arr = array("result" => $result, "error" => $err);
	if ( ! empty($arr)) $output_arr = array_merge($output_arr, $arr);
	die(json_encode($output_arr));
}
