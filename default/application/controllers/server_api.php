<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

//
// 會員系統廠商協作功能 API
//  - ui 前置的 function 為有提供 web 畫面的 API
//  - 裡面有執行 Javascript 的 LongeAPI.* function 為呼叫 SDK 的串接功能
//
class Server_api extends MY_Controller
{
	var $partner_conf;
	
	var $partner, $game, $time, $hash, $key;
    
    var $mongo_log;
    
    var $error_message = '';
	
	function __construct()
	{
		parent::__construct();
		$this->load->config('api');		
		$this->partner_conf = $this->config->item("partner_api");
        
        $this->load->config('g_mongodb');
        $g_mongodb = $this->config->item('mongo_db');
        $this->mongo_log = new MongoDB\Driver\Manager($g_mongodb['url']);
	}
    
    function validate_token() {
        
        if ($this->input->get_post("uid")) $uid = $this->input->get_post("uid");
        else return $this->_return_error("欄位不齊全");
        if ($this->input->get_post("game_id")) $game_id = $this->input->get_post("game_id");
        else return $this->_return_error("欄位不齊全");
        if ($this->input->get_post("token")) $token = $this->input->get_post("token");
        else return $this->_return_error("欄位不齊全");
        
    	$pass_ips = array();    	
    	foreach($this->partner_conf as $partner => $item)
		{
    		if (isset($item['sites']) && isset($item['ips']) && array_key_exists($game_id, (array) $item['sites']))
			{
    			$pass_ips = array_merge($pass_ips, $item['ips']);
    		}
    	}    
    	$pass = in_array($_SERVER["REMOTE_ADDR"], $pass_ips);
        
        if ( ! (IN_OFFICE || $pass)) {
            return $this->_return_error("伺服器不開放");
        }

        $query = new MongoDB\Driver\Query([
            "uid" => intval($uid),
            "game_id" => $game_id
        ]);
    
        $cursor = $this->mongo_log->executeQuery("longe_log.users", $query);

        $result = [];
        
        foreach ($cursor as $document) {
            $result[] = $document;
        }
        
        if (isset($result[0]->token) && $result[0]->token == $token) {
            echo 1;
            return true;
        } else {
            echo 0;
            return false;
        }
    }
	
	// 玩家在遊戲 server 中完成遊戲本身的登入程序(在龍邑的登入程序之後)
	function user_login_complete()
	{
		$game_id = $this->input->post("game_id");
		$uid = $this->input->post("uid");
		$server_id = $this->input->post("server_id");
		
        if(!IN_OFFICE)
		{
            die('0');
        }
		
		
		log_message("error", "user_login_complete:{$uid},{$server_id}");
		
		die('1');
	}
    
	// 玩家在遊戲中創角色
	function user_create_character()
	{
		$server_id = $this->input->post("server_id");
		$uid = $this->input->post("uid");
		$character_id = $this->input->post("character_id");
		$character_name = $this->input->post("character_name");
		
        if(!IN_OFFICE)
		{
            die('0');
        }
		
		// 若沒設定 server_id, 則找出最近一次登入的 server
		if(empty($server_id))
		{
			$login_game = $this->db->from("log_game_logins")->where("uid", $uid)->order_by("create_time desc")->limit(1)->get()->row();
			if(!empty($login_game))
			{
				$server_id = $login_game->server_id;
			}
		}
		
		log_message("error", "user_create_character:{$server_id},{$uid},{$character_id},{$character_name}");

		$server_info = $this->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $server_id)->get()->row();
			if (empty($server_info))
			{
				die('0');
			}
			
			$server_id = $server_info->server_id;
		}
		
		$query = $this->db->from("users")->where("uid", $uid)->get();
		if ($query->num_rows() == 0)
		{
			die('0');
		}
		
		$this->load->model("g_characters");
		if ($this->g_characters->chk_character_exists($server_info, $uid, $character_name))
		{
			die('0');
		}
		
		$insert_id = $this->g_characters->create_character($server_info,
			array(
				"uid" => $uid,
				'name' => $character_name,
				'in_game_id' => $character_id
			));
		
		if (empty($insert_id))
		{
			die('0');
		}

		log_message("error", "user_create_character:{$insert_id}");
		
		die('1');
	}
	
    function _return_error($msg) 
    {
    	$this->error_message = $msg;
    	return false;
    }
}