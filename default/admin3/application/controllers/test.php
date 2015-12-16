<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller {
	
	function wer()
	{
		$this->load->model("log_admin_actions");
		$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'function', 'action', 'desc');
	}
	
	function aaa($trade_seq)
	{
		$this->load->library("mycard");
		$this->mycard->check_trade_status($trade_seq);
	}
	
	function index() 
	{
		$this->load->library("zacl");
	}
	
	function switch_account()
	{
		//die();
		if ($this->input->post()) {
			$this->g_user->switch_account($this->input->post("account"));
		}
		echo "<form method='post'>帳號<input type='text' name='account'></form>";
		echo "目前帳號:".$this->g_user->account;		
	}
	
	function ggg()
	{
		die();
		$this->load->library("g_wallet");
		
		$arr = array(		
			'304757'=>'1000',		
		);
		
		foreach ($arr as $uid => $point) {
			$order_id = $this->g_wallet->produce_order($uid, "long_e_billing", "3", $point);
			$order = $this->g_wallet->get_order($order_id);
			$this->g_wallet->complete_order($order);
			$this->g_wallet->update_order_note($order, '夢幻崑崙停止營運補點');
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */