<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funapp extends MY_Controller {
	
	var $funapp_conf;
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->config("g_funapp");
		$this->load->model("funapps");
		$this->funapp_conf = $this->config->item("funapp");
	}
	
	function _init_funapp_layout()
	{
		return $this->_init_layout()
			->set_breadcrumb(array("儲值"=>"payment", "天天賺 購點"=>""))
			->set("subtitle", "天天賺購點");
	}
	
	function order() //實體卡
	{
		$this->_require_login();
        
        $payment_type = $this->input->post("pay_type");
        //$item_code = $this->input->post("prod_id");
        //$product_name = $this->input->post("prod_id");
        $currency = $this->input->post("currency");
        $amount = $this->input->post("amount");
		
		$_SESSION['payment_game']		= $this->input->post('game');
		$_SESSION['payment_server']		= $this->input->post('server');
		$_SESSION['payment_character']	= $this->input->post('character');
		$_SESSION['payment_api_call']   = $this->input->post('api_call');
		$_SESSION['payment_partner_order_id'] = $this->input->post('partner_order_id');
		
		if ($this->funapp_conf["iap_id"][(string)$amount]) {
			$iap_id = $this->funapp_conf["iap_id"][(string)$amount];
		} else {
			go_payment_result(0, 0, 0, "品項錯誤");
		} 
		
		$data = array(
			'uid'			=> $this->g_user->uid,
			'status' 		=> '0',
			'payment_type'	=> '',
			'iap_id'		=> $iap_id
		);
		if ($this->input->post("server")) $data["server_id"] = $this->input->post("server");	
        
		$funapp_billing_id = $this->funapps->insert_billing($data);
		
		$this->load->library("g_wallet");
		$this->g_wallet->produce_funapp_order($this->g_user->uid, $funapp_billing_id, "funapp_billing", $amount, $_SESSION['payment_character'], $_SESSION['payment_partner_order_id'], "0", $_SESSION['payment_server']);
		
		$data = array(
			'contentID'          => $iap_id,
			'extraInfo'          => urlencode($funapp_billing_id),
			'notifyUrl'          => base_url()."funapp/return_url/{$funapp_billing_id}",
			'successRedirectUrl' => base_url()."funapp/payment_result/{$amount}",
			'backUrl'            => "",
			'reSelectUrl'        => ($_SESSION['payment_api_call'] == 'true')?"":base_url()."payment/result",
		);
        
		log_message("error", "funapp order:".implode("", $data));
        
        //$urlencoded = urlencode(implode("", $data).$this->funapp_conf["hash_key"]);
		//$data['Hash'] = hash('sha256', strval($urlencoded));
		
		$auth_url = $this->funapp_conf["auth_url"]."?".http_build_query($data);
        
		header("location:".$auth_url);
		
		
		//$cnt = 0;
		//while ($cnt++ < 5) {
		//	$result = json_decode(my_curl($auth_url));
		//	if ( ! empty($result)) break;
		//	sleep(1);
		//} 
	}
	
	function return_url($funapp_billing_id) //web-side Ingame callback 
	{
		if (empty($funapp_billing_id)) die("未傳遞參數");
		$post = $this->input->post();
		if (empty($post)) die("未傳遞參數");
		
		//檢查訂單
		$funapp_billing = $this->funapps->get_billing_row(array("id" => $funapp_billing_id));
		if (empty($funapp_billing)) {
			die("funapp訂單不存在");
		}
		if ($funapp_billing->status <> '0') {
			die("此筆funapp訂單已結案");
		}
        
		if (isset($post['errorMsg'])) {
			die($post['errorMsg']);
		}
		
		$_SESSION['payment_type']    = "funapp";
		$_SESSION['payment_channel'] = "funapp";
		$_SESSION['cuid']	         = 'TWD';
		$_SESSION['oid']	         = "funapp";
        
        $data = array(
            'result'	   => '1',
            'trans_no'     => $post['transNo'],
            'amount'	   => $post['paidPrice'],
			'payment_type' => $post['payType'],
            'status' 	   => '1',
            'note' 		   => $error_message,
        );
		
        $this->funapps->update_billing($data, array("id" => $funapp_billing_id));
        
        $this->confirm($funapp_billing);
	}
	
    function confirm($funapp_billing, $is_get_order=0) {
	 
        $confirm_url = $this->funapp_conf["confirm_url"]
			."?cpId=".$this->funapp_conf["cpId"]
			."&hash=".hash("sha256", $this->funapp_conf["secretKey"].$this->funapp_conf["cpId"].$funapp_billing->id)
			."&extraInfo=".$funapp_billing->id;
        
        $cnt = 0;
        while ($cnt++ < 3) {
            $confirm_result = json_decode(my_curl($confirm_url));
            if ( ! empty($confirm_result)) break;
            sleep(1);
        } 	

        if (empty($confirm_result)) {
            if ($is_get_order) return 0;
            die("funapp伺服器無回應");	
        }
        
        if ($confirm_result->returnCode === "000" && $confirm_result->body["0"]->transType == "2") {
		
			$_SESSION['payment_channel'] = $confirm_result->body["0"]->payType;
			$_SESSION['oid']	         = $confirm_result->body["0"]->transNo;
			
            $this->funapps->update_billing(
                array(
                    "result"       => 1, 
                    "status"       => 2, 
					'trans_no'     => $confirm_result->body["0"]->transNo,
					'payment_type' => $confirm_result->body["0"]->payType,
                    "amount"       => $confirm_result->body["0"]->paidPrice,
				), 
                array("id" => $funapp_billing->id));

            $user_billing = $this->db->where("funapp_billing_id", $funapp_billing->id)->get("user_billing")->row();
				
			$this->load->library("g_wallet");
			$this->g_wallet->complete_order($user_billing);
				
            $this->load->library("game");
            $this->game->payment_transfer($funapp_billing->uid, $funapp_billing->server_id, $confirm_result->body["0"]->paidPrice, $user_billing->partner_order_id, $user_billing->character_id, "", "", "");
					
            if ($is_get_order) return 1;
            go_payment_result(1, 1, $confirm_result->body["0"]->paidPrice, $error_message);
        } else {
            if ($is_get_order) return 0;
            go_payment_result(0, 0, 0, $confirm_result->ReturnMsg);
        }
	}
	
	function ingame_result()
	{
		$this->_init_funapp_layout()
			->set("status", $this->input->get("status"))
			->set("message", urldecode($this->input->get("message")))
			->render("", "inner");	
	}
	
	//funapp billing 自動補單
	function reconfirm()
	{
        $confirm_url = $this->funapp_conf["confirm_url"]
			."?cpId=".$this->funapp_conf["cpId"]
			."&hash=".hash("sha256", $this->funapp_conf["secretKey"].$this->funapp_conf["cpId"].date("Y/m/d"))
			."&date=".date("Y/m/d")
			."&from=".(date("H")+1)
			."&from=".(date("H")>=1)?(date("H")-1):"0"
			."&transType=2";
        
        $cnt = 0;
        while ($cnt++ < 3) {
            $confirm_result = json_decode(my_curl($confirm_url));
            if ( ! empty($confirm_result)) break;
            sleep(1);
        } 	

        if (empty($confirm_result)) {
            if ($is_get_order) return 0;
            die("funapp伺服器無回應");	
        }
		
        if ($confirm_result->returnCode === "000") {
			
			foreach ($confirm_result->body as $row) {
				$funapp_billing = $this->funapps->get_billing_row(array("id" => $row->extraInfo));
				
				if ($funapp_billing->result==1 && $funapp_billing->status==2) {
					continue;
				} else {
					$this->funapps->update_billing(
						array(
							"result" => 1, 
							"status" => 2, 
							"amount" => $row->paidPrice
						), 
						array("id" => $funapp_billing->id));

					$user_billing = $this->db->where("funapp_billing_id", $funapp_billing->id)->get("user_billing")->row();

					$this->load->library("g_wallet");
					$this->g_wallet->complete_order($user_billing);

					$this->load->library("game");
					$this->game->payment_transfer($funapp_billing->uid, $funapp_billing->server_id, $row->paidPrice, $user_billing->partner_order_id, $user_billing->character_id, "", "", "");
				}
			}
        } else {
			die($confirm_result->returnCode.$confirm_result->returnDesc);
		}
	}
	
	function payment_result($amount=0)
	{
		$post = $this->input->post();
		
        go_payment_result(1, 1, $amount, '');
	}
}

function go_payment_result($status, $transfer_status, $price, $message, $args='') 
{
	// 若沒有設定 site, 表示是系統 check_order 處理或有問題, 不用顯示結果
	if(!isset($_SESSION['site']))
	   exit();
	
	$site = $_SESSION['site'];
	$api_call = $_SESSION['payment_api_call'];

	$_SESSION['payment_api_call'] = '';
	unset($_SESSION['payment_api_call']);

	if($api_call == 'true')
		header('location: '.g_conf('url', 'api')."api2/ui_payment_result?s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	else
		header('location: '.g_conf('url', 'longe')."payment/result?site={$site}&s={$status}&ts={$transfer_status}&p={$price}&m=".urlencode($message)."&".$args);
	exit();
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */