<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller {


	function get_question($id)
	{

		$q = $this->DB2->from("questions")
		->where("admin_uid is not null", null, false)
		->where("status", "2")
		->where("(allocate_status='2' or allocate_status='0')", null, false)
		->where("id", $id)
		->get();

		echo print_r($q);

	}
function test_explode()
{
	$array_post_types  = explode(',' , "b,9,a");

	//更新問題類型 faq_types
	$updateSql="INSERT INTO faq_types (faq_id,type_id) VALUES";
	for ($i=0; $i <count($array_post_types) ; $i++) {
		if ($i>0) $updateSql .= ",";
		$updateSql .= "(123 ,'{$array_post_types[$i]}')";
	}

	$updateSql .= " ON DUPLICATE KEY UPDATE type_id=type_id";

	echo $updateSql;
}

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
