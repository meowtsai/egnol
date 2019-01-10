<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_tgs extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->config("event");
	}

	function index()
	{
		$event_id=15;
		$result = $this->get_event_status($event_id);
		$event = $result['message'];
		//echo $this->config->item("g38_server");
		$this->_init_layout("客服中心")
			->set("event", $event)
			->set("is_ingame", $is_ingame)
			->set("g83_server",$this->config->item("g83_server"))
			->add_css_link("login")
			->add_css_link("server")
			->mobile_view();
  }

	function get_event_status($e_id){

    $query = $this->db->from("events")
    ->where("id", $e_id)
    ->select("id,game_id,event_name,status,begin_time,end_time")
    ->get();
    if ($query->num_rows()>0)
    {
      $event = $query->row();
      if (($event->status=='1' && now() > $event->begin_time && now() < $event->end_time) || IN_OFFICE)
      {
        return array("status"=>"success", "message"=>$event);
      } else {
        return array("status"=>"failure", "message"=>"活動未開放");
      }
    }
    else {
      return array("status"=>"failure", "message"=>"沒有這個活動");
    }
  }

	function event_serial_ajax(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$email = $this->input->get_post("email");
		$char_id	    = $this->input->get_post("char_id");
		$server_name	= $this->input->get_post("server_list");
		$character_name	= htmlspecialchars($this->input->get_post("character_name"));
		$serial_no	= $this->input->get_post("serial_no");

		// check if miss data
		if (!$email || !$char_id || !$server_name || !$character_name || !$serial_no) {
			die(json_encode(array("status"=>"failure", "message"=>"資料不完整")));
			return;
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			die(json_encode(array("status"=>"failure", "message"=>"E-Mail 格式錯誤。")));
		}


		if ( ! $this->input->post("serial_no")) die(json_encode(array("status"=>"failure", "message"=>"尚未填寫活動序號")));
		if (! $this->input->post("char_id")) die(json_encode(array("status"=>"failure", "message"=>"沒有角色id, 資料異常, 請重新開始流程")));

		//die(json_encode(array("status"=>"failure", "message"=>"email={$email},partner_uid={$partner_uid},char_id={$char_id},server_name={$server_name},character_name={$character_name},serial_no={$serial_no}")));
		$servers = $this->config->item("g83_server");


		$_SESSION["tgs_data"] = array(
			"char_id" => $char_id,
			"char_name" => $character_name,
			'ip' => $ip,
			'serial' => $serial_no,
			'server_name' => $server_name,
			'server_text' => $servers[$server_name],
			'email'=>$email,
			'event_id' => $event_id,
		);

		die(json_encode(array("status"=>"success", "message"=>"請確認資料是否正確以完成序號登錄")));

	}

function tgs_confirm(){
	if (!$_SESSION["tgs_data"]){
		header("Location: /event_tgs");
		die();
	}
	$data = $_SESSION["tgs_data"];

	$this->_init_layout("客服中心")
		->set("data", $data)
		->set("is_ingame", $is_ingame)
		->add_css_link("login")
		->add_css_link("server")
		->mobile_view();

}

function event_serial_confirm_ajax(){
	$event_id = 15;
	$data = $_SESSION["tgs_data"];

	$query = $this->db->query("SELECT group_concat(event_sub_id) as chk_sub_id FROM event_serial WHERE event_id={$event_id} and personal_id='{$data["char_id"]}'");
	$chk_sub_id = isset($query->row()->chk_sub_id)? $query->row()->chk_sub_id:0;


	//check
	$updated_s_id = 0;

	// get the record that you want to update
	$this->db->where(array('serial'=>$data['serial'], 'event_id' => $event_id,'status' => '0'));
	if ($chk_sub_id){
		$this->db->where_not_in("event_sub_id",explode(",",$chk_sub_id));
	}
	$query_serial = $this->db->get('event_serial');

	// getting the Id
	$serial_result = $query_serial->result_array();
	$updated_s_id = $serial_result[0]['id'];


	$log_data = array(
		"char_id" => $data["char_id"],
		"char_name" => $data["char_name"],
		'ip' => $data["ip"],
		'serial' => $data["serial"],
		'note' => $data["server_name"],
		'event_id' => $event_id,
	);



	if ($updated_s_id)
	{
		// id    | event_id | serial       | uid        | personal_id  | status | create_time         | email                  | mobile | share_code   | receive_code | event_sub_id
		$this->db->where(array('serial'=>$data['serial'], 'event_id' => $event_id,'status' => '0'));
		$this->db->update('event_serial',array("status" => 1,"personal_id"=>$data['char_id'],"email"=>$data['email']));

		$log_data["status"]=1;

		$query_item = $this->db->query("select title from serial_main where id=(select event_sub_id from event_serial where id={$updated_s_id})");
		$item_title = isset($query_item->row()->title)? $query_item->row()->title:0;




		// id | partner_uid | char_id | char_name       | ip            | serial           | create_time         | status | note | event_id
		$this->db->insert("log_serial_event", $log_data);

		$msg = "感謝您參與荒野行動TGS虛寶兌換活動!<br/>
		您本次登錄成功的資訊如下<br/>
		伺服器：<b>{$data['server_text']}</b><br/>
		角色id：<b>{$data['char_id']}</b><br/>
		角色名稱：<b>{$data["char_name"]}</b><br/>
		序號：<b>{$data["serial"]}</b><br/>
		獎勵內容<b>{$item_title}</b><br/>
		獎項將於 2019/3/15 晚上 23：59 前，以遊戲內郵件發送至所填寫的角色ID。
		";
		$this->load->library("g_send_mail");

		if($this->g_send_mail->send_view($data["email"],
			"荒野行動TGS虛寶兌獎通知信[".date("Y/m/d H:i:s")."]",
			"g_blank_mail",
			array("game_name" => "荒野行動", "msg" => $msg),
			array("headerimg" => FCPATH."/p/image/mail/header.jpg")))
		{
				$_SESSION['check_id'] = $check_id;
				$_SESSION['email'] = $this->input->post("email");
				$_SESSION['mobile'] = $this->input->post("mobile");
				unset($_SESSION["tgs_data"]);
				die(json_encode(array("status"=>"success", "item_title"=> $item_title, "message"=>"兌換成功! 獎項將於 2019/3/15 晚上 23：59 前，以遊戲內郵件發送至所填寫的角色ID。")));
		}
		else
		{
			die(json_encode(array("status"=>"failure", "message"=>"E-Mail 發送失敗。請確認E-mail為有效信箱。")));
		}




	}
	else {
		//die(json_encode(array("status"=>"failure", "site"=> $site, "message"=>"兌換失敗，序號錯誤或已被使用，錯誤五次將會鎖定。(剩餘次數:{$try_count})")));
		//die(json_encode(array("status"=>"failure", "message"=>json_encode($log_data))));
		$this->db->insert("log_serial_event", $log_data);

		die(json_encode(array("status"=>"failure", "site"=> $site, "message"=>"兌換失敗, 可能是:序號錯誤/已被使用/同組獎品僅限一次。請回上頁輸入可用序號。")));
	}



}





}
