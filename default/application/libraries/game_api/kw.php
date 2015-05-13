<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun.php';

class Kw extends Kunlun 
{	
	function __construct()
	{
		parent::__construct();
		$this->kunlun_conf['login_url'] = "http://user.unite.kimi.com.tw/User/uniteLogin";
		$this->kunlun_conf['transfer_url'] = "http://user.unite.kimi.com.tw/VouchV2/addGameCoin";
		$this->kunlun_conf['check_role_url'] = "http://user.unite.kimi.com.tw/Char/getCharInfo";	
	}
	
	function load_game_conf() //override
	{
		return $this->load_config("kw");
	}
	
    function login($server, $user, $ad='') //override
    {	
    	$login_url = $this->get_login_url($server, $user, $ad);
    	header('LOCATION:'.$login_url);
    	exit();
    }
    
    function transfer($server_row, $billing_row, $rate=1)
    {
    	return parent::transfer($server_row, $billing_row, $rate);    		
    }
}