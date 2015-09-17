<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Stm extends Game_Api
{	
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("stm");
    }

	// 檢查並取得帳號角色
	function check_user($uid)
	{
		$character = array('id'=>"-1",'name'=>"");

        $res = $this->ajax_post($this->conf['billing']."/apilonge-checkuser", array('uid'=>$uid));
		if($res['status'] == 'E000')
		{
			$pos = strrpos($res['msg'], ":");
			if($pos != FALSE)
			{
				$character['id'] = substr($res['msg'], $pos + 1);
				$character['name'] = substr($res['msg'], 0, $pos);
			}
		}

		return $character;
	}

	// 入點
    function transfer($server, $order, $amount, $rate)
    {
		$character = $this->check_user($order->uid);
		if($character['id'] == "-1")
		{
			return _return_error("角色尚未建立！");
		}

		$points = $amount * $rate;
        $sig = MD5("{$order->id}{$order->uid}{$character['id']}{$points}r1g4284gj94ek");
        $res = $this->ajax_post($this->conf['billing']."/apilonge-billing",
								array('orderid'=>$order->id,
										'account'=>$order->uid,
										'roleid'=>$character['id'],
										'amount'=>$points,
										'sig'=>$sig));
		if($res['status'] == 'E000')
		{
			return "1";
		}
		else
		{
            return _return_error("點數轉入錯誤：".$res['status']);
		}
    }
}