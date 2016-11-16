<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//
// 活動用頁面
//
class Event extends MY_Controller
{
	// 共用功能
	// 檢查電話號碼
	function _check_mobile_region($mobile)
	{
		// 886 台
		if(preg_match("/^8869\d{8}$/", $mobile) || preg_match("/^09\d{8}$/", $mobile))
			return 'tw';

		// 852 港
		if(preg_match("/^852[5-9]\d{7}$/", $mobile))
			return 'hk';

		// 853 澳
		if(preg_match("/^8536\d{7}$/", $mobile))
			return 'mo';

		return 'undefined';
	}

	// --------------------------------------------------------------------------------------------------------
	// 預註冊活動
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
			->add_css_link(array('event/default','event/style','event/reset','event/colorbox','event/animate'))
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

	// 取得序號
	function _e01_new_sn($uid)
	{
/*        $now = new DateTime();
        $ts = $now->format('Y-m-d H:i:s');
		$sqlstr = "UPDATE event_serial SET status=1,uid={$uid},create_time='{$ts}' WHERE id = (SELECT id from (SELECT id FROM event_serial WHERE event_id=3 and status=0 ORDER BY id LIMIT 1) as eid)";

		$res = $this->db->query($sqlstr);

		if($res != FALSE && $this->db->affected_rows() > 0)
		{
			return $this->_e01_get_sn($uid);
		}
*/
		return false;
	}

	function _e01_get_sn($uid)
	{
		$query = $this->db->where("event_id", 3)->where("uid", $uid)->get("event_serial");
		if(empty($query) || $query->num_rows() > 0)
		{
			return $query->row()->serial;
		}

		return false;
	}
	
	function _e01_read_counter()
	{
		$file_path = $_SERVER['DOCUMENT_ROOT'] . "/p/evt/01/data/counter.txt";
		$cnt = file_get_contents($file_path);
		if($cnt === false)
		{
			file_put_contents($file_path, LOCK_EX);
			$cnt = "0";
		}
		
		return (int)$cnt;
	}
	
	function _e01_inc_counter($inc)
	{
		$file_path = $_SERVER['DOCUMENT_ROOT'] . "/p/evt/01/data/counter.txt";
		$cnt = file_get_contents($file_path);
		if($cnt === false)
		{
			$cnt = "0";
		}
		$cnt = strval((int)$cnt + $inc);
		file_put_contents($file_path, $cnt, LOCK_EX);
	}
	
	function e01_result()
	{
		header('content-type:text/html; charset=utf-8');

		// 檢查是否已截止
		$end_time = new DateTime($event->end_time);
		$now_time = new DateTime(date('Y-m-d H:i:s'));
		if($end_time < $now_time)
		{
			die(json_message(array("message"=>"預約登入活動時間已結束囉！")));
		}
		
		$account = $this->input->post('account');
		$pwd = $this->input->post("pwd");

		if(empty($account))
		{
			die(json_failure('E-Mail或行動電話未填寫'));
		}
		else if (empty($pwd) )
		{
			die(json_failure("請輸入密碼"));
		}

		$email = '';
		$mobile = '';
		if(filter_var($account, FILTER_VALIDATE_EMAIL))
		{
			$email = $account;
		}
		else if($this->_check_mobile_region($account) != 'undefined')
		{
			$mobile = $account;
		}
		else
		{
			die(json_failure('E-Mail或行動電話格式錯誤'));
		}

		// 先檢查是否已有帳號
		$boolResult = $this->g_user->verify_account($email, $mobile, $pwd);
		if ($boolResult==true)
		{
			$sn = $this->_e01_get_sn($this->g_user->uid);
			if($sn != false)
			{
				die(json_message(array("message"=>"成功", "action"=>"check_reward", "sn"=>$sn, "new"=>"0"), true));
			}
			else
			{
				// 檢查是否已截止
				$end_time = new DateTime($event->end_time);
				$now_time = new DateTime(date('Y-m-d H:i:s'));
				if($end_time < $now_time)
				{
					die(json_message(array("message"=>"您的帳號沒有領取活動序號。")));
				}
				
                // 若已建立帳號但尚未領取序號, 可直接獲得
                $sn = $this->_e01_new_sn($this->g_user->uid);
                if($sn != false)
                {
					$this->_e01_inc_counter(1);
                    die(json_message(array("message"=>"成功", "action"=>"check_reward", "sn"=>$sn, "new"=>"1"), true));
                }
                else
                {
                    die(json_message(array("message"=>"因活動反應熱烈，所有序號已發放完畢！")));
                }
			}
		}
		else
		{
			// 檢查是否已截止
			$end_time = new DateTime($event->end_time);
			$now_time = new DateTime(date('Y-m-d H:i:s'));
			if($end_time < $now_time)
			{
				die(json_message(array("message"=>"帳號不存在或密碼錯誤！")));
			}
			
			// 先檢查是否有問題
			if(!IN_OFFICE)
			{
				$sqlstr = "SELECT count(*) as login_count FROM log_logins WHERE ip='" . $_SERVER["REMOTE_ADDR"] . "' AND create_time>'2016-02-25'";
				$res = $this->db->query($sqlstr);

				if($res->row()->login_count > 16)
				{
					die(json_failure("無法獲得。"));
				}
			}

			$boolResult = $this->g_user->create_account($email, $mobile, $pwd);
			if ($boolResult==true)
			{
				$this->g_user->verify_account($email, $mobile, $pwd);
				$sn = $this->_e01_new_sn($this->g_user->uid);
				if($sn != false)
				{
					$this->_e01_inc_counter(1);
					die(json_message(array("message"=>"成功", "action"=>"register", "sn"=>$sn, "new"=>"1"), true));
				}
				else
				{
					die(json_message(array("message"=>"因活動反應熱烈，所有序號已發放完畢！")));
				}
			}
			else
			{
				die(json_failure("帳號不存在或密碼錯誤！"));
			}
		}
	}

