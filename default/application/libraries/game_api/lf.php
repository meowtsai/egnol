<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class Lf extends Game_Api
{    
    var $conf;

    function __construct()
    {    
    	parent::__construct();
    	$this->conf = $this->load_config("lf");    	
    }
    
    function login($server, $user, $ad)
    {	    	    	
    	echo $this->CI->load->view("gate/login_game/lf", array("server"=>$server, "user"=>$user, "ad"=>$ad, "key"=>$this->conf['login_key']), true);
    	exit();
    }
    
    function transfer($server, $billing, $rate=1)
    {			
		//euid,服务器ID,payGold,payNum(订单号),time(时间戳,如1371719717），验证码（MD5(euid + 服务器ID + payGold + payNum + time + key))）
		$data['euid'] = $this->CI->g_user->encode($billing->uid);
		$data['sid'] = 'lxf01';
		$data['payGold'] = $billing->amount * intval($rate);
		$data['payNum'] = $billing->id;
		$data['time'] = time();
		$data['hash'] = md5($data['euid'].$data['sid'].$data['payGold'].$data['payNum'].$data['time'].$this->conf['transfer_key']);		
		
		$sbody = '';
		foreach($data as $val) {
			$sbody .= chr(strlen($val)).$val;	
		}
		
		$blen = strlen($sbody);
		for($i =0; $i < $blen; $i++) {
			$sbody[$i] = $sbody[$i] ^ chr(0x37);			
        }
        $sbody = $sbody . chr(0x37);
        $len = $blen+1;
        $cmd = chr(0xff) . chr(0xd8) . chr(24) . chr(2) . chr($len >> 24 & 0xff) . chr($len >> 16 & 0xff) . chr($len >> 8 & 0xff) . chr($len & 0xff) . $sbody;
 		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket create failed");
 		 		
 		$sip = $server->address;
		$sport = '4901';
        socket_connect($sock, $sip, $sport) or die("err");
        socket_write($sock, $cmd, strlen($cmd)) or die("err2");
        
        $re = socket_read($sock, 1024);      
        
   		if (empty($re) && $re <> '0') {
    		return "-1";
    	}
    	else {	
			if ($re == '0') {
	    		return '1';
	    	}
	    	else {	  
	    		$error_table = array(  			
    				'-1' => '版本錯誤',
					'-2' => '充值IP驗證未通過',
					'-3' => '參數不全',
					'-4' => '訂單超時',
					'-5' => '驗證未通過',
					'-6' => '遊戲中無此帳戶',
					'-7' => '玩家還未創建角色',
	    		);
		    	return $this->_return_error($re.' '.$error_table[$re]);
		    }
    	}      	   
    }
    
    function check_role_status($server, $user)
    {
		$data['sid'] = '1';
    	$data['euid'] = $this->CI->g_user->encode($user->uid);		

		$sbody = '';
		foreach($data as $val) {
			$sbody .= chr(strlen($val)).$val;	
		}
		var_dump($sbody);
		
		$blen = strlen($sbody);
		for($i =0; $i < $blen; $i++) {
			$sbody[$i] = $sbody[$i] ^ chr(0x37);			
        }
        $sbody = $sbody . chr(0x37);
        $len = $blen+1;
        $cmd = chr(0xff) . chr(0xd8) . chr(0) . chr(6) . chr($len >> 24 & 0xff) . chr($len >> 16 & 0xff) . chr($len >> 8 & 0xff) . chr($len & 0xff) . $sbody;
 		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket create failed");
 		 		
 		$sip = $server->address;
		$sport = '4901';
        socket_connect($sock, $sip, $sport) or die("err");
        socket_write($sock, $cmd, strlen($cmd)) or die("err2");
        
        $re = socket_read($sock, 1024);
        var_dump($re);      
        return $re;
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */