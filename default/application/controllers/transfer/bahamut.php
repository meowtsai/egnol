<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bahamut extends MY_Controller {
	
	var $conf, $v=array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library("g_wallet");
		$this->load->helper("transfer");
		
		$this->load->config("api");
		$api = $this->config->item("channel_api");
		$this->conf = $api['bahamut'];
	}	
	
	function trade()
	{
		$this->v['TID'] = $this->input->post("TID");
		$this->v['RCODE'] = 'E000';
		$this->v['RMSG'] = '';
				
		//$this->v['TID'] = "PST000000000000"; //----- test

		fb('start');
		$trade_result = $this->_trade();
		//fb($this->v, 'result');
		//fb($trade_result, 'trade_result');
		fb('end');
		
		$TIME = date("YmdHis", time());
		$CHECKCODE = substr(md5($this->conf["transfer_key"].$this->v['GID'].$this->v['TID'].$this->v['UID'].$this->v['RCODE'].$TIME), 7, 16);

		$re = my_curl($this->conf["transfer_result_url"], array("GID"=>$this->v['GID'], "TID"=>$this->v['TID'], "UID"=>$this->v['UID'], "RCODE"=>$this->v['RCODE'], "RMSG"=>$this->v['RMSG'], "TIME"=>$TIME, "CHECKCODE"=>$CHECKCODE));
		//fb($re, 're');
		if ( ! empty($re)) 
		{		
			parse_str($re, $arr);
			//fb($arr, 'arr');
			if ($arr['CHECKCODE'] <> substr(md5($this->conf["transfer_key"].$arr['GID'].$arr['TID'].$arr['UID'].$arr['RCODE'].$arr['TIME']), 7, 16)) {
				if ($trade_result) $this->g_wallet->cancel_other_order($this->v['order'], "回傳巴哈失敗(驗證碼錯誤)");	
			}	
							
			if ($arr['RCODE'] == 'S000') //回傳請求成功 
			{ 
				//success
			}
			else 
			{
				if ($trade_result) $this->g_wallet->cancel_other_order($this->v['order'], "回傳巴哈失敗({$arr['RCODE']}{$arr['RMSG']})");	
			}
		}
		else {
			if ($trade_result) $this->g_wallet->cancel_other_order($this->v['order'], "回傳巴哈失敗(伺服器未回應)");	
		}
	}
	
	function _trade()
	{		
		$site = $this->input->get("site");
		$TIME = $this->input->post("TIME");
		$CHECKCODE = $this->input->post("CHECKCODE");
		
		if ( ! array_key_exists($site, $this->conf['sites'])) {
			return $this->_return_error('E003', '無串接此遊戲');
		}
		$this->v['GID'] = $this->conf['sites'][$site]["trade_gid"];		
		
		//$TIME = date("YmdHis", time()); //------- test		
		//$CHECKCODE = substr(md5($this->conf["transfer_key"].$this->v['GID'].$this->v['TID'].$TIME), 7, 16); //------ test		

		if ($CHECKCODE <> substr(md5($this->conf["transfer_key"].$this->v['TID'].$TIME), 7, 16)) {
			return $this->_return_error('E002', '驗證碼錯誤');
		}					
		
		$TIME = date("YmdHis", time());
		$CHECKCODE = substr(md5($this->conf["transfer_key"].$this->v['GID'].$this->v['TID'].$TIME), 7, 16);
		$re = my_curl($this->conf["transfer_getdata_url"], array("GID"=>$this->v['GID'], "TID"=>$this->v['TID'], "TIME"=>$TIME, "CHECKCODE"=>$CHECKCODE));
		if ( ! empty($re)) 
		{		
			parse_str($re, $arr);			
			if ($arr['RCODE'] == 'S000') //請求資料成功 
			{ 
				if ($arr['CHECKCODE'] <> substr(md5($this->conf["transfer_key"].$arr['GID'].$arr['TID'].$arr['UID'].$arr['SERVER'].$arr['PID'].$arr['PNUM'].$arr['PNT'].$arr['TIME']), 7, 16)) {
					return $this->_return_error('E002', '驗證碼錯誤2');
				}				
				
				//fb($arr, arr);
				//$arr["SERVER"] = '00'; //----test
				//$arr["PID"] = 'F1'; //----test
				//$arr["PNT"] = '10'; //---test
		
				$this->v['UID'] = $arr["UID"];
		    	$account = strtolower($arr["UID"]).'@bahamut';
				$password = rand(100000, 999999);
				$email = '';
				$name = '';
				
				//登入，若不存在，則建立
				$re = $this->g_user->login($account, $password, $email, $name, $site);
				if (empty($re)) return $this->_return_error('E004', '帳號創建或登入失敗'); 								
				
				$this->load->model("games");	    		
	    		$server_row = $this->games->get_server_by_server_id("{$site}_{$arr["SERVER"]}");
	    		if (empty($server_row)) return $this->_return_error('E004', '伺服器不存在');
	    		
	    		$game_row = $this->games->get_game($server_row->game_id);
	    		if (empty($game_row)) return $this->_return_error('E004', '遊戲不存在'); 	

	    		if ($arr["PID"] == "F1") {
	    			$point = $arr["PNT"];
	    		}
	    		else {
	    			return $this->_return_error('E001', "無設定此產品({$arr["PID"] })"); //無設定; 	
	    		}	    			    		
	    		
	    		if (chk_trade_limit('bahamut_billing', '200000')) { //設定交易上限額，防洗點
	    			return $this->_return_error('E999', "本日已超過金流交易上限值");	
	    		}
	    		
	    		//檢查有無此巴哈訂單
	    		$bahamut_billing = $this->db->from("user_billing ub")->where("transaction_type", "bahamut_billing")->where("order", $arr["TID"])->get()->row();
	    		//fb($bahamut_billing, 'bahamut_billing');				
	    		if (empty($bahamut_billing)) 
	    		{
					//建單
					$billing_id = $this->g_wallet->produce_order($this->g_user->uid, "bahamut_billing", "2", $point, $server_row->server_id, $arr["TID"]);
					if (empty($billing_id)) return $this->_return_error('E005', '訂單新增失敗');  					
					
					$order = $this->g_wallet->get_order($billing_id);
					$this->v['order'] = $order;
					
					//轉入遊戲		
					$this->load->library("game_api/{$server_row->game_id}");
					$re = $this->{$server_row->game_id}->transfer($server_row, $order, $game_row->exchange_rate);	 
					if ($re === "1") {
						$this->g_wallet->complete_order($order);
						return true;
					}
					else if ($re === "-1") {
						$this->g_wallet->cancel_timeout_order($order);		
						return $this->_return_error('E999', "遊戲端伺服器無回應");
					}
					else {
						$error_message = "儲值失敗({$this->{$server_row->game_id}->error_message})";
						$this->g_wallet->cancel_order($order, $error_message);	
						return $this->_return_error('E999', $error_message);
					}
	    		}
	    		else {
	    			$this->v['order'] = $bahamut_billing;

	    			if ($bahamut_billing->result == '1') {
	    				return true;
	    			}
	    			else return $this->_return_error('E999', $bahamut_billing->note);	
	    		}
			}
			else return $this->_return_error('E005', "{$arr['RCODE']}{$arr['RMSG']}"); //交易失敗	
		}
		else return $this->_return_error('E005', 'transfer_getdata_url 無回應'); //交易失敗
	}
	
	function _return_error($RCODE, $RMSG='') 
	{
		$this->v['RCODE'] = $RCODE;
		//$this->v['RCODE'] = 'E999';
		$this->v['RMSG'] = $RMSG;
		return false;
	}
	
	function server_list($game_id)
	{
		$this->db->from("servers")->where("game_id", $game_id)->order_by("id");
		
		if ($game_id=='yh' || $game_id=='bw') {}
		else $this->db->where("server_status","public");
		
		$query = $this->db->get();
		
		foreach($query->result() as $row) {
			echo strtr($row->server_id, array("{$game_id}_"=>"")).",".$row->name."\r\n";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */