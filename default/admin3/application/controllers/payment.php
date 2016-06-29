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
    
	function test_points()
	{
		//$this->zacl->check("payment_settings", "read");
		
		$this->init_payment_layout();
		//$this->load->config("g_payment_gash");
		
        $msg_type = 'error';
		$msg = array();
        
		if ($post = $this->input->post()) 
		{
			
            $uid = intval($this->input->post("uid"));
            $game_id = $this->input->post("game");
            $server_id = $this->input->post("server");
            $points = intval($this->input->post("points"));

            if(empty($uid) || empty($server_id) || empty($points))
            {
                $msg[] = "參數錯誤!";
            }
            if($points < 1 || $points > 10000)
            {
                $msg[] = "點數超出範圍!";
            }

            $character = $this->db->where("server_id", $server_id)->where("uid", $uid)->from("characters")->get()->row();
            if(empty($character))
            {
                $msg[] = "沒有角色!";
            }

    //		$order_no = $this->_make_trade_seq();

            $this->load->library("g_wallet");

            $order_id = $this->g_wallet->produce_order($uid, "free_points", "4", $points, $server_id, '', $character->id);//, $order_no);
            $order = $this->g_wallet->get_order($order_id);

            $msg[] = "交易序號: {$order_id}<br/>";

            // 先看是否有遊戲入點機制, 若有則轉點, 無則設為尚未轉進遊戲
            $this->load->library("game_api");
            if($this->game_api->has_billing($game_id))
            {
                // 呼叫遊戲入點機制
                $this->load->library("game_api/{$game_id}");
                
                $server = $this->DB2->from("servers")->where("server_id", $server_id)->get()->row();
                $game = $this->DB2->from("games")->where("game_id", $game_id)->get()->row();
                
                $res = $this->{$game_id}->transfer($server, $order, $points, $game->exchange_rate);
                $error_message = $this->{$game_id}->error_message;

                if ($res === "1") {
                    $this->g_wallet->complete_order($order);
                    $msg[] = "贈點成功!";
                    $msg_type = 'success';
                }
                else if ($res === "-1") {
                    $this->g_wallet->cancel_timeout_order($order);
                    $msg[] = "遊戲伺服器沒有回應(錯誤代碼: 002)";
                }
                else if ($res === "-2") {
                    $this->g_wallet->cancel_other_order($order, $error_message);
                    $msg[] = "{$error_message}(錯誤代碼: 003)";
                }
                else {
                    $this->g_wallet->cancel_order($order, $error_message);
                    $msg[] = "{$error_message}";
                }
            }
		}
		
		$games = $this->DB2->from("games")->get();
		$servers = $this->DB2->from("servers")->order_by("server_id desc")->get();	
        
		$this->g_layout
			->add_breadcrumb("測試點數", "payment/test_points")
			->add_js_include("payment/test_points")
			->set("games", $games)
			->set("servers", $servers)
            ->set("msg", $msg)
            ->set("msg_type", $msg_type)
			->render();
	}
}
?>