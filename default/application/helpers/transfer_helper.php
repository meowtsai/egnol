<?php

function go_index($message='') 
{
	die("<script type='text/javascript'>
			".($message ? "alert('{$message}')" : "")."
			location.href = '".site_url("eWallet/transfer_index.php")."'
		</script>");
}

function go_result($status, $message, $args='') 
{
	header('location: '.site_url("wallet/transfer_result?status={$status}&message=".urlencode($message)."&".$args));
	exit();
}

function chk_price($price) 
{
	$CI =& get_instance();
	
	$mobile_point =  array(60, 150, 300, 660, 790, 1490, 3590, 5990, 9990, 14900, 29900);
	$product_point = array_unique(array_merge(array(50, 100, 200, 300, 500, 1000, 2000, 3000, 5000, 10000), $mobile_point));	
	
	if ($price < 0) {	    	
    	go_result(0, '設定金額錯誤'); 
    }
    else if ( ! in_array($price, $product_point)) {
    	if ( ! in_array($CI->g_user->uid, array('304757','300187','440569','433555','433558','433560','2247045'))) {
    		go_result(0, '設定金額錯誤');
    	}
    }
}

function chk_trade_limit($transaction_type, $limit, $go_result=false) 
{
	$CI =& get_instance();

	$query = $CI->db->query("
		SELECT substring(create_time, 1, 10), sum(amount) FROM long_e.user_billing ub
			WHERE transaction_type='{$transaction_type}' and result=1 and create_time >= substring(now(), 1, 10)
		GROUP BY substring(create_time, 1, 10) HAVING sum(amount) > {$limit};");
	
	if ($query->num_rows() > 0) {
		if ($go_result) go_result(0, '本日已超過金流交易上限值，若持續發生此狀況，請至客服中心與我們聯繫。');
		return true; 
	}
	return false;
}
