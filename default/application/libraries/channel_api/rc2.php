<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Rc2 extends Channel_Api
{
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("rc2");
	}
	
    function login($site)
    {    	
    	if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	header("LOCATION: {$this->conf['sites'][$site]["login_url"]}");
    }
    
    function login_callback($site)
    {
    	$key = $this->conf['login_key'];
    	
    	$rc_uid = $this->CI->input->get("uid");
		$sid = $this->CI->input->get("sid");
		$cm = $this->CI->input->get("cm");
		$ltime = $this->CI->input->get("ltime");
		$sign = $this->CI->input->get("sign");
		$game = $this->CI->input->get("game");
		
		$ad = $this->CI->input->get("ad");
		$aid = $this->CI->input->get("aid");
		
		//TODO 處理sid
		if ($sid !== null) $server_id = "{$game}_".sprintf("%02d", $sid);
		else $server_id = $game;	
		
		$server_row = $this->CI->db->from("servers")->where("server_id", $server_id)->get()->row();
		if (empty($server_row)) return $this->_return_error('無此伺服器');
		
		//http://www.long_e.com.tw/gate/login_callback/rc2?game=mon&uid=123456&sid=0&cm=0&ltime=123456789&sign=0a8129ba56358e1a4ba228c443e45f1e
		//die(md5($rc_uid.$sid.$cm.$ltime.$key));
		
       	if ($sign == md5($rc_uid.$sid.$cm.$ltime.$key)) 
       	{    		
			$user_data['euid'] = $rc_uid;
			$user_data['site'] = $game;
			$user_data['sid'] = $server_row->id;
			$user_data['ad'] = $ad.( ! empty($aid) ? '_'.$aid : '');
    		return $user_data;
    	}
    	else {
    		return $this->_return_error('驗證碼錯誤');
    	}
    }
      
}
