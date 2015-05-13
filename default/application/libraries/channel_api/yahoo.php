<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Yahoo extends Channel_Api
{
	var $conf;
	
	function __construct() 
	{
		parent::__construct();
		$this->conf = $this->load_config("yahoo");
	}
	
    function login($site)
    {	    	    	
    	if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}
    	    	
    	$url = sprintf($this->conf['login_url'], $this->conf["sites"][$site]['game_name']);
    	header("location: {$url}");    	
    	exit;
    }
    
    function login_callback($site)
    {
    	$gid = $this->CI->input->get('gid');
	    $ypsd = $this->CI->input->get('ypsd');
	    $rnd = $this->CI->input->get('rnd');
	    $ts = $this->CI->input->get('ts');
	    $gametype = $this->CI->input->get('gametype');	    
	    $ys = $this->CI->input->get('ys');
	    
	   	$query = "gid={$gid}&ypsd={$ypsd}&rnd={$rnd}&ts={$ts}&gametype={$gametype}";
 	    
	    if ($this->ys_verify($query, $ys, $this->conf['secret'], $this->conf['version']) === 0) //0為equal 
	    { 
			$user_data['euid'] = strtolower($ypsd); //follow舊寫法，應該用guid比較洽當
    		$user_data['name'] = $ypsd;
    		if (array_key_exists($gametype, $this->conf['game_type'])) {
    			$user_data['site'] = $this->conf['game_type'][$gametype];
    		}
    		return $user_data;
	    }
	    else {
	    	return $this->_return_error("認證失敗");
	    }
    }
          
	function ys_verify($query, $ys, $secret, $version) {
	    $query .= $secret;
	    $b64 = base64_encode (pack("H*", sha1($query)));
	    $b64 = str_replace (array('+', '/', '='), array('.', '_', '-'), $b64);
	
	    if ($version > 0) {
	        $buf = sprintf ("%c", $version + 64);
	        $b64 .= "~$buf";
	    }
	    if (strcmp ($ys, $b64)) {
	        return 1;
	    } return 0;
	}
}



/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */