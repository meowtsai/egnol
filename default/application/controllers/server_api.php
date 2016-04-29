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
	
    function _return_error($msg) 
    {
    	$this->error_message = $msg;
    	return false;
    }
    
	function system_check_and_notify()
	{
		
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
		//$game_id = $this->input->get_post("game_id");
		$uid = $this->input->get_post("uid");
		$vendor_server_id = $this->input->get_post("server_id");
		
        log_message("error", "user_login_complete:{$uid},{$vendor_server_id}");
        
        if(!IN_OFFICE) die('0');
        
        /*$vendor_server = $this->db->from("servers")->where("address", $vendor_server_id)->order_by("server_id")->get()->row();
        
        $server_id = $vendor_server->server_id;
        $game_id = $vendor_server->game_id;
        log_message("error", "user_login_complete:1");
        
		$query = $this->db->from("log_game_logins")
		           ->where("uid", $uid)
				   ->where("is_recent", "1")
				   ->where("game_id", $game_id)->get()->row();
                   
        log_message("error", "user_login_complete:2");*/
        $query=true;
        if ($query) {
                      /*
            $this->db->where("uid", $uid)
              ->where("is_recent", '1')
              ->where("game_id", $game_id)->update("log_game_logins", array("create_time" => date('Y-m-d H:i:s'), "server_id" => $server_id, "is_ingame" => 1));

            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert(["uid" => intval($uid), "game_id" => $game_id, "server_id" => $server_id, "token" => $query->token, "device_id" => $query->device_id, "latest_update_time" => time()]);
            
        log_message("error", "user_login_complete:3");
            $this->mongo_log->executeBulkWrite("longe_log.users", $bulk);
            
            unset($bulk);
            
            $filter = ['device_id' => $query->device_id, 'game_id' => $game_id, 'uid' => null];
            $newObj = ['$set' => ['uid' => intval($uid)]];
            
            $options = ["multi" => true, "upsert" => false];
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update($filter, $newObj, $options);

            $this->mongo_log->executeBulkWrite("longe_log.le_AppPause", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppResume", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppStart", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.le_AppViewEnter", $bulk);
            $this->mongo_log->executeBulkWrite("longe_log.test_simplepost", $bulk);
            
            unset($bulk);
            
        log_message("error", "user_login_complete:4");
            $user_count_date = date('Y-m-d', time() + 59*60);
            $user_count_hour = date('G', time() + 59*60);
            $peak = 0;
            
            $uc_query = new MongoDB\Driver\Query([
                "game_id" => $game_id,
                "server_id" => $server_id
            ]);
        
            $uc_cursor = $this->mongo_log->executeQuery("longe_log.user_count", $uc_query);

            $uc_result = [];
            
            foreach ($uc_cursor as $document) {
                $uc_result[] = $document;
            }
            
        log_message("error", "user_login_complete:5");
            if (isset($uc_result[0]->count)) { 
                
                $new_count = $uc_result[0]->count + 1;
                
                $uc2_filter = ['game_id' => $game_id, "server_id" => $server_id];
                $uc2_newObj = ['$set' => ['count' => $new_count]];
                
                $uc2_options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($uc2_filter, $uc2_newObj, $uc2_options);

                $this->mongo_log->executeBulkWrite("longe_log.user_count", $bulk);
                unset($bulk);
                
                $uo_query = new MongoDB\Driver\Query([
                    "game_id" => $game_id,
                    "server_id" => $server_id, 
                    "date" => $user_count_date, 
                    "hour" => intval($user_count_hour)
                ]);
            
                $uo_cursor = $this->mongo_log->executeQuery("longe_log.user_online", $uo_query);

                $uo_result = [];
                
                foreach ($uo_cursor as $document) {
                    $uo_result[] = $document;
                }
                if (!isset($uo_result[0]->peak) || (isset($uo_result[0]->peak) && $uo_result[0]->peak < $new_count)) $peak = $new_count;
        log_message("error", "user_login_complete:6");
            } else {
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->insert(["game_id" => $game_id, "server_id" => $server_id, "count" => 1]);
                $this->mongo_log->executeBulkWrite("longe_log.user_count", $bulk);
                $peak = 1;
                unset($bulk);
        log_message("error", "user_login_complete:7");
            }
            
            if ($peak) {
                $uo_filter = ['game_id' => $game_id, "server_id" => $server_id, "date" => $user_count_date, "hour" => intval($user_count_hour)];
                $uo_newObj = ['$set' => ['peak' => $peak]];
                
                $uo_options = ["multi" => false, "upsert" => true];
                
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update($uo_filter, $uo_newObj, $uo_options);

                $this->mongo_log->executeBulkWrite("longe_log.user_online", $bulk);
                unset($bulk);
            } */
        log_message("error", "user_login_complete:8");
            
            die('1');
        } else {
            die('0');
        }
	}
    
	// 玩家在遊戲中創角色
	function user_create_character()
	{
		$server_id = $this->input->post("server_id");
		$uid = $this->input->post("uid");
		$character_id = $this->input->post("character_id");
		$character_name = $this->input->post("character_name");

		log_message("error", "user_create_character:{$uid},{$server_id},{$character_id},{$character_name}");
		
        if(!IN_OFFICE)
		{
            die('0');
        }
/*		
		// 若沒設定 server_id, 則找出最近一次登入的 server
		if(empty($server_id))
		{
			$login_game = $this->db->from("log_game_logins")->where("uid", $uid)->order_by("create_time desc")->limit(1)->get()->row();
			if(!empty($login_game))
			{
				$server_id = $login_game->server_id;
			}
		}
*/		
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
		
		die('1');
	}
}