<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/kunlun.php';

class Xj extends Kunlun 
{	
	function load_game_conf() //override
	{
		return $this->load_config("xj");
	}    
	
    function login($server, $user, $ad='') //override
    {	
    	$login_url = $this->get_login_url($server, $user, $ad);
    	
        if ($ad == 'google') {
    		die('
<html><body>
<!-- Google Code for &#36938;&#25138; Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 978906751;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "PdYzCKGkkAcQ_9zj0gM";
var google_conversion_value = 0;
    				
setTimeout(function() {location.href="'.$login_url.'";}, 800);
    				    				
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/978906751/?value=0&amp;label=PdYzCKGkkAcQ_9zj0gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
</body></html>			
    		');
    	}
    	
    	header('LOCATION:'.$login_url);
    	exit();
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */