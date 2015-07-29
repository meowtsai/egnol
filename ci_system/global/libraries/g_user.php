<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 會員認証用類別
 *
 * @author      eddiehan@molibee.com
 * @package     authUser
*/

class G_User
{
	var $CI;
	var $uid;
	var $euid;
	var $email;
	var $mobile;
	var $password;
	var $external_id;
	var $remoteAddr;
	var $userAgent;
	var $token;
	
	var $error_mssage='錯誤';

	function __construct() 
	{
		$this->CI =& get_instance();
			
		if ( ! empty($_SESSION['user_id']))
		{
			$this->uid = $_SESSION['user_id'];
			$this->euid = isset($_SESSION['euid']) ? $_SESSION['euid'] : '';
			$this->remoteAddr = $_SERVER['REMOTE_ADDR'];
			$this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			$this->email = $_SESSION['email'];
			$this->mobile = $_SESSION['mobile'];
			$this->external_id = $_SESSION['external_id'];
			$this->token = $_SESSION['token'];
		}
	}

	/**
	 *  登出
	 */
	function logout() 
	{				
		$_SESSION['user_id'] = '';
		$_SESSION['euid'] = '';		
		$_SESSION['email'] = '';
		$_SESSION['mobile'] = '';
		$_SESSION['external_id'] = '';
		$_SESSION['token'] = '';
		$_SERVER['REMOTE_ADDR'] = '';
		
		unset($_SESSION['user_id']);
		unset($_SESSION['euid']);
		unset($_SESSION['email']);
		unset($_SESSION['mobile']);
		unset($_SESSION['external_id']);
		unset($_SESSION['token']);
		
		//setcookie('PHPSESSID', '', time()-3600);
		//session_destroy();
		return true;
	}

	// 檢查玩家是否已登入, 若未登入則導向登入畫面
	function require_login($site='', $redirect_url='')
	{
		if (empty($this->uid))
		{
			if (empty($redirect_url))
			{
				$redirect_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			}
			header("Content-type: text/html; charset=utf-8");
			echo "<script type='text/javascript'>
					alert('請先進行登入');
					top.location.href='http://{$_SERVER['HTTP_HOST']}/member/login?site={$site}&redirect_url=".urlencode($redirect_url)."';
					</script>";
			exit();
		}
		else
		{
			return true;
		}
	}

	// 檢查玩家是否已登入
	function is_login()
	{
		return !empty($this->uid);
	}

	function verify_account($email, $mobile, $password='', $external_id='')
	{
		if(empty($email) && empty($mobile) && empty($external_id))
		{
             return $this->_return_error("帳號不存在");
		}

		$this->email = strtolower(trim($email));
		$this->mobile = trim($mobile);

		$user_row = $this->query_account($email, $mobile, $external_id);
		if (!empty($user_row))
		{
			if(!empty($password))
			{
				if($user_row->password != md5($password))
				{
					return $this->_return_error("帳號不存在或密碼錯誤");
				}
			}

			if ($user_row->is_banned != 0 && !IN_OFFICE)
			{
				return $this->_return_error("停權");
			}

			$this->set_user($user_row->uid, $user_row->email, $user_row->mobile, $user_row->external_id);
			return true;
		} 
		else
		{
			return $this->_return_error("帳號不存在或密碼錯誤");	
		}
	}
	
	function set_user($uid, $email, $mobile, $external_id='')
	{		
		//登入log
		/*
		$this->CI->db
			->where("uid", $row->uid)
			->where("is_recent", "1")
			->update("log_logins", array("is_recent" => "0"));
			*/
		
		/*if ($this->CI->input->get("ad")) {
			$ad = $this->CI->input->get("ad");
		} else $ad = empty($_SESSION['ad']) ? '' : $_SESSION['ad'];
		*/
		
		$site = $this->CI->input->get('site') ? $this->CI->input->get('site') : (empty($_SESSION['site']) ? 'long_e' : $_SESSION['site']);	
		$ad = $this->CI->input->get('ad') ? $this->CI->input->get('ad') : (empty($_SESSION['ad']) ? '' : $_SESSION['ad']);
		$account = '';

		if(!empty($email))
		{
			$account = $email;
		}
  		else if(!empty($mobile))
		{
			$account = $mobile;
		}
  		else if(!empty($external_id))
		{
			$account = $external_id;
		}

		$data = array(
			'uid' => $uid,
			'account' => $account,
			'ip' => $_SERVER["REMOTE_ADDR"],
			'create_time' => now(),
			'is_recent' => '1',
			'ad' => $ad,
			'site' => $site,
		);
		if ( ! empty($_SESSION['log_imei'])) $data['imei'] = $_SESSION['log_imei'];
		if ( ! empty($_SESSION['log_android_id'])) $data['android_id'] = $_SESSION['log_android_id'];

		$this->CI->db->insert("log_logins", $data);	

		$this->uid = $uid;
		$this->euid = $this->encode($uid);
		$this->email = $email;
		$this->mobile = $mobile;
		$this->external_id = $external_id;
		$this->token = $this->generate_token();
		
		$_SESSION['user_id'] = $this->uid;
		$_SESSION['euid'] = $this->euid;
		$_SESSION['email'] = $this->email;
		$_SESSION['mobile'] = $this->mobile;
		$_SESSION['external_id'] = $this->external_id;
		$_SESSION['token'] = $this->token;
	}
	
	function get_user_data($uid='') 
	{
		if (empty($uid)) $uid = $this->uid;
		return $this->CI->db->from("users")->where("uid", $uid)->get()->row();
	}

	// 建立新帳號
	function create_account($email, $mobile, $password, $external_id='')
	{
		if(!empty($email) || !empty($mobile))
		{
			$email = strtolower(trim($email));
			$mobile = trim($mobile);

			if(!empty($email))
			{
				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
		            return $this->_return_error('電子信箱格式錯誤');
				}
			}

			if (strlen($password) < 6)
			{
				return $this->_return_error('密碼不得少於六碼');
			}
		}
		else
		{
			if(empty($external_id))
			{
	            return $this->_return_error('第三方登入帳號錯誤');
			}
		}

		if ($this->check_account_exist($email, $mobile, $external_id))
		{
			return $this->_return_error('帳號已經存在');
		}
		else
		{
			$data = array(
				'password'	=> md5(trim($password)),
				'create_time' => date("YmdHis"),
				'is_approved'	=> 0
			);

        	// 有指定 email 或 mobile 才寫入, 沒指定就會預設為 NULL
			// 否則若寫入空字串會被當成合法的唯一值而造成誤判
			if(!empty($email)) $data['email'] = $email;
			if(!empty($mobile)) $data['mobile'] = $mobile;
			if(!empty($external_id)) $data['external_id'] = $external_id;

			$this->CI->db->insert("users", $data);
			$uid = $this->CI->db->insert_id();
			if ( ! $uid)
			{
				return $this->_return_error('資料庫新增錯誤');
			}
			else
			{
			    $user_info_data = array(
				    'uid'	=> $uid
			    );
				$this->CI->db->insert("user_info", $user_info_data);
			}
			return true;
		}
	}

	// 讀取帳號資料
	function query_account($email, $mobile, $external_id='')
	{
        $query = null;
		if(!empty($email))
		{
			// 以 e-mail 讀取帳號
			$query = $this->CI->db->from("users")->where("email", $email)->get();
		}
		else if(!empty($mobile))
		{
			// 若沒有則以行動電話讀取帳號
			$query = $this->CI->db->from("users")->where("mobile", $mobile)->get();
		}
		else if(!empty($external_id))
		{
			// 若沒有則以第三方登入 id 讀取帳號
			$query = $this->CI->db->from("users")->where("external_id", $external_id)->get();
		}

		if ($query != null && $query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return null;
	}

	// 綁定帳號
	function bind_account($uid, $email, $mobile, $password)
	{
		$data = array(
			'password' => md5(trim($password))
		);

      	// 有指定 email 或 mobile 才寫入, 沒指定就會預設為 NULL
		// 否則若寫入空字串會被當成合法的唯一值而造成誤判
		if(!empty($email)) $data['email'] = $email;
		if(!empty($mobile)) $data['mobile'] = $mobile;

		$this->CI->db
			->where("uid", $uid)
			->update("users", $data);

		return true;
	}

	// 檢查帳號是否已存在
	function check_account_exist($email, $mobile, $external_id='')
	{
		if(!empty($email))
		{
			// 檢查 email
			$cnt = $this->CI->db->from("users")->where("email", $email)->count_all_results();
			if($cnt > 0)
				return true;
		}
		if(!empty($mobile))
		{
			// 檢查行動電話
			$cnt = $this->CI->db->from("users")->where("mobile", $mobile)->count_all_results();
			if($cnt > 0)
				return true;
		}
		if(!empty($external_id))
		{
			// 檢查第三方登入 id
			$cnt = $this->CI->db->from("users")->where("external_id", $external_id)->count_all_results();
			if($cnt > 0)
				return true;
		}

		return false;
	}

	// 檢查目前使用者是否為從第三方登入建立的帳號
	function is_from_3rd_party()
	{
		return !empty($this->external_id);
	}

/*
	function check_account_channel($type='')
	{
		$site = $this->CI->input->get("site");
		$account = $this->CI->input->get("account");
		$uid = $this->CI->input->get("uid");
		$euid = $this->CI->input->get("euid");
		
		if (empty($site)) $site = 'long_e';
		
		$back_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$long_e_login_page = site_url("member/login?site={$site}&redirect_url=".urlencode($back_url));

		if ($this->is_login())
		{ //已登入帶登入資訊
			$account = $this->account;	
		}
		else {	//使用get資訊
			if ($euid) $uid = $this->decode($euid);		
			if ($uid) $account = $this->CI->db->from("users")->where("uid", $uid)->get()->row()->account;
		}

		if ($this->is_login()) {
			//if (($uid && $uid <> $this->uid) || ($account && $account <> $this->account)) {
			//	header("location: {$long_e_login_page}"); 
			//	exit();
			//}
			return true;
		}
		else {
						
			if (empty($account)) {
				header("location: {$long_e_login_page}"); 
				exit();
			}
			else {
				
				//暫時全導登入頁
				header("location: {$long_e_login_page}"); 
				exit();
				//end
					
				if ($this->check_extra_account($account)) { //外站帳號
					$spt = explode("@", $account);					
					$extra_login_page = site_url("gate/login/{$site}?channel={$spt[1]}&redirect_url=".urlencode($back_url));
					header("location: {$extra_login_page}");
					exit();
				}
				else {
					header("location: {$long_e_login_page}");
					exit();
				}
			}
			return true;
		}		
	}
*/
	function loadDB()
	{		
		return $this->CI->load->database("long_e", true);
	}
	
	function encode($id) 
	{
		$digit = $id%10; //個位數
		$factor = $this->_mapping_num($digit)%7+1;
		$m_factor = $this->_mapping_num($factor);	
		$code = ($id * $factor).$m_factor;
		$m_code = $this->_mapping_num($code);
		return intval($m_code);
	}
	
	function decode($m_code) 
	{
		$code = $this->_mapping_num($m_code, true);		
		$m_factor = substr($code, -1, 1);
		$factor = $this->_mapping_num($m_factor, true);
		if ($factor == 0) return 0;
		
		$id = substr($code, 0, strlen($code)-1) / $factor;
		if (is_int($id)) return $id;
		else return 0;
	}
	
	//數字轉換
	function _mapping_num($num, $reverse=false) {
		$num = intval($num);
		$mapping_table = array("0", "5", "4", "1", "7", "9", "2", "3", "6", "8");
		if ($reverse) {
			$tmp = array();
			foreach($mapping_table as $k => $v) $tmp[$v] = $k;
			$mapping_table = $tmp;
		}		
		$result = '';
		for ($i=0; $i<=strlen($num)-1; $i++) {
			$result .= $mapping_table[substr($num, $i, 1)];
		}
		return $result;
	}	
	
	function display_account($show_notice=true)
	{
	/*
		$this->CI->load->config('../../../default/application/config/api');
		$channel_api = $this->CI->config->item("channel_api");
		$display = array();
		if (is_array($channel_api)) {
			foreach($channel_api as $channel => $arr) {
				$display[$channel] = $arr["name"];
			}		
		}
		$re = $this->account;
		if (strpos($re, "@imei") && $this->mobile){
			$re = $this->mobile;
		}
		elseif ($this->check_extra_account($this->account)) {
			$tmp = explode("@", $this->account);
			//if ($tmp[1] == 'igame') $tmp[1] = 'igamer'; //前人設定錯導致
			if (array_key_exists($tmp[1], $display)) {
				$re = $display[$tmp[1]]." 帳號";
			}		
		}		
		return $re.( $show_notice ? " ".$this->display_notice() : "");
		*/
		return '';
	}
	
	function get_channel()
	{
		if ($this->is_channel_account()) {
			$tmp = explode("@", $this->account);
			return $tmp[1];		
		}	
		return false;
	}
	
	function is_channel_account()
	{		
		if (empty($this->account)) return false;
		
		if (strstr($this->account, '@') == FALSE) {
			return false;
		} else return true;
	}
	
	function display_notice()
	{
		$cnt = $this->CI->db->from("notice_targets")->where("uid", $this->uid)->where("is_read", 0)->count_all_results();
		$re = "<a href='".base_url()."/notice/get_list' style='font-size:11px; line-height:20px; color:#ff0;' target='_blank'>";
		if ($cnt > 0) 
			$re .= "<img src='".base_url()."/p/img/notice_on.gif' title='通知({$cnt})' onclick='$(this).attr(\"src\", \"".base_url()."/p/img/notice_off.gif\")'>";
		else $re .= "<img src='".base_url()."/p/img/notice_off.gif' title='通知'>";
		$re .= "</a>";
		return $re;
	}
	
	function _return_error($msg) {
		$this->error_message = $msg;
		return false;
	}
	
	function generate_token($len = 32)
	{

		// Array of potential characters, shuffled.
		$chars = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
		);
		shuffle($chars);

		$num_chars = count($chars) - 1;
		$token = '';

		// Create random token at the specified length.
		for ($i = 0; $i < $len; $i++)
		{
			$token .= $chars[mt_rand(0, $num_chars)];
		}

		return $token;

	}
}
