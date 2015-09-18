<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Game_Api
{
    var $CI;
    var $error_message = '';

    function __construct()
    {    
    	$this->CI =& get_instance();
    	$this->CI->load->config("api");    	    	
    }
    
    function load_config($game)
    {
    	$tmp = $this->CI->config->item("game_api");
    	return $tmp[$game];
    }    

    function curl($url, $post_data=false, $user_ssl=false)
    {
    	// create a new cURL resource
    	$ch = curl_init();

    	// set Time out limit rule
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 25);

    	if ($user_ssl) {
    	   	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	}

    	if ($post_data) {
			curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $post_data ));
    	}

    	// set URL and other appropriate options
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	//curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

    	// grab URL and pass it to the browser
    	$url_return = curl_exec($ch);

    	// close cURL resource, and free up system resources
    	curl_close($ch);

    	return $url_return;
    }

    function run_post($url, $data)
    {
?>
	<form id="post_frm" method="POST" action="<?=$url?>" style="display:none">
		<? foreach($data as $k => $d):?>
	    <input type="hidden" name="<?=$k?>" value="<?=$d?>">
	    <? endforeach;?>
	</form>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
	$(function(){
	   	$("#post_frm").submit();
	});
	</script>
<?
    }

    function _return_error($msg) {
    	$this->error_message = $msg;
    	return false;
    }

	// 遊戲是否有入點機制
	function has_billing($site)
	{
		$cfg = $this->load_config($site);
		if(!empty($cfg['billing']))
		{
			return true;
		}

		return false;
	}

	function curl_post($url, $data)
	{
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curl_res = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($curl_res);

		return $result;
	}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */