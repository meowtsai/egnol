<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class R2g extends Game_Api
{	
    function __construct()
    {    
    	parent::__construct();
    }
	
	// 第三方金流入點
    function transfer($server, $order, $amount, $rate)
    {
		$partner_api = $this->CI->config->item("partner_api");
		
		$order_id = $order->id;
		$transaction_id = $order->order_no;
		$partner_order_id = $order->partner_order_id;
		$product_id = $order->note;
		$price = $amount;
		$currency = "TWD";
		
		$app_key = $partner_api['netease']['sites']['r2g']['key'];
		$time = time();
		$rand = strval(rand());
		
		$str = "{$order_id}{$time}{$price}{$rand}{$product_id}{$app_key}{$transaction_id}{$currency}{$partner_order_id}";
        $verify = MD5($str) . $rand;

        $res = $this->curl_post($server->address, array(
													'order_id'=>$order_id,
													'transaction_id'=>$transaction_id,
													'partner_order_id'=>$partner_order_id,
													'product_id'=>$product_id,
													'price'=>$price,
													'currency'=>$currency,
													'verify'=>$verify,
													'time'=>$time));

		if($res->code === '200')
		{
			return "1";
		}
		else
		{
            return $this->_return_error("點數轉入錯誤：" . $res->msg);
		}
	}
	
	// In-App Purchase 入點
    function iap_transfer($order_id, $product_id, $price, $currency, $transaction_id, $partner_order_id, $uid, $server_id, $character_id, $verify_code)
    {
		$partner_api = $this->CI->config->item("partner_api");
		$game_api = $this->CI->config->item("game_api");
		
		$app_key = $partner_api['netease']['sites']['r2g']['key'];
		$time = time();
		$rand = strval(rand());
		
		$str = "{$order_id}{$time}{$price}{$rand}{$product_id}{$app_key}{$transaction_id}{$currency}{$partner_order_id}";
        $verify = MD5($str) . $rand;
		
        $res = $this->_curl_post($game_api['r2g']['billing'], array(
													'order_id'			=> $order_id,
													'transaction_id'	=> $transaction_id,
													'partner_order_id'	=> $partner_order_id,
													'product_id'		=> $product_id,
													'price'				=> $price,
													'currency'			=> $currency,
													'verify'			=> $verify,
													'time'				=> $time));

		if($res->code == 200)
		{
			return "1";
		}
		else
		{
            return $this->_return_error("點數轉入錯誤：" . $res->msg);
		}
	}
}