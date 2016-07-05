<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Channel_Api
{
    var $CI;
    var $error_message = '';

    function __construct()
    {    
    	$this->CI =& get_instance();
    	$this->CI->load->config("g_api");    	    	
    }
    
    function load_config($channel)
    {
    	$tmp = $this->CI->config->item("channel_api");
    	return $tmp[$channel];
    }    

    function do_http_request($urlreq)
    {
    	$ch = curl_init();
    
    	// set URL and other appropriate options
    	curl_setopt($ch, CURLOPT_URL, "$urlreq");
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    
    	// grab URL and pass it to the browser
    	$request_result = curl_exec($ch);
    
    	// close cURL resource, and free up system resources
    	curl_close($ch);
    
    	return $request_result;
    }
    
    function _return_error($msg) {
    	$this->error_message = $msg;
    	return false;
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */