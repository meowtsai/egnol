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
            "uid" => (string)$uid,
            "game_id" => $game_id
        ]);
    
        $cursor = $this->mongo_log->executeQuery("longe_log.users", $query);

        $result = [];
        
        foreach ($cursor as $document) {
            $result[] = $document;
        }
        
        if (isset($result[0]['token']) && $result[0]['token'] == $token) {
            echo 1;
            return true;
        } else {
            echo 0;
            return false;
        }
    }
    
    function _return_error($msg) 
    {
    	$this->error_message = $msg;
    	return false;
    }
}