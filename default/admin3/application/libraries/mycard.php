<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mycard
{
	var $CI;
	var $domain;
	var $key1, $key2;
	
	function __construct()
	{
		// Get the instance
		$this->CI =& get_instance();
		
		if (ENVIRONMENT == 'development') {
			$this->domain = 'http://test.b2b.mycard520.com.tw';
		} 
		else $this->domain = 'https://b2b.mycard520.com.tw'; 
		
		$this->key1 = 'mycard2008';
		$this->key2 = 'long_e2010';
	}

	function query_ingame($trade_seq)
	{		
		$hash = hash('sha256', $this->key1.'GFD00235'.$trade_seq.$this->key2);
		$url = sprintf("{$this->domain}/MyCardIngameService/CheckTradeStatus?facId=%s&facTradeSeq=%s&hash=%s", 
							'GFD00235', $trade_seq, $hash);		
		$result = my_curl($url);
		return json_decode($result);
	}
	
	function query_billing($auth_code)
	{
		$url = sprintf("{$this->domain}/MyCardBillingRESTSrv/MyCardBillingRESTSrv.svc/SingleTradeQuery?AuthCode=%s", $auth_code);
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
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		
		$key1 = 'long_e2010';
		 
		$data = array(
			"GameServiceId" => 'long_e',
			"StartDate"	=> date("Y-m-d", strtotime($start_date)),
			"StartTime"	=> date("H:i:s", strtotime($start_date)),
			"EndDate"	=> date("Y-m-d", strtotime($end_date)),
			"EndTime"	=> date("H:i:s", strtotime($end_date)),
			"SHA1Key"	=> $key1,
		);
		$client = new SoapClient("{$this->domain}/MyCardBIllingQueryDataWebService/BillingQueryData.asmx?WSDL", $data);

  		$result = $client->BillingTradeDetailQuery($data);
  		if ($result->BillingTradeDetailQueryResult->ReturnMsgNo == '1') {
  			return simplexml_load_string($result->BillingTradeDetailQueryResult->ReturnDataSet->any);
  		} else return false;  			
	}
}