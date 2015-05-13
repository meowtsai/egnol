<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/channel_api.php';

class Omg extends Channel_Api
{	
	var $conf;
	
	function __construct()
	{
		parent::__construct();
		$this->conf = $this->load_config("omg");
	}
	
    function login($site)
    {	
        if ( ! array_key_exists($site, $this->conf["sites"])) {
    		return $this->_return_error('無串接此遊戲');
    	}    	
    	
		$appkey = $this->conf['sites'][$site]["appkey"];
	
	    $url = $this->conf["login_url"];
	    $storeid = $this->conf["storeid"];
	    $hashkey = $this->conf["hashkey"];
	    $hash = md5($storeid . "|" . time() . "|" . $hashkey . "|" . $appkey);
	?>
	<form id="omg_login_frm" method="POST" action="<?=$url?>" style="display:none">
	    <input name="storeid" value="<?=$storeid?>">
	    <input name="timestamp" value="<?=time()?>">
	    <input name="appkey" value="<?=$appkey?>">
	    <input name="hash" value="<?=$hash?>">
	    <input type="submit" value="送出">
	</form>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
	$(function(){
	    $("#omg_login_frm").submit();
	});
	</script>
<?
    }
    
    function login_callback($site)
    {
    	if ( ! isset($_POST['pid'])) die('參數未傳遞');
    	 
        $pid = $_POST['pid'];
	    if ($pid == "0") {
	        die("登入驗證失敗");
	    }
	
	    $rtncode = $_POST['rtncode'];
	    if ($rtncode <> '1') {
	        die($_POST['rtnmsg']);
	    }
	
	    $timestamp = $_POST['timestamp'];
	    $hash = $_POST['hash'];
	    $pname = $_POST['pname'];
	    $hashkey = $this->conf['hashkey'];
	
	    if ( MD5($pid . "|" . $timestamp . "|" . $rtncode . "|" . $hashkey) <> $_POST['hash']) 
	    {
	        die("驗證碼錯誤!");
	    }
	    else 
	    {	        
			$user_data = array();
			$user_data['euid'] = $pid;
			$user_data['name'] = urldecode($pname);	
			return $user_data;
	    }    	
    }
      
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */