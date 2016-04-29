<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/game_api.php';

class R2g extends Game_Api
{	
    function __construct()
    {    
    	parent::__construct();
    }
	
	// 判斷 server id 為原廠編號或我方代碼, 並轉換成我方代碼
	function convert_server_id($input_id)
	{
		$server_info = $this->db->from("servers")->where("server_id", $input_id)->get()->row();
		if (empty($server_info))
		{
			$server_info = $this->db->from("servers")->where("address", $input_id)->get()->row();
			if (empty($server_info))
			{
				return false;
			}
		}
			
		return $server_info->server_id;
	}
	
	function get_apple_bundle_id()
	{
		$partner_api = $this->CI->config->item("partner_api");
		
		return $partner_api['netease']['sites']['r2g']['Apple']['BundleID'];
	}
	
	// 第三方金流入點
    function transfer($server, $order, $amount, $rate, $gash_billing_id='')
    {
		$partner_api = $this->CI->config->item("partner_api");
		$game_api = $this->CI->config->item("game_api");
	
		$order_id = $order->id;
		$uid = $order->uid;
		$transaction_id = $order->order_no;
		$partner_order_id = $order->partner_order_id;
		$product_id = "r2g_" . $amount;
		$price = $amount;
		$currency = "TWD";
		
		$app_key = $partner_api['netease']['sites']['r2g']['key'];
		$time = time();
		
		$character = $this->CI->db->from("characters")->where("id", $order->character_id)->get()->row();
		$server = $this->CI->db->from("servers")->where("server_id", $character->server_id)->get()->row();
		
		$free_point = 0;
		
		// 針對 GASH 活動
		if(!empty($gash_billing_id) && $server->server_id === 'r2gtest')
		{
			$gash_billing = $this->CI->db->from("gash_billing")->where("id", $gash_billing_id)->get()->row();
			if($gash_billing->PAID === 'COPGAM02' || $gash_billing->PAID === 'COPGAM05' || $gash_billing->PAID === 'COPGAM09')
			{
				// GASH 帳號點數/點數卡/點數卡(手機)
				$free_point = intval(floatval($amount) * 0.15);

				if($free_point > 0)
					$product_id = $product_id . "+{$free_point}";
				
				log_message("error", "transfer: GASH payment event for {$gash_billing->PAID} add {$free_point} point");
			}
		}
		
		$server_num = $server->address;
		$partner_character_id = $character->in_game_id;
		
		if(empty($partner_order_id))
		{
			$pay_type = 'web';
		
			$str = "{$currency}{$order_id}{$pay_type}{$price}{$product_id}{$partner_character_id}{$server_num}{$time}{$transaction_id}{$uid}{$app_key}";
			$verify = MD5($str);

			log_message("error", "transfer:".$str);

			$res = $this->curl_post($game_api['r2g']['billing'], array(
														'order_id'=>$order_id,
														'transaction_id'=>$transaction_id,
														'product_id'=>$product_id,
														'price'=>$price,
														'currency'=>$currency,
														'uid'=>$uid,
														'role_id'=>$partner_character_id,
														'server_id'=>$server_num,
														'pay_type'=>$pay_type,
														'verify'=>$verify,
														'time'=>$time));

			log_message("error", "transfer:{$res->code}, ".json_encode($res));
		}
		else
		{
			$pay_type = 'web_inapp';

			$str = "{$currency}{$order_id}{$partner_order_id}{$pay_type}{$price}{$product_id}{$partner_character_id}{$server_num}{$time}{$transaction_id}{$uid}{$app_key}";
			$verify = MD5($str);

			log_message("error", "transfer:".$str);

			$res = $this->curl_post($game_api['r2g']['billing'], array(
														'order_id'=>$order_id,
														'transaction_id'=>$transaction_id,
														'partner_order_id'=>$partner_order_id,
														'product_id'=>$product_id,
														'price'=>$price,
														'currency'=>$currency,
														'uid'=>$uid,
														'role_id'=>$partner_character_id,
														'server_id'=>$server_num,
														'pay_type'=>$pay_type,
														'verify'=>$verify,
														'time'=>$time));

			log_message("error", "transfer:".json_encode($res));
		}

		if(intval($res->code) == 200)
		{
			return "1";
		}
		else
		{
            return $this->_return_error("點數轉入錯誤：" . $res->msg);
		}
	}
	
	// In-App Purchase 入點
    function iap_transfer($order, $server, $pay_type, $product_id, $price, $currency)
    {
		$partner_api = $this->CI->config->item("partner_api");
		$game_api = $this->CI->config->item("game_api");
		$app_key = $partner_api['netease']['sites']['r2g']['key'];
		$time = time();

		$order_id = $order->id;
		$uid = $order->uid;
		$transaction_id = $order->order_no;
		$partner_order_id = $order->partner_order_id;
		
		$character = $this->CI->db->from("characters")->where("id", $order->character_id)->get()->row();
		
		$server_num = $server->address;
		$partner_character_id = $character->in_game_id;
		
		$str = "{$currency}{$order_id}{$partner_order_id}{$pay_type}{$price}{$product_id}{$partner_character_id}{$server_num}{$time}{$transaction_id}{$uid}{$app_key}";
        $verify = MD5($str);

		log_message("error", "iap_transfer:".$str);
		
        $res = $this->curl_post($game_api['r2g']['billing'], array(
													'order_id'=>$order_id,
													'transaction_id'=>$transaction_id,
													'partner_order_id'=>$partner_order_id,
													'product_id'=>$product_id,
													'price'=>$price,
													'currency'=>$currency,
													'uid'=>$uid,
													'role_id'=>$partner_character_id,
													'server_id'=>$server_num,
													'pay_type'=>$pay_type,
													'verify'=>$verify,
													'time'=>$time));

		log_message("error", "iap_transfer:".json_encode($res));

		if(intval($res->code) == 200)
		{
			return "1";
		}
		else
		{
            return $this->_return_error($res->msg);
		}
	}
}