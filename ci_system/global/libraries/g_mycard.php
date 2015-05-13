<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class G_Mycard
{
	var $CI;
	var $mycard_conf;
	
	function __construct()
	{
		// Get the instance
		$this->CI =& get_instance();
				
		$this->CI->load->config("g_mycard");
		$this->mycard_conf = $this->CI->config->item("mycard");
	}

	function query_ingame($trade_seq)
	{		
		$hash = hash('sha256', $this->mycard_conf['key1'].$this->mycard_conf['facId'].$trade_seq.$this->mycard_conf['key2']);
		$url = sprintf($this->mycard_conf['check_trade_status'], $this->mycard_conf['facId'], $trade_seq, $hash);
		$result = my_curl($url);
		return json_decode($result);
	}
	
	function query_billing($auth_code)
	{
		$url = sprintf($this->mycard_conf['single_trade_query'], $auth_code);
		$result = my_curl($url);
		if ( ! empty($result)) {
			$pieces = explode("|", $result);
			return (object) array(
				"result" => $pieces[0],
				"message" => $pieces[1],
				"trade_status" => $pieces[2],		
			);
		}
		else return false;
	}
	
	function query_billing_data($start_date, $end_date) 
	{
		$data = array(
			"GameServiceId" => 'long_e',
			"StartDate"	=> date("Y-m-d", strtotime($start_date)),
			"StartTime"	=> date("H:i:s", strtotime($start_date)),
			"EndDate"	=> date("Y-m-d", strtotime($end_date)),
			"EndTime"	=> date("H:i:s", strtotime($end_date)),
			"SHA1Key"	=> $this->mycard_conf['key2'],
		);
		$client = new SoapClient($this->mycard_conf['billing_query_data'], $data);

  		$result = $client->BillingTradeDetailQuery($data);
  		if ($result->BillingTradeDetailQueryResult->ReturnMsgNo == '1') {
  			return simplexml_load_string($result->BillingTradeDetailQueryResult->ReturnDataSet->any);
  		} else return false;  			
	}
}