	function e01_check_reward()
	{
		header('content-type:text/html; charset=utf-8');

		$account = $this->input->post('account');
		$pwd = $this->input->post("pwd");

		if(empty($account))
		{
			die(json_failure('電子郵件或行動電話未填寫'));
		}
		else if (empty($pwd) )
		{
			die(json_failure("請輸入密碼"));
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

		$boolResult = $this->g_user->verify_account($email, $mobile, $pwd);
		if ($boolResult==true)
		{
			$sn = $this->_e01_get_sn($this->g_user->uid);
			if($sn != false)
			{
				die(json_message(array("message"=>"成功", "action"=>"check_reward", "sn"=>$sn, "new"=>"0"), true));
			}
			else
			{
				// 檢查是否已截止
				$end_time = new DateTime($event->end_time);
				$now_time = new DateTime(date('Y-m-d H:i:s'));
				if($end_time < $now_time)
				{
					die(json_message(array("message"=>"您的帳號沒有領取活動序號。")));
				}
				
                // 若已建立帳號但尚未領取序號, 可直接獲得
                $sn = $this->_e01_new_sn($this->g_user->uid);
                if($sn != false)
                {
					$this->_e01_inc_counter(1);
                    die(json_message(array("message"=>"成功", "action"=>"check_reward", "sn"=>$sn, "new"=>"1"), true));
                }
                else
                {
                    die(json_message(array("message"=>"因活動反應熱烈，所有序號已發放完畢！")));
                }
			}
		}
		else
		{
			die(json_failure($this->g_user->error_message));
		}
	}

	// 取得目前計數
    function e01_get_count()
    {
		header('content-type:text/html; charset=utf-8');

        die(json_message(array("count" => $this->_e01_read_counter()), true));
    }
    
    function e01_icc()
    {
		if(!IN_OFFICE)
			die();
		
		$this->_e01_inc_counter((int)$this->input->post('i'));
    }
	
	function e02_r2g_transfer()
	{
		$this->_init_layout()
			->set_meta("title", "絕代雙驕玩家獨享元寶活動")
			->add_css_link(array('event/e02_style','event/e02_reset'))
			->api_view();
	}
	
	function e02_content()
	{
		if ($this->g_user->uid) {
			
			$check_transfered = $this->db->from("r2g_transferlist")->where("uid", $this->g_user->uid)->get()->row();
			
			if ($check_transfered) {
				$this->_init_layout()
					->set_meta("title", "絕代雙驕玩家獨享元寶活動")
					->mobile_view("event/e02_transferred");
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
				$transfer_amount = $amount*0.4;
				
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
								AND c.uid='{$this->g_user->uid}'";
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
					->set("uid", $this->g_user->uid)
					->set("characters", $characters)
					->set("billing_sum", (isset($billing_sum->sum))?$billing_sum->sum:0)
					->add_css_link(array('event/reset','event/colorbox'))
					->add_js_include(array('jquery-1.12.3.min', 'event/jquery.colorbox-min', 'jquery.validate.min', 'jquery.metadata', 'jquery.form', 'event/default', 'event/validate2'))
					->mobile_view("event/e02_choose");
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
						JOIN characters c ON u.character_id=c.id
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
			
			redirect('/event/e02_content', 'refresh');
		}
	}
}
