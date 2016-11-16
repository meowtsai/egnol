<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile extends MY_Controller
{
	function index()
	{		
		$this->load->model("g_bulletins");		
		$this->load->model("g_pictures");		
        
		$this->_init_layout()
			->add_css_link(array('mobile/normalize','mobile/animate','mobile/lity','mobile/layout','mobile/owl.carousel','mobile/owl.theme'))
            ->set("news", $this->g_bulletins->get_list("vxz", "", 7, $this->input->get("record")))
            ->set("slider_news_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_news_mobile"))
            ->set("slider_about_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_about_mobile"))
            ->set("slider_video_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_video_mobile"))
            ->set("slider_heroes_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_heroes_mobile"))
            ->mobile_view("mobile");
	}
	
	function e01_register()
	{
		// 讀取活動資料
		//$event = $this->db->from("events")->where("id", 3)->get()->row();
		$email = $this->input->get_post("email");
		$mobile = $this->input->get_post("mobile");
		$earlylogin_serial = $this->input->get_post("earlylogin_serial");
		$combo_serial = $this->input->get_post("combo_serial");
		$share_code = $this->input->get_post("share_code");
		$receive_code = $this->input->get_post("receive_code");
		
		if (!empty($receive_code)) {
			$receive_code_used = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->where("receive_code", $receive_code)->get()->row();
			
			if (empty($receive_code_used)) {
				$receive_code = "此情緣合擊序號已領取";
			}
		}
		
		$this->_init_layout()
			->add_css_link(array('mobile_e01/default','mobile_e01/style','mobile_e01/reset','mobile_e01/colorbox','mobile_e01/animate'))
            ->set("email", $email)
            ->set("mobile", $mobile)
            ->set("share_code", ($share_code)?$share_code:"")
            ->set("receive_code", ($receive_code)?$receive_code:"")
            ->set("earlylogin_serial", ($earlylogin_serial)?$earlylogin_serial:"")
            ->set("combo_serial", ($combo_serial)?$combo_serial:"")
			->api_view();
	}
	
	function e01_register_json()
	{
		// 讀取活動資料
		header('content-type:text/html; charset=utf-8');
		
		//$event = $this->db->from("events")->where("id", 3)->get()->row();
		$email = $this->input->post("email");
		$mobile = $this->input->post("mobile");
		$receive_code = $this->input->get_post("receive_code");
		$share_success = 0;
		
		if (!empty($receive_code)) {
			$receive_code_used = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->where("receive_code", $receive_code)->get()->row();
			
			if (empty($receive_code_used)) {
				die(json_failure('此情緣合擊序號已領取'));
			} else {
				$share_success = 1;
			}
		}
		
		if (!empty($email) && !empty($mobile)) {
			$check_code_used = $this->db->from("event_serial")->where("event_id", 8)->where("email", $email)->where("mobile", $mobile)->get()->row();

			if (empty($check_code_used)) {
				$check_used = $this->db->from("event_serial")->where("event_id", 8)->where("email", $email)->or_where("mobile", $mobile)->get()->row();
				
				if (empty($check_used)) {
					$earlylogin_code = $this->db->from("event_serial")->where("event_id", 8)->where("email", NULL)->where("mobile", NULL)->get()->row();
					
					$earlylogin_serial = $earlylogin_code->serial;
					
					$earlylogin_data = array(
					   'email' => $email,
					   'mobile' => $mobile
					);

					$this->db->where('id', $earlylogin_code->id);
					$this->db->update('event_serial', $earlylogin_data); 
					
					
					if ($share_success) {
						$combo_code1 = $this->db->from("event_serial")->where("event_id", 9)->where("share_code", $receive_code)->get()->row();
						
						$combo_data1 = array(
						   'status' => 1
						);
						
						$this->db->where('id', $combo_code1->id);
						$this->db->update('event_serial', $combo_data1); 

						$combo_code2 = $this->db->from("event_serial")->where("event_id", 9)->where("receive_code", $receive_code)->get()->row();
						
						$combo_serial = $combo_code2->serial;
						
						$combo_data2 = array(
						   'status' => 1,
						   'share_code' => md5($email.$mobile.'9')
						);
						
						$this->db->where('id', $combo_code2->id);
						$this->db->update('event_serial', $combo_data2); 
					} else {
						$combo_code1 = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->get()->row();
						
						$combo_data1 = array(
						   'email' => $email,
						   'mobile' => $mobile,
						   'status' => 0,
						   'share_code' => md5($email.$mobile.'9')
						);
						
						$this->db->where('id', $combo_code1->id);
						$this->db->update('event_serial', $combo_data1); 
					}

					$combo_code3 = $this->db->from("event_serial")->where("event_id", 9)->where("email", NULL)->where("mobile", NULL)->get()->row();

					$share_code = md5($email.$mobile.'9');
					
					$combo_data3 = array(
					   'status' => 0,
					   'receive_code' => $share_code
					);

					$this->db->where('id', $combo_code3->id);
					$this->db->update('event_serial', $combo_data3); 
				} else {
					die(json_failure('用戶資訊已被使用'));
				}
			} else {
				$earlylogin_serial = $check_code_used->serial;
				
				$combo_code = $this->db->from("event_serial")->where("event_id", 9)->where("email", $email)->where("mobile", $mobile)->get()->row();
				
				if(!empty($combo_code) && $combo_code->status == 1) {
					$combo_serial = $combo_code->serial;
				} else {
					$combo_serial = "";
				}
				
				$share_code = $combo_code->share_code;
			}
		}
		
		$earlylogin_serial=($earlylogin_serial)?$earlylogin_serial:"";
		$combo_serial=($combo_serial)?$combo_serial:"";
		$share_code=($share_code)?$share_code:"";
		
		die(json_message(array("message"=>"成功", "email"=>$email, "mobile"=>$mobile, "earlylogin_serial"=>$earlylogin_serial, "combo_serial"=>$combo_serial, "share_code"=>$share_code), true));
	}
	
	function e02_r2g_transfer()
	{
		$this->_init_layout()
			->set_meta("title", "絕代雙驕玩家獨享元寶活動")
			->add_css_link(array('mobile/e02/style','mobile/e02/reset'))
			->api_view();
	}
	
	function e02_content()
	{
		if ($this->g_user->uid) {
			
			$check_transfered = $this->db->from("r2g_transferlist")->where("uid", $this->g_user->uid)->get()->row();
			
				$this->_init_layout()
					->set_meta("title", "絕代雙驕玩家獨享元寶活動")
					->mobile_view("mobile/e02_transferred");
			if ($check_transfered) {
				$this->_init_layout()
					->set_meta("title", "絕代雙驕玩家獨享元寶活動")
					->mobile_view("mobile/e02_transferred");
			} elseif ($this->input->post("character_id")) {
				
				$billing_str = "SELECT SUM(amount) AS sum
							FROM user_billing 
							WHERE
								uid='{$this->g_user->uid}'
								AND transaction_type='top_up_account' 
								AND result = 1 
								AND server_id LIKE 'r2g%' 
								GROUP BY uid";
				$billing_sum = $this->db->query($billing_str)->row();
				
				$amount = (isset($billing_sum->sum))?$billing_sum->sum:0;
				$transfer_amount = ceil($amount*0.4);
				
				$transfer_data = array(
					"uid" => $this->g_user->uid,
					"character_id" => $this->input->post("character_id"),
					"amount" => $amount,
					"transfer_amount" => $transfer_amount
				);

				$this->db->insert("r2g_transferlist", $transfer_data);
				
				die(json_message(array("message"=>"成功"), true));
			} else {

				$characters_str = "SELECT c.id, c.name AS character_name, s.server_id, s.name AS server_name
							FROM characters c 
							JOIN servers s ON c.server_id=s.server_id
							WHERE
								s.game_id='vxz'
								AND c.uid='{$this->g_user->uid}'
								AND c.server_id not in ('vxz-test01', 'vxz-server0')";
				$characters = $this->db->query($characters_str);

				$billing_str = "SELECT SUM(amount) AS sum
							FROM user_billing 
							WHERE
								uid='{$this->g_user->uid}'
								AND transaction_type='top_up_account' 
								AND result = 1 
								AND server_id LIKE 'r2g%' 
								GROUP BY uid";
				$billing_sum = $this->db->query($billing_str)->row();

				$this->_init_layout()
					->set_meta("title", "絕代雙驕玩家獨享元寶活動")
					->set("account", ($this->g_user->email)?$this->g_user->email:$this->g_user->mobile)
					->set("characters", $characters)
					->set("billing_sum", (isset($billing_sum->sum))?$billing_sum->sum:0)
					->add_css_link(array('event/reset','event/colorbox'))
					->add_js_include(array('jquery-1.12.3.min', 'event/jquery.colorbox-min', 'jquery.validate.min', 'jquery.metadata', 'jquery.form', 'event/default', 'event/validate2'))
					->mobile_view("mobile/e02_choose");
			}
		} else {

			$this->_init_layout()
				->set_meta("title", "絕代雙驕玩家獨享元寶活動")
				->add_css_link(array('event/reset','event/colorbox'))
				->add_js_include(array('jquery-1.12.3.min', 'event/jquery.colorbox-min', 'jquery.validate.min', 'jquery.metadata', 'jquery.form', 'event/default', 'event/login'))
				->mobile_view();
		}
	}
	
	function e02_billinglist() {
		
		if ($this->g_user->uid) {
			
			$billing_str = "SELECT u.*, c.name
						FROM user_billing u
						LEFT JOIN characters c ON u.character_id=c.id
						WHERE
							u.uid='{$this->g_user->uid}'
							AND u.transaction_type='top_up_account' 
							AND u.result = 1 
							AND u.server_id LIKE 'r2g%' 
							GROUP BY u.uid";
			$billing_list = $this->db->query($billing_str);
			
			$this->_init_layout()
				->set_meta("title", "絕代雙驕玩家獨享元寶活動")
				->set("billing_list", $billing_list)
				->mobile_view();
		} else {
			
			redirect('/mobile/e02_content', 'refresh');
		}
	}
	
	function login_json()
	{
		header('content-type:text/html; charset=utf-8');

		$site = $this->input->get_post("site");

		$_SESSION['site'] = $site;

		// 檢查 e-mail or mobile
		$account = $this->input->post("account");
		if(empty($account))
		{
			die(json_failure('電子郵件或行動電話未填寫'));
		}

		$pwd = $this->input->post("pwd");
		if (empty($pwd))
		{
			die(json_failure('密碼尚未填寫'));
		}

		$email = '';
		$mobile = '';
		if(filter_var($account, FILTER_VALIDATE_EMAIL))
		{
			$email = $account;
		}
		else
		{
			$mobile = $account;
		}

		if ( $this->g_user->verify_account($email, $mobile, $pwd) === true )
		{
			die(json_message(array("message"=>"成功", "site"=>$site), true));
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}
	
	// 登出
	function logout()
	{
		$site = $this->input->get_post("site");

		$this->g_user->logout();

		header('Content-type:text/html; Charset=UTF-8');
		echo "<script type='text/javascript'>alert('成功登出系統'); location.href='/mobile/e02_content?site={$site}';</script>";
	}
}
