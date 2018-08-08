<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gmt extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->zacl->check_login(true);

		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}
	}

	function index()
	{
    $gmt_action = array(
    	"E_GM_ACTION_ADD_COIN" => array("code"=> 100, "title" =>"增加代幣"),
      "E_GM_ACTION_DEC_COIN" => array("code"=> 101, "title" =>"扣除代幣"),
      "E_GM_ACTION_ADD_ITEM" => array("code"=> 150, "title" =>"增加普通物品"),
      "E_GM_ACTION_DEC_ITEM" => array("code"=> 151, "title" =>"扣除普通物品"),

      //"E_GM_ACTION_SEND_MAIL" => array("code"=> 200, "title" =>"發送系統郵件"),
      //"E_GM_ACTION_SEND_ALL_MAIL" => array("code"=> 201, "title" =>"發送全服系統郵件"),

      "E_GM_ACTION_ACCOUNT_LOCK" => array("code"=> 300, "title" =>"禁止角色登錄"),
      "E_GM_ACTION_ACCOUNT_UNLOCK" => array("code"=> 301, "title" =>"解除角色禁止登錄"),
      "E_GM_ACTION_ACCOUNT_QUERY_LOCK" => array("code"=> 302, "title" =>"查詢角色是否禁止登錄"),

      "E_GM_ACTION_DEVICE_LOCK" => array("code"=> 350, "title" =>"禁止設備登錄"),
      "E_GM_ACTION_DEVICE_UNLOCK" => array("code"=> 351, "title" =>"解除設備禁止登錄"),
      "E_GM_ACTION_DEVICE_QUERY_LOCK" => array("code"=> 352, "title" =>"查詢設備是否禁止登錄"),

      "E_GM_ACTION_GET_TOWN_INFO" => array("code"=> 450, "title" =>"獲取小鎮訊息"),
      "E_GM_ACTION_GET_PLAYER_INFO" => array("code"=> 500, "title" =>"獲取玩家訊息"),


    );

		$this->_init_layout();
		$this->g_layout
		->add_breadcrumb("台版邊境之旅GM工具")
		->add_js_include("gmt/itemData")
    ->set("gmt_action",$gmt_action)
    ->render();
	}

  function ma71tw_action()
  {
    $data = file_get_contents("php://input");
    //die($data);
    //$data = '{"action":450, "townid":"205"}';
    //$townid = $this->input->post("townid");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'http://ma71na-public-tw.longeplay.com.tw:20150/gm');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);

		$action = json_decode($data)->{'action'};

    $log_data = array(
      "admin_uid" => $_SESSION["admin_uid"],
      "ip" => $_SERVER['REMOTE_ADDR'],
      "action" => $action,
      "desc" => "input:{$data}; output:{$server_output}" ,
      "create_time" => now(),
    );

    $this->DB1->insert("log_gm_actions", $log_data);

    die($server_output);


  }



}
