<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MY_Controller {
	
	function create_captcha()
	{	
		$this->load->helper('captcha');		
		$random = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
			
		$vals = array(
			'word'	 => $random,
			'img_path'	 => './p/captcha/',
			'img_url'	 => site_url("p/captcha").'/',
			'img_width'	 => 60,
			'img_height' => 20
		);
		
		$cap = create_captcha($vals);
		echo $cap['image'];
		
		$_SESSION["captcha"] = $random;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */