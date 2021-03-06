<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class G_Wallet
{
    var $CI;
    var $error_message = '';

    function __construct()
    {    
    	$this->CI =& get_instance();  
    }
    
    function get_order($order_id)
    {
    	return $this->CI->db->select("u.*, ub.*")
    				->from("user_billing ub")
    				->join("users u", "ub.uid=u.uid")
    				->where("id", $order_id)->get()->row();
    }
    
    function get_order_by_order_no($transaction_type, $order_no)
    {
    	return $this->CI->db->select("u.*, ub.*")
    				->from("user_billing ub")
    				->join("users u", "ub.uid=u.uid")
					->where("transaction_type", $transaction_type)
    				->where("order_no", $order_no)->get()->row();
    }
    
    function get_balance($uid)
    {
		$balance_sql = "
SELECT 
	x.uid,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=1 AND result=1 AND uid=x.uid GROUP BY uid), 0) aq,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=2 AND result=1 AND uid=x.uid GROUP BY uid), 0) amount,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=3 AND result=1 AND uid=x.uid GROUP BY uid), 0) rq,
        COALESCE((SELECT SUM(amount) FROM user_billing WHERE billing_type=4 AND result=1 AND uid=x.uid GROUP BY uid), 0) gq
FROM users x
WHERE x.uid={$uid}";

		$balance_result = $this->CI->db->query($balance_sql)->row();
		
    	$query = $this->CI->db->select("balance")->from("users")
    		->where("uid", $uid)->limit(1)->get();
    	if ($balance_result) {
			$balance = $balance_result->aq + $balance_result->rq + $balance_result->gq - $balance_result->amount;
    		return $balance;
    	} 
    	else return 0;
    }
            
    //billing_type: 1購買,2轉點,3回補,4贈送
    function produce_order($uid, $transaction_type, $billing_type, $amount, $pay_server_id='', $partner_order_id='', $character_id='', $order_no='', $vip_ticket_id='', $product_id='')
    {	
/*  	if ($order_no) {
	    	$cnt = $this->CI->db->from("user_billing")->where("order_no", $order_no)->where_in("result", array("1","3"))->count_all_results();
			if ($cnt > 0)  return $this->_return_error("第三方訂單號已被使用");
    	}
*/    	
        $country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
        $country_code = ($country_code) ? $country_code : null;
		
    	$user_billing_data = array(
    		'uid' 			=> $uid,
    		'transaction_type' => $transaction_type,
    		'billing_type'	=> $billing_type,
    		'amount' 		=> $amount,
    		'server_id' 	=> $pay_server_id,
    		'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    		'result'		=> '0',
    		'note'			=> '',
			'country_code'  => $country_code,
			'product_id'    => $product_id,
    	);    	
    	$partner_order_id && $user_billing_data["partner_order_id"] = $partner_order_id;
    	$character_id && $user_billing_data["character_id"] = $character_id;
    	$order_no && $user_billing_data["order_no"] = $order_no;
    	$vip_ticket_id && $user_billing_data["vip_ticket_id"] = $vip_ticket_id;
    	
    	$this->CI->db
    		->set("create_time", "now()", false)
    		->set("update_time", "now()", false)
    		->insert("user_billing", $user_billing_data);
		
    	return $this->CI->db->insert_id();
    }
    
    function complete_order($order)
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("result" => "1"));
    }
    
    function re_complete_order($order, $amount = 0, $order_no = '', $note = '')
    {
        if ($amount) {
            $user_billing_update = array("result" => "1", "order_no" => $order_no, "amount" => $amount);
            $top_up_amount = $amount;
        } else {
            $user_billing_update = array("result" => "1", "order_no" => $order_no);
            $top_up_amount = $order->amount;
        }
        
    	$this->CI->db->where("id", $order->id)->update("user_billing", $user_billing_update);
        
    	$user_billing_data = array(
    		'uid' 			=> $order->uid,
    		'transaction_type' => 'top_up_account',
    		'billing_type'	=> 2,
    		'amount' 		=> $top_up_amount,
    		'server_id' 	=> $order->server_id,
    		'ip'		 	=> $order->ip,
    		'result'		=> '0',
    		'note'			=> ($note)?$note:$order->note,
			'country_code'  => $order->country_code,
    		'partner_order_id' => $order->partner_order_id,
    		'character_id' 	=> $order->character_id,
    		'order_no' 	    => $order->order_no,
    		'vip_ticket_id' => $order->vip_ticket_id,
    	);
    	
    	$this->CI->db
    		->set("create_time", "now()", false)
    		->set("update_time", "now()", false)
    		->insert("user_billing", $user_billing_data);
        
    	return $this->CI->db->insert_id();
    }
    
    function confirm_order($order)
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("is_confirmed" => "1"));
    }
    
    function cancel_order($order, $note='')
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("result" => "2", "note" => $note));
	}
	
    function cancel_timeout_order($order)
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("result" => "3"));
	}	
	
    function cancel_other_order($order, $note='')
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("result" => "4", "note" => $note));
	}		

	// 設定狀態為已完成儲值但尚未被轉入遊戲中
    function ready_for_game_order($order)
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("result" => "5"));
    }

    function update_order_note($order, $note='', $question_id='')
    {
    	$this->CI->db->where("id", $order->id)->update("user_billing", array("note" => $note, "question_id" => $question_id));
	}		
	
    function update_order($order, $data) 
    {
		$this->CI->db
    		->set("update_time", "now()", false)
    		->where("id", $order->id)
    		->update("user_billing", $data);
    }
    
    function produce_mycard_order($uid, $mycard_billing_id, $transaction_type, $amount, $character_id, $partner_order_id='', $result='1', $server_id='')
	{	
		if ( ! in_array($transaction_type, array("mycard_ingame", "mycard_billing"))) return $this->_return_error("transaction_type 錯誤");
		$cnt = $this->CI->db->from("user_billing")->where("mycard_billing_id", $mycard_billing_id)->where("result", "1")->count_all_results();
		if ($cnt > 0)  return $this->_return_error("ID已被使用");		
    	
		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;
		
    	$user_billing_data = array(
    			'uid' 			=> $uid,
    			'transaction_type' => $transaction_type,
    			'billing_type'	=> '1',
    			'amount' 		=> $amount,
    			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    			'result'		=> $result,
    			'mycard_billing_id' => $mycard_billing_id,
				'server_id'    => $server_id,
				'character_id'  => $character_id,
				'country_code'  => $country_code,
				'partner_order_id' => $partner_order_id
    		);
    	
    	$this->CI->db->set("create_time", "now()", false)->insert("user_billing", $user_billing_data);
    	return $this->CI->db->insert_id();
    }    
    
    function produce_funapp_order($uid, $funapp_billing_id, $transaction_type, $amount, $character_id, $partner_order_id='', $result='1', $server_id='')
	{	
		if ( ! in_array($transaction_type, array("funapp_ingame", "funapp_billing"))) return $this->_return_error("transaction_type 錯誤");
		$cnt = $this->CI->db->from("user_billing")->where("funapp_billing_id", $funapp_billing_id)->where("result", "1")->count_all_results();
		if ($cnt > 0)  return $this->_return_error("ID已被使用");		
    	
		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;
		
    	$user_billing_data = array(
    			'uid' 			=> $uid,
    			'transaction_type' => $transaction_type,
    			'billing_type'	=> '1',
    			'amount' 		=> $amount,
    			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    			'result'		=> $result,
    			'funapp_billing_id' => $funapp_billing_id,
				'server_id'     => $server_id,
				'character_id'  => $character_id,
				'country_code'  => $country_code,
				'partner_order_id' => $partner_order_id
    		);
    	
    	$this->CI->db->set("create_time", "now()", false)->insert("user_billing", $user_billing_data);
    	return $this->CI->db->insert_id();
    } 
    
    function produce_gash_order($uid, $gash_billing_id, $amount, $character_id, $coid, $partner_order_id, $result='1', $server_id='')
	{	
		$cnt = $this->CI->db->from("user_billing")->where("gash_billing_id", $gash_billing_id)->where("result", "1")->count_all_results();
		if ($cnt > 0)  return $this->_return_error("ID已被使用");			
			
		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;
    	
    	$user_billing_data = array(
    			'uid' 			=> $uid,
    			'transaction_type' => 'gash_billing',
    			'billing_type'	=> '1',
    			'amount' 		=> $amount,
    			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    			'result'		=> $result,
    			'gash_billing_id' => $gash_billing_id,
				'server_id'    => $server_id,
				'character_id'  => $character_id,
				'country_code'  => $country_code,
				'order_no'		=> $coid,
				'partner_order_id' => $partner_order_id
    		);
    	
    	$this->CI->db->set("create_time", "now()", false)->insert("user_billing", $user_billing_data);
    	return $this->CI->db->insert_id();
    }      
    
    function produce_income_order($uid, $tran_type, $tran_id, $amount, $order='')
	{	
		if ($order) {
			$cnt = $this->CI->db->from("user_billing")
					->where("transaction_type", $tran_type)->where("order", $order)->where("result", "1")->count_all_results();
			if ($cnt > 0)  return $this->_return_error("第三方訂單號已被使用");
		}
		else {
			$cnt = $this->CI->db->from("user_billing")
					->where("transaction_type", $tran_type)->where("transaction_id", $tran_id)
					->where("result", "1")->count_all_results();
			if ($cnt > 0) return $this->_return_error("交易ID已被使用");
		}
    	
		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;
		
    	$user_billing_data = array(
    			'uid' 			=> $uid,
    			'transaction_type' => $tran_type,
    			'transaction_id'=> $tran_id,    			
    			'billing_type'	=> '1',
    			'amount' 		=> $amount,
    			'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    			'result'		=> '1',
				'country_code'  => $country_code,
    		);
    	$order && $data["order"] = $order;
    	
    	$this->CI->db
    		->set("create_time", "now()", false)
    		->set("update_time", "now()", false)
    		->insert("user_billing", $user_billing_data);
    	
    	return $this->CI->db->insert_id();
    }      
    
	// iOS/Android in-app purchase 訂單
    function produce_iap_order($uid, $transaction_type, $billing_type, $server_id, $partner_order_id, $product_id, $verify_code, $character_id = '')
    {	
		$cnt = $this->CI->db->from("user_billing")->where("partner_order_id", $partner_order_id)->where_in("result", array("1","3"))->count_all_results();
		if($cnt > 0)
			return $this->_return_error("廠商訂單號已被使用");

		$country_code = geoip_country_code3_by_name($_SERVER['REMOTE_ADDR']);
		$country_code = ($country_code) ? $country_code : null;
		
    	$user_billing_data = array(
    		'uid' 			=> $uid,
    		'transaction_type' => $transaction_type,
    		'billing_type'	=> $billing_type,
    		'server_id' 	=> $server_id,
    		'ip'		 	=> $_SERVER['REMOTE_ADDR'],
    		'result'		=> '0',
    		'product_id'	=> $product_id,
    		'verify_code'	=> $verify_code,
			'country_code'  => $country_code,
			'partner_order_id' => $partner_order_id,
    	);    	
    	
    	$character_id && $user_billing_data["character_id"] = $character_id;
		
    	$this->CI->db
    		->set("create_time", "now()", false)
    		->set("update_time", "now()", false)
    		->insert("user_billing", $user_billing_data);
			
    	return $this->CI->db->insert_id();
    }
	
	//檢查Google訂單是否已經完成支付和給點, 如果回傳1 veryify就不再繼續 直接回覆原廠成功
    function check_isGPOrderCompleted($transaction_id)
    {
        $checkOrder_sql = "SELECT IF(((SELECT count(*) FROM user_billing WHERE order_no='{$transaction_id}' AND transaction_type='inapp_billing_google' AND result=1)=1) AND (SELECT COUNT(*) FROM user_billing WHERE order_no='{$transaction_id}' AND transaction_type='top_up_account' AND result=1)>=1,true,false ) AS result";
    	$order_result = $this->CI->db->query($checkOrder_sql)->row();
        return $order_result->result;
    }
    
    function _return_error($msg) {
    	$this->error_message = $msg;
    	return false;
    }
    
    //取得商品資訊
     public function get_product_by_prodid($prod_id)
    {
        return $this->CI->db->select("*")
    				->from("products")
    				->where("id", $prod_id)->get()->row();
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */