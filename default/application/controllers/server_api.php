<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("RESPONSE_OK", "1");
define("RESPONSE_FAILD", "0");

//
// �|���t�μt�Ө�@�\�� API
//  - ui �e�m�� function �������� web �e���� API
//  - �̭������� Javascript �� LongeAPI.* function ���I�s SDK ���걵�\��
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
		$this->load->library(array("Mongo_db"));		
        
        $this->mongo_log = new Mongo_db(array("activate" => "default"));
	}
    
    function validate_token() {
        
        if ($this->input->get_post("uid")) $uid = $this->input->get_post("uid");
        else return $this->_return_error("��줣����");
        if ($this->input->get_post("game_id")) $game_id = $this->input->get_post("game_id");
        else return $this->_return_error("��줣����");
        if ($this->input->get_post("token")) $token = $this->input->get_post("token");
        else return $this->_return_error("��줣����");
        
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
            return $this->_return_error("���A�����}��");
        }

        $log_user = $this->mongo_log->where(array("uid" => (string)$uid, "game_id" => $game_id))->select(array('token'))->get('users');
        
        if ($log_user[0]['token'] && $log_user[0]['token'] == $token) {
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