<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minik_page extends MY_Controller {		

	function entry()
	{
		$game = $this->input->get("game");
		if (empty($game)) die("參數錯誤");
		 
		$this->load->library("channel_api/minik");
		$minik = $this->minik->new_minik($game);
		if ($minik == false) {
			die($this->minik->error_message);
		}		
		
		$userinfo = $minik->GetUserInfo();
		fb($userinfo);
		
		if (isset($userinfo['uid'])) { //登入			
			$euid = strtolower($userinfo['uid']);
			$name = $userinfo['name'];			
			$account = $euid.'@minik';
			$password = rand(100000, 999999);
			$email = '';
			
			if ($this->g_user->login($account, $password, $email, $name, $game)) {
				
				$inviteParams = array();
							
			   	//to invite user's friends who have not played this application.
			   	$inviteType = "notjoin";
			   	$inviteSrc = $minik->getInviteSrc($inviteParams, $inviteType);
			
			   	//to invite user's all friends.
			   	$inviteSrc = $minik->getInviteSrc($inviteParams);
		
				$cnt = $this->db->from("log_game_logins")
					->where("account", $this->g_user->account)
					->where("server_id in (SELECT id FROM `servers` WHERE game_id='{$game}')", null, false)
					->where("is_recent", "1")
					->where("DATEDIFF(NOW(), create_time)<=90", null, false)
					->count_all_results();

			   	//選擇伺服器
			   	$choose_server_form = file_get_contents("http://{$game}.long_e.com.tw/common/choose_server_form");
			   	$choose_server_form = strtr($choose_server_form, array("target='_self'"=>"target='_blank'", 'target="_self"'=>'target="_blank"')); //調為開新頁
			   	
				$this->_init_layout();					
				$this->g_layout->set("minik", $minik)->set("inviteSrc", $inviteSrc)
					->set("choose_server_form", $choose_server_form)
					->set("old_user", ($cnt>0))
					->view("minik_page/{$game}");
			}
			else{
				//創立失敗
				die($this->g_user->error_message);
			}	
		}
		else //未登
		{
			var_dump($userinfo);
			exit();
		}
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */