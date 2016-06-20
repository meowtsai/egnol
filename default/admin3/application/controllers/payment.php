<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->zacl->check_login(true);			
	}
	
	function init_payment_layout()
	{
		return $this->_init_layout()
			->add_breadcrumb("付費管理", "payment");
	}
    
	function payment_settings()
	{
		$this->zacl->check("payment_settings", "read");
		
		$this->init_payment_layout();
		$this->load->config("g_payment_gash");
		
		if ($post = $this->input->post()) 
		{
			unset($post['submit']);
            
            $new_list = (isset($post['disable_list']))?",".implode(",", $post['disable_list']):",";
            
            $filename = "./p/payment_disable_list";
            
            unlink($filename);
            $fp = fopen($filename, 'w');
            fwrite($fp, $new_list);
            fclose($fp);
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL,$this->config->item("payment_frontend_url")."payment/update_payment_disable_list");
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        http_build_query(array('disable_list' => $new_list)));
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);
            
            curl_close ($ch);
		}
		
		$this->g_layout
			->add_breadcrumb("付費選項設定", "payment/payment_settings")
			->render();
	}
}
?>