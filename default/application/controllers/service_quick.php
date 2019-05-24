<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_quick extends MY_Controller {

	function __construct()
	{

		parent::__construct();
		$this->load->config("service");

		// $ip = $_SERVER['REMOTE_ADDR'];
		// if ($ip =="103.123.0.6" ){
		// 	die();
		// }

	}

	function index()
	{

		if ($this->input->get("site") == "long_e¶m_game_id=g78naxx2hmt")
		{
			//redirect('/service_quick?site=long_e&param_game_id=g78naxx2hmt', 'location', 301);
			header("Location: /service_quick?site=long_e&param_game_id=g78naxx2hmt");
			die();
		}
		if ($this->input->get("site") == "long_e¶m_game_id=h55naxx2tw")
		{
			//redirect('/service_quick?site=long_e&param_game_id=g78naxx2hmt', 'location', 301);
			header("Location: /service_quick?site=long_e&param_game_id=h55naxx2tw");
			die();
		}



        $vendor_game_id = $this->input->get_post("game_id");
        $partner_uid    = $this->input->get_post("partner_uid");
        $in_game_id	    = $this->input->get_post("in_game_id");
        $server_name	= $this->input->get_post("server_name");
        $character_name	= $this->input->get_post("character_name");

				$level	= $this->input->get_post("level");
				$usr_device	= $this->input->get_post("usr_device");
				$os_ver	= $this->input->get_post("os_ver");
				$app_ver	= $this->input->get_post("app_ver");
				$time_zone	= $this->input->get_post("time_zone");
				$network	= $this->input->get_post("network");
				$key_string	= $this->input->get_post("key");
				$key	= $this->config->item("question_key")[$vendor_game_id];


				$diffgame	= $this->input->get("param_game_id");
				$evt_code	= $this->input->get("evt_code");
				$_SESSION['evt_code']	= $evt_code;

				$not_read_cnt = 0;

				if ($diffgame)
				{
					unset($_SESSION['vendor_game_id']);
					unset($_SESSION['partner_uid']);
					unset($_SESSION['in_game_id']);
					unset($_SESSION['server_name']);
					unset($_SESSION['character_name']);
					unset($_SESSION['server_id']);
					unset($_SESSION['game_name']);
					unset($_SESSION['email']);
					unset($_SESSION['mobile']);
					unset($_SESSION['check_id']);

					$vendor_game_id = $diffgame;

				}
        if ($partner_uid) {
            unset($_SESSION['vendor_game_id']);
            unset($_SESSION['partner_uid']);
            unset($_SESSION['in_game_id']);
            unset($_SESSION['server_name']);
            unset($_SESSION['character_name']);
            unset($_SESSION['server_id']);
            unset($_SESSION['game_name']);
            unset($_SESSION['email']);
            unset($_SESSION['mobile']);
            unset($_SESSION['check_id']);

						$encode_server_name =urlencode($server_name);
						$encode_c_name =urlencode($character_name);
						$os_ver =urlencode($os_ver);

						//$in_game_id=urlencode($in_game_id);
						//$usr_device =rawurlencode($usr_device);

						//echo "rawurlencode".rawurlencode($character_name).'<br />';
						//echo "urlencode".urlencode($character_name).'<br />';
						// echo rawurlencode($usr_device).'<br />';

						//$str_to_encrypt = "game_id={$vendor_game_id}&partner_uid={$partner_uid}&in_game_id={$in_game_id}&server_name={$encode_server_name}&character_name={$encode_c_name}&level={$level}&usr_device={$usr_device}&os_ver={$os_ver}&app_ver={$app_ver}&time_zone={$time_zone}&network={$network}&key={$key}";
						$str_to_encrypt = "game_id={$vendor_game_id}&partner_uid={$partner_uid}&in_game_id={$in_game_id}&server_name={$encode_server_name}&character_name={$encode_c_name}&level={$level}&usr_device={$usr_device}&os_ver={$os_ver}&app_ver={$app_ver}&network={$network}&key={$key}";
						//echo $str_to_encrypt.'<br />';
				    $sig = MD5($str_to_encrypt);

						//echo $str_to_encrypt.'<br />';
						//echo $sig.'<br />';

						//game_id=g66naxx2tw&partner_uid=11029961&in_game_id=XDLstlJQCVT9bJ/b&server_name=審核服&character_name=Q·阿加莎&level=1&usr_device=Android&os_ver=8.0.0&app_ver=376339.382966.382939&time_zone=+0000&network=1&key=85328f5ead26ed6627690d55f8f5fc5e


						//echo "this=> ".MD5('game_id=g66naxx2tw&partner_uid=11029961&in_game_id=XDLstlJQCVT9bJ%2Fb&server_name=%E5%AF%A9%E6%A0%B8%E6%9C%8D&character_name=Q%C2%B7%E9%98%BF%E5%8A%A0%E8%8E%8E&level=1&usr_device=Android&os_ver=8.0.0&app_ver=376339.382966.382939&time_zone=+0000&network=1&key=4yekay2g6x').'<br />';
						//echo "+ 的".urlencode('+').'<br />';
						//echo "+ 的".rawurlencode('+').'<br />';
						/// 0212 h35 android sorting problems so we modified the code to fit both scenario

						if ($sig !==$key_string)
						{
							parse_str($_SERVER['QUERY_STRING'],$output_query);
							$str_to_encrypt ='';

							foreach($output_query as $qstringkey => $qstringvalue) {
								//echo "$key is at $value";
								if ($qstringkey!='key')
								{
									//$str_to_encrypt.=$qstringkey.'='.rawurlencode($qstringvalue).'&';
									$str_to_encrypt.=$qstringkey.'='.urlencode($qstringvalue).'&';
								}

							}
							$str_to_encrypt.="key={$key}";
							//echo $str_to_encrypt.'<br />';

							$sig = MD5($str_to_encrypt);
							//echo $sig.'<br />';


						}

						if ($sig ===$key_string)
						{
							$_SESSION['vendor_game_id']	= $vendor_game_id;
	            $_SESSION['partner_uid']	= $partner_uid;
	            $_SESSION['in_game_id']	    = $in_game_id;
	            $_SESSION['server_name']	= $server_name;
	            $_SESSION['character_name']	= $character_name;
							$_SESSION['q_note']	=  "等級={$level}, 系統={$usr_device}, os={$os_ver}, app_ver={$app_ver},time_zone={$time_zone},network={$network}";


							$not_read_cnt = $this->db->select("q.id")
										->where("q.partner_uid", $_SESSION['partner_uid'])
										->where("q.status", "2")
										->where("q.is_read", "0")
										->from("questions q")->count_all_results();

						}

        }
				if (!$vendor_game_id)
				{
					$vendor_game_id = 'h35naxx1hmt';
				}
				$game_info = $this->db->from("games")->where("vendor_game_id", $vendor_game_id)->get()->row();
		    $_SESSION['game_name']	= $game_info->name;

        if (!empty($server_name) && !empty($character_name)) {
            $server_info = $this->db->from("servers")->where("game_id", $game_info->game_id)->where("address", $server_name)->get()->row();
            $character_info = $this->db->from("characters")->where("server_id", $server_info->server_id)->where("in_game_id", $in_game_id)->get()->row();

						//<? if ($row->status=='2' && $row->is_read=='0') echo '<span class="field" style="color:red">(未讀)</span>'

            if (isset($server_info->server_id)) {
							$_SESSION['server_id'] = $server_info->server_id;
							$_SESSION['server_name'] = $server_info->name;
						}

            if (isset($server_info->server_id) && !isset($character_info->id)) {

							$this->load->model("g_characters");
              $insert_id = $this->g_characters->create_character($server_info,
                    array(
                        "uid" => 0,
                        'partner_uid' => $partner_uid,
                        'name' => $character_name,
                        'in_game_id' => $in_game_id
                    ));
            } else if ($character_info->name <> $character_name) {

		        $this->load->model("g_characters");
                $affected_rows = $this->g_characters->update_character(
                    array(
                        'name' => $character_name
                    ),
                    array(
                        'server_id' => $server_info->server_id,
                        'in_game_id' => $in_game_id
                    ));
            }
        }

        $is_ingame = ($_SESSION['vendor_game_id']) ? 1 : 0;

		$this->_init_layout("客服中心")
			->set("site", $game_info->game_id)
			//->set("site", 'g83tw')
			->set("not_read_cnt", $not_read_cnt)
			->set("question_cnt", $question_cnt)
			->set("is_ingame", $is_ingame)
			->add_css_link("login")
			->add_css_link("server")
			->mobile_view();
	}

	function question()
	{


		//$this->_require_login();
		$site = $this->_get_site();
		// $user_ip = $_SERVER['REMOTE_ADDR'];
		// if ($user_ip=="61.220.44.200"){
		// 	header("Location: /service_quick/question_internal?site=$site");
		// 	die();
		// }

		$server = $this->db->from("servers gi")
			->join("games g", "gi.game_id=g.game_id")->get();


		$games = $this->db->from("games")->where("is_active", "1")->get();
		$game_info = $this->db->from("games")->where("vendor_game_id", $site)->get()->row();
		$_SESSION['game_name']	= $game_info->name;
		//$games = $this->db->from("games")->where_in("is_active", array("1", "2"))->get();


		$this->db->from("servers")->where_in("server_status", array("public", "private"));
		if ($site=='LRE') {
				$this->db->order_by("name", "desc");
		}
		else {
			$this->db->order_by("server_id");
		}
		$servers = $this->db->get();

		//$result = $this->get_event_status(11);

		//[{"id":"11","event_name":"\u7b2c\u4e94\u4eba\u683c yahoo \u626d\u86cb\u6d3b\u52d5","begin_time":"2018-12-03 10:00:00","end_time":"2018-12-31 23:59:59"},{"id":"13","event_name":"Google Play x \u7b2c\u4e94\u4eba\u683c","begin_time":"2018-12-03 10:00:00","end_time":"2019-02-28 23:59:59"}]
		$events = $this->get_events($site);

    $is_ingame = ($_SESSION['vendor_game_id']) ? 1 : 0;

		$this->_init_layout("客服中心")
			->add_js_include("jquery.blockUI")
			->add_js_include("service_quick/question")
			->set("games", $games)
			->set("servers", $servers)
			->set("events", $events)
			->set("partner_uid", $_SESSION['partner_uid'])
			->set("server_id", $_SESSION['server_id'])
			->set("server_name", $_SESSION['server_name'])
			->set("character_name", $_SESSION['character_name'])
			->set("evt_code", $_SESSION['evt_code'])
			->set("is_ingame", $is_ingame)
			->add_css_link("login")
			->add_css_link("money")
			->add_css_link("server")
			->mobile_view();
	}

	function question_ajax()
	{
		$ip = $_SERVER['REMOTE_ADDR'];


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
								http_build_query(array('secret' => '6LefP6UUAAAAAII1VSYnPPfFBFOy131pz0L9c7kX', 'response' =>$this->input->post("g-recaptcha-response")  , 'remoteip' => $ip )));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

		//{ "success": false, "error-codes": [ "invalid-input-response" ] }
		//{ "success": true, "challenge_ts": "2019-05-24T07:56:36Z", "hostname": "test-payment.longeplay.com.tw" }
//echo $server_output;


// $server_output ='{"success": false, "error-codes":["invalid-input-response"]}';
// $server_output ='{ "success": true, "challenge_ts": "2019-05-24T07:56:36Z", "hostname": "test-payment.longeplay.com.tw" }';
//echo $server_output;
	$google_response = json_decode($server_output);
//{ "success": false, "error-codes": [ "invalid-input-response" ] }Array ( [success] => [error-codes] => Array ( [0] => invalid-input-response ) )
//echo "<br />hello".$google_response->{'success'};

if (!$google_response->{'success'}) {
	die(json_encode(array("status"=>"failure", "message"=>"Are you a robot?")));
}






		$site = $this->_get_site();

		if (isset($_SERVER["CONTENT_LENGTH"]))
		if($_SERVER["CONTENT_LENGTH"]>((int)ini_get('post_max_size')*1024*1024))
		die(json_encode(array("status"=>"failure", "message"=>"所有附件加總大小請勿超過".ini_get('post_max_size').", ")));

		//if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));

		if ( ! $this->input->post("server")) die(json_encode(array("status"=>"failure", "message"=>"尚未選擇伺服器")));
		if ( ! $this->input->post("question_type")) die(json_encode(array("status"=>"failure", "message"=>"尚未選擇問題類型")));
		if ( ! $this->input->post("content")) die(json_encode(array("status"=>"failure", "message"=>"尚未填寫問題描述")));



		if ( ! $this->input->post("partner_uid") && (!filter_var($this->input->post("email"), FILTER_VALIDATE_EMAIL)))
		{
			die(json_encode(array("status"=>"failure", "message"=>"E-Mail 格式錯誤。")));
		}


		if ( ! $this->input->post("partner_uid") && (! $this->input->post("mobile") ||  ! $this->input->post("email")) ) die(json_encode(array("status"=>"failure", "message"=>"請填寫電子郵件信箱和手機")));

		/*
        $query = $this->db->query("SELECT count(*) > (3-1) as chk FROM questions WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問，若有未說明問題，請以原提問進行補述!")));
        */
		$post_server_id =$this->input->post("server");
		$post_character_name =htmlspecialchars($this->input->post("character_name"));
		$post_content =nl2br(htmlspecialchars($this->input->post("content")));

		// if content contains char in list extension B
		if ($this->isListB($post_content))
		{
			log_message('error', '提問單可能含有冷僻字元:'.$post_character_name.':'.$post_content);
			//die(json_encode(array("status"=>"failure", "message"=>"可能含有冷僻字元,請移除不合法字元.")));
		}



		if (!IN_OFFICE){
			$query = $this->db->query("SELECT count(*) as chk FROM questions
			WHERE (server_id='{$post_server_id}' and character_name='{$post_character_name}' and content='{$post_content}' and create_time > Date_Sub(NOW(), INTERVAL 3 HOUR))
			or (note like '%IP={$ip},%' and create_time > Date_Sub(NOW(), INTERVAL 3 MINUTE) )");
			if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問!")));
		}


	 	$if_a = array('1','l','0','o');
		$then_b = array('8','k','f','w');

    $check_id = str_replace($if_a, $then_b, base_convert(time(), 10, 32));

		$country_name = "";
		if ($ip)
		{
			$country_name =geoip_record_by_name($ip)["country_name"];
		}



		$data = array(
			"uid" => 0,
			"partner_uid" => $_SESSION['partner_uid'],
			'type' => $this->input->post("question_type"),
			"server_id" => $post_server_id,
			'character_name' => $post_character_name,
			//"type" => '9',
			'content' => $post_content,
			'is_in_game' => (($_SESSION['vendor_game_id']) ? 1 : 0),
			'phone' => $this->input->post("mobile"),
			'email' => $this->input->post("email"),
			'check_id' => $check_id,
			'is_quick' => 1,
			"note" => $_SESSION['q_note']."| IP={$ip}, 國家={$country_name} ",
		);

		$game = "";

		if ($this->input->post("game"))
		{
			$game = $this->input->post("game");
		}

        /*
				$info = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
				//print_r ($info);
				echo $info["country_name"];
		$data = array(
			"uid" => $this->g_user->uid,
			'type' => $this->input->post("question_type"),
			'server_id' => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);
        */

		$this->load->library('upload');
		$config['upload_path'] = realpath("p/upload");
		$config['allowed_types'] = 'gif|jpg|jpeg|bmp|png';
		$config['max_size']	= '10240'; //1MB
		$config['max_width'] = '10240';
		$config['max_height'] = '10240';
		$config['encrypt_name'] = true;

		$upload_cnt = 0;
		if ( ! empty($_FILES["file01"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file01"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->allowed_types." and ".$this->upload->file_type." and ".$this->upload->ext)));
				die(json_encode(array("status"=>"failure", "message"=>"附件1上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
			}
		}

		if ( ! empty($_FILES["file02"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file02"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件2上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
			}
		}
		if ( ! empty($_FILES["file03"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file03"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件3上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
			}
		}

		//0523 per cs request - add 3 addtional pics columns
		$addtional_pics = array();
		if ( ! empty($_FILES["file04"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file04"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件4上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				if ($upload_cnt>=3)
				{
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
				}
				else {
					$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
				}

			}
		}

		if ( ! empty($_FILES["file05"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file05"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件5上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				if ($upload_cnt>=3)
				{
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
				}
				else {
					$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
				}
			}
		}

		if ( ! empty($_FILES["file06"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file06"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件6上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
				if ($upload_cnt>=3)
				{
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
				}
				else {
					$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");
				}
			}
		}


		$this->db
			->set("create_time", "now()", false)
			->set("update_time", "now()", false)
			->insert("questions", $data);

			$q_id = $this->db->insert_id();

			// $this->db->select("id")
			// 		->where("check_id", $check_id)
			// 		->from("questions")
			// 		->order_by("id", "desc");
			//
			// $queryId = $this->db->get();

			for($count = 0; $count < sizeof($addtional_pics);$count++)
			{
				$this->db->insert("question_pictures", array("question_id"=>$q_id,"pic_path"=>$addtional_pics[$count]));
			}







			// if ($queryId->num_rows() > 0) {
			// 		$q_id = $queryId->row()->id;
			// 	}

        if (!$this->input->post("partner_uid")) {

            header('content-type:text/html; charset=utf-8');

            if(filter_var($this->input->post("email"), FILTER_VALIDATE_EMAIL))
            {
                $msg = "您提問的案件單號為#".$q_id."<br />後續若要<a href='https://game.longeplay.com.tw/service_quick?site=long_e&param_game_id=".$game."'>追蹤此單號</a>的客服問題請用以下代碼進行查詢：<br /><b>".$check_id."</b>";
			    			$this->load->library("g_send_mail");

                if($this->g_send_mail->send_view($this->input->post("email"),
									$_SESSION['game_name']."客服代碼通知信[".date("Y/m/d H:i:s")."]",
									"g_blank_mail",
									array("game_name" => $_SESSION['game_name'], "msg" => $msg),
									array("headerimg" => FCPATH."/p/image/mail/header.jpg")))
                {
                    $_SESSION['check_id'] = $check_id;
                    $_SESSION['email'] = $this->input->post("email");
                    $_SESSION['mobile'] = $this->input->post("mobile");
		            	die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"後續追蹤客服問題#".$q_id."請用提問時信箱或手機及以下代碼查詢：<b>".$check_id."</b>")));
                }
                else
                {
				    			die(json_encode(array("status"=>"failure", "message"=>"E-Mail 發送失敗。請確認E-mail為有效信箱。")));
                }
            }
            else
            {
            //     // 手機號碼的話要發送簡訊
            //     $msg = "後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件".$q_id."：".$check_id;
						//
            //     $this->load->library("g_send_sms");
						//
            //     if($this->g_send_sms->send($site, $this->input->post("mobile"), $msg))
            //     {
            //         $_SESSION['check_id'] = $check_id;
            //         $_SESSION['email'] = $this->input->post("email");
            //         $_SESSION['mobile'] = $this->input->post("mobile");
		        //     die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件#".$q_id."：".$check_id)));
            //     }
            //     else
            //     {
				    // die(json_encode(array("status"=>"failure", "message"=>"簡訊發送失敗。請確認輸入手機為有效號碼或是改為填寫E-mail欄位。")));
            //     }
            }
        } else {
		    die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"提問成功!")));
        }
	}

	function listing()
	{
        $email    = ($this->input->get_post("email")) ? $this->input->get_post("email") : $_SESSION['email'];
        $mobile   = ($this->input->get_post("mobile")) ? $this->input->get_post("mobile") : $_SESSION['mobile'];
        $check_id = ($this->input->get_post("check_id")) ? $this->input->get_post("check_id") : $_SESSION['check_id'];
        $partner_uid = $_SESSION['partner_uid'];

        if (!empty($_SESSION['partner_uid'])) {

            $this->db->select("*")
                ->where("partner_uid", $_SESSION['partner_uid'])
                ->from("questions")
                ->order_by("id", "desc");

            if ($this->input->get("status")) {
                $this->db->where("status", $this->input->get("status"));
            }
            else {
                $this->db->where("status >", "0");
            }

            $query = $this->db->get();
        } elseif ($check_id) {
            $this->db->select("*")
                ->where("check_id", $check_id)
                ->where("(email='{$email}'  or phone='{$mobile}')")
                ->from("questions")
                ->order_by("id", "desc");

            if ($this->input->get("status")) {
                $this->db->where("status", $this->input->get("status"));
            }
            else {
                $this->db->where("status >", "0");
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $_SESSION['check_id'] = $check_id;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;
		        //die(json_encode(array("status"=>"success", "site"=> $site, "id"=> $query->row()->id)));
            } else {
                unset($_SESSION['check_id']);
                unset($_SESSION['email']);
                unset($_SESSION['mobile']);
                //die(json_encode(array("status"=>"failure", "message"=>"查無此客服紀錄。")));
            }
        }

		$this->_init_layout("客服中心")
			->add_js_include("service_quick/listing")
			->set("check_id", $_SESSION['check_id'])
			->set("partner_uid", $_SESSION['partner_uid'])
			->set("query", $query)
			->add_css_link("login")
			->add_css_link("server")
			->mobile_view();
	}

	function listing_ajax()
	{
        $email   = $this->input->get_post("email");
        $mobile  = $this->input->get_post("mobile");
        $check_id = ($this->input->get_post("check_id"));

        if ($check_id) {
            $this->db->select("*")
                ->where("check_id", $check_id)
                ->where("((email <> '' and email is not null and email='{$email}') or (phone <> '' and phone is not null and phone='{$mobile}'))")
                ->from("questions")
                ->order_by("id", "desc");

            if ($this->input->get("status")) {
                $this->db->where("status", $this->input->get("status"));
            }
            else {
                $this->db->where("status >", "0");
            }

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $_SESSION['check_id'] = $check_id;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;
		        die(json_encode(array("status"=>"success", "site"=> $site, "id"=> $query->row()->id)));
            } else {
                die(json_encode(array("status"=>"failure", "message"=>"查無此客服紀錄。")));
								//.$this->db->last_query()
            }
        } else {
            die(json_encode(array("status"=>"failure", "message"=>"查無此客服紀錄。")));
        }
	}

	function listing_reset_ajax()
	{
        unset($_SESSION['vendor_game_id']);
        unset($_SESSION['partner_uid']);
        unset($_SESSION['in_game_id']);
        unset($_SESSION['server_name']);
        unset($_SESSION['character_name']);
        unset($_SESSION['server_id']);
        unset($_SESSION['game_name']);
        unset($_SESSION['email']);
        unset($_SESSION['mobile']);
        unset($_SESSION['check_id']);

        die(json_encode(array("status"=>"success", "site"=> $site)));
	}

	function view($id)
	{
        if ($_SESSION['partner_uid']) {
            $question = $this->db->select("q.*, g.name as game_name, gi.name as server_name, q.phone, q.email")
                        ->where("q.partner_uid", $_SESSION['partner_uid'])
                        ->where("q.id", $id)
                        ->where("q.status >", "0")
                        ->from("questions q")
                        ->join("servers gi", "gi.server_id=q.server_id")
                        ->join("games g", "g.game_id=gi.game_id")
                        //->join("users u", "u.uid=q.uid")
                        ->get()->row();
        } else {
						$question = $this->db->select("q.*, g.name as game_name, gi.name as server_name, q.phone, q.email")
                        ->where("q.check_id", $_SESSION['check_id'])
												->where("(q.email='{$_SESSION['email']}'  or phone='{$_SESSION['mobile']}')")
//												->where("((email <> '' and email is not null and email='{$email}') or (phone <> '' and phone is not null and phone='{$mobile}'))")
												->where("q.id", $id)
                        ->where("q.status >", "0")
                        ->from("questions q")
                        ->join("servers gi", "gi.server_id=q.server_id")
                        ->join("games g", "g.game_id=gi.game_id")
                        //->join("users u", "u.uid=q.uid")
                        ->get()->row();
        }

		$is_ingame = ($_SESSION['vendor_game_id']) ? 1 : 0;
		if ($question)
		{
			if ($question->status == '2' || $question->status == '4' || $question->status == '7') {
				$this->db->where("id", $id)->update("questions", array("is_read"=>'1'));
			}
			$replies = $this->db->from("question_replies")->where("question_id", $id)->order_by("id", "asc")->get();
			$pic_plus = $this->db->from("question_pictures")->where("question_id", $id)->order_by("id", "asc")->get();




		}
		else {
			$replies = false;
			$pic_plus= false;
		}

		$this->_init_layout("客服中心")
			->add_css_link("login")
			->add_css_link("server")
			->add_js_include("service_quick/view")
			->add_js_include("jquery.blockUI")
			->add_js_include("default")
			->set("replies", $replies)
			->set("pic_plus", $pic_plus)
			->set("question", $question)
			->set("is_ingame", $is_ingame)
			->mobile_view();
	}

	function insert_reply_json()
	{
		//if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));

		$ip = $_SERVER['REMOTE_ADDR'];


		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
								http_build_query(array('secret' => '6LefP6UUAAAAAII1VSYnPPfFBFOy131pz0L9c7kX', 'response' =>$this->input->post("g-recaptcha-response")  , 'remoteip' => $ip )));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

	$google_response = json_decode($server_output);

if (!$google_response->{'success'}) {
	die(json_encode(array("status"=>"failure", "message"=>"Are you a robot?")));
}




		$question_id = $this->input->post("question_id");

		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM question_replies WHERE question_id={$question_id} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問!")));

		if ($this->isListB(nl2br(htmlspecialchars($this->input->post("content")))))
		{
			log_message('error', '提問單可能含有冷僻字元:'.$question_id.':'.nl2br(htmlspecialchars($this->input->post("content"))));
			//die(json_encode(array("status"=>"failure", "message"=>"可能含有冷僻字元,請移除不合法字元.")));
		}

		$data = array(
			"uid" => 0,
			"question_id" => $question_id,
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);

		$this->load->library('upload');
		$config['upload_path'] = realpath("p/upload");
		$config['allowed_types'] = 'gif|jpg|jpeg|bmp|png';
		$config['max_size']	= '10240'; //1MB
		$config['max_width'] = '10240';
		$config['max_height'] = '10240';
		$config['encrypt_name'] = true;

		$addtional_pics = array();
		if (!empty($_FILES["reply_file01"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("reply_file01"))
			{
				//die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
				die(json_encode(array("status"=>"failure", "message"=>"附件1上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
			}
		}
		if (!empty($_FILES["reply_file02"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("reply_file02"))
			{
				die(json_encode(array("status"=>"failure", "message"=>"附件2上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
			}
		}
		if (!empty($_FILES["reply_file03"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("reply_file03"))
			{
				die(json_encode(array("status"=>"failure", "message"=>"附件3上傳失敗, 請檢查檔案是否符合規定")));
			}
			else
			{
				$upload_data = $this->upload->data();
					array_push($addtional_pics,site_url("p/upload/{$upload_data['file_name']}"));
			}
		}

		$this->db
			->set("create_time", "now()", false)
			->insert("question_replies", $data);
		$reply_q_id = $this->db->insert_id();

		for($count = 0; $count < sizeof($addtional_pics);$count++)
		{
			$this->db->insert("question_pictures", array("question_id"=>$question_id,"reply_id"=>$reply_q_id,"pic_path"=>$addtional_pics[$count]));
		}




		$this->db->where("id", $question_id)->update("questions", array("is_read"=>'0', "status"=>'1'));

		die(json_encode(array("status"=>"success")));

	}

	function close_question($id)
	{
		//if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));

		$question = $this->db->where("id", $id)->from("questions q")->get()->row();
		//if ($question->uid <> $this->g_user->uid) die(json_encode(array("status"=>"failure", "message"=>"權限不足")));

		$this->db->set("status", "4")->set("close_admin_uid", null)->set("system_closed_start", null)->where("id", $id)->update("questions");
		die(json_encode(array("status"=>"success")));
	}

	function isListB($string) {
		return preg_match('/[\x{20000}-\x{215FF}-\x{21600}-\x{230FF}-\x{23100}-\x{245FF}-\x{24600}-\x{260FF}-\x{26100}-\x{275FF}-\x{27600}-\x{290FF}-\x{29100}-\x{2A6DF}]/u', $string);
	}


	function yahoo_event(){
		if (!$_SESSION['vendor_game_id'])
		{
			die("資料異常, 請關閉視窗重新開始流程。");
		}
		$result = $this->get_event_status(11);

		//echo $event;
		if ($result['status']=='success')
		{
			$event = $result['message'];

			//get char id
			$res_char = $this->get_character($_SESSION['server_id'],$_SESSION['partner_uid'],$_SESSION['in_game_id']);
			if ($res_char['status']=='success')
			{
				$char_data = $res_char['message'];

				$char_id = $char_data->id; //角色id
				$event_status = 0;
				$query = $this->db->from("event_serial")
				->where("uid", $char_id)
				->where("event_id", 11)
				->select("serial")->get();

				if ($query->num_rows()>0)  //已經領取過
				{
					$event_status = $query->row()->serial;
				}

				$this->_init_layout("客服中心")
					->set("event", $event)
					->set("char_data", $char_data)
					->set("event_status", $event_status)
					->add_js_include("service_quick/event")
					->add_css_link("login")
					->add_css_link("server")
					->mobile_view();
			}
			else {
				log_message('error', "角色異常".",".$_SESSION['server_id'].",".$_SESSION['partner_uid'].",".$_SESSION['in_game_id']);
				header("Location: /service_quick/question?site=$site");
				die();
			}

		}
		else {
			//錯誤導回前頁
			log_message('error', '活動尚未開始:'.$_SESSION['server_id'].",".$_SESSION['partner_uid'].",".$_SESSION['in_game_id']);
			header("Location: /service_quick/question?site=$site");
			die();
		}
		//die($event);


	}


	function get_character($server_id,$partner_uid,$in_game_id){

		$query = $this->db->from("characters")
		->where("server_id", $server_id)
		->where("partner_uid", $partner_uid)
		->where("in_game_id", $in_game_id)
		->select("id,name,in_game_id,partner_uid,server_id")->get();
		if ($query->num_rows()>0)
		{
			$char = $query->row();
			return array("status"=>"success", "message"=>$char);
		}
		else {
			return array("status"=>"failure", "message"=>"沒有這個角色!");
		}

	}
	function get_event_status($e_id){
		$query = $this->db->from("events")
		->where("id", $e_id)
		->select("id,game_id,event_name,status,begin_time,end_time")
		->get();

		//$this->db->from("games")->where("vendor_game_id", $vendor_game_id)->get()->row();
		if ($query->num_rows()>0)
		{
			$event = $query->row();
			//if (($event->status=='1' && now() > $event->begin_time && now() < $event->end_time))
			if (($event->status=='1' && now() > $event->begin_time && now() < $event->end_time) || IN_OFFICE )
			{
					return array("status"=>"success", "message"=>$event);
			}
			else {
				return array("status"=>"failure", "message"=>"活動未開放!");
			}

			//{"status":"failure","message":{"id":"11","game_id":"h55naxx2tw","event_name":"\u7b2c\u4e94\u4eba\u683c yahoo \u626d\u86cb\u6d3b\u52d5","status":"1","begin_time":"2018-12-03 00:00:00","end_time":"2018-12-15 00:00:00"}}
		}
		else {
			return array("status"=>"failure", "message"=>"沒有這個活動!");
		}

	}

	function yahoo_ajax()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( ! $this->input->post("serial_no")) die(json_encode(array("status"=>"failure", "message"=>"尚未填寫活動序號")));
		//if (! $this->input->post("partner_uid")) die(json_encode(array("status"=>"failure", "message"=>"沒有角色id, 資料異常, 請重新開始流程")));
		if (! $this->input->post("char_id")) die(json_encode(array("status"=>"failure", "message"=>"沒有角色id, 資料異常, 請重新開始流程")));

		$post_character_name =htmlspecialchars($this->input->post("character_name"));
		$serial_no = substr($this->input->post("serial_no"),0,16);
		$char_id= $this->input->post("char_id");

		$try_count = 0;
		$query = $this->db->query("SELECT count(*) as chk FROM log_yahoo_event WHERE char_id='{$char_id}'");
		$try_count = (4 - $query->row()->chk);
		if ($query->row()->chk > 4) die(json_encode(array("status"=>"failure", "message"=>"回報數量已達上限,若有疑問請回報客服!")));

		$query = $this->db->query("SELECT count(*) as chk FROM event_serial WHERE event_id=11 and uid='{$char_id}'");
		if ($query->row()->chk > 0) die(json_encode(array("status"=>"failure", "message"=>"您的角色已經領取過了喔。")));

		//log data
		$data = array(
			"char_id" => $char_id,
			'ip' => $ip,
			'serial_no' => $serial_no,
		);
		$this->db
		->insert("log_yahoo_event", $data);
		//end log data

		//check
		$updated_s_id = 0;

		// get the record that you want to update
		$this->db->where(array('substr(serial,1,16)'=>$data['serial_no'], 'event_id' => '11','status' => '0'));
		$query_serial = $this->db->get('event_serial');

		// getting the Id
		$serial_result = $query_serial->result_array();
		$updated_s_id = $serial_result[0]['id'];

		if ($updated_s_id)
		{
			$this->db->where(array('substr(serial,1,16)'=>$data['serial_no'], 'event_id' => '11','status' => '0'));
			$this->db->update('event_serial',array("uid" => $char_id,"status" => 1));
			die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"兌換成功! 獎項將於 2019/01/15 晚上 23：59 前，以遊戲內郵件發送至所填寫的角色ID。")));
		}
		else {
			die(json_encode(array("status"=>"failure", "site"=> $site, "message"=>"兌換失敗，序號錯誤或已被使用，錯誤五次將會鎖定。(剩餘次數:{$try_count})")));
		}


	}

	//find events by game_id
	function get_events($game_id){
		$query = $this->db->from("events")
		->where("type", 2)
		->where("status !=", '0')
		->where("game_id", $game_id)
		->select("id,status,event_name,begin_time,end_time")->get();
		//if ($event->status=='1' && now() > $event->begin_time && now() < $event->end_time) || IN_OFFICE )
		//die(json_encode($query->result()));
		return $query->result();
	}

	function event_serial(){
		//取出活動相關資訊

		$event_id = $this->input->get_post("event_id");
		$result = $this->get_event_status($event_id);
		if ($result['status']=='success')
		{
			$event = $result['message'];
				//get char id
				$res_char = $this->get_character($_SESSION['server_id'],$_SESSION['partner_uid'],$_SESSION['in_game_id']);
				if ($res_char['status']=='success')
				{
					$char_data = $res_char['message'];
					$char_id = $char_data->id; //角色id
					$records = 0;
					$query = $this->db->from("event_serial a")
					->join("events b","a.event_id = b.id" , "left")
					->join("serial_main c", "a.event_sub_id =c.id", "left")
					->where("a.uid", $char_id)
					->where("a.event_id", $event_id)
					->select("b.event_name, c.title, a.serial ")->get();
					if ($query->num_rows()>0)  //已經領取過
					{
						$records = $query->result();
					}

					$this->_init_layout("客服中心")
						->set("event", $event)
						->set("char_data", $char_data)
						->set("records", $records)
						->add_css_link("login")
						->add_css_link("server")
						->mobile_view();
				}
				else {
					log_message('error', "角色異常".",".$_SESSION['server_id'].",".$_SESSION['partner_uid'].",".$_SESSION['in_game_id']);
					header("Location: /service_quick/question?site=$site");
					die();
				}


		}
		else {
			//錯誤導回前頁
			log_message('error', '活動尚未開始:'.$_SESSION['server_id'].",".$_SESSION['partner_uid'].",".$_SESSION['in_game_id']);
			header("Location: /service_quick/question?site=$site");
			die();
		}


	}

	function event_serial_ajax()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( ! $this->input->post("serial_no")) die(json_encode(array("status"=>"failure", "message"=>"尚未填寫活動序號")));
		//if (! $this->input->post("partner_uid")) die(json_encode(array("status"=>"failure", "message"=>"沒有角色id, 資料異常, 請重新開始流程")));
		if (! $this->input->post("char_id")) die(json_encode(array("status"=>"failure", "message"=>"沒有角色id, 資料異常, 請重新開始流程")));



		$post_character_name =htmlspecialchars($this->input->post("character_name"));
		$partner_uid=$this->input->post("partner_uid");
		$serial_no = $this->input->post("serial_no");
		if (strlen($serial_no)>15) {
			$serial_no = substr($this->input->post("serial_no"),0,16);
		}
		else {
			$serial_no = $this->input->post("serial_no");
		}

		$char_id= $this->input->post("char_id");
		$event_id= $this->input->post("event_id");
		$is_ingame= $this->input->post("is_ingame");
		$email = "";
		$server = "";


		$try_count = 0;
		//select count(*) from log_serial_event where status=0 and partner_uid='195335332'
		$query = $this->db->query("SELECT count(*) as chk FROM log_serial_event WHERE status=0  and char_id='{$char_id}' and event_id={$event_id}");
		$try_count = (9 - $query->row()->chk);
		if ($query->row()->chk > 9) die(json_encode(array("status"=>"failure", "message"=>"回報數量已達上限,若有疑問請回報客服!")));
		// select event_sub_id from event_serial where event_id=13 and uid=361257
		$query = $this->db->query("SELECT group_concat(event_sub_id) as chk_sub_id FROM event_serial WHERE event_id={$event_id} and uid='{$char_id}'");
		$chk_sub_id = isset($query->row()->chk_sub_id)? $query->row()->chk_sub_id:0;
		//if ($query->row()->chk > 0) die(json_encode(array("status"=>"failure", "message"=>"您的角色已經領取過了喔。")));
		//echo $chk_sub_id; 1,2,2
		//log data
		$data = array(
			"partner_uid" => $partner_uid,
			"char_id" => $char_id,
			"char_name" => $post_character_name,
			'ip' => $ip,
			'serial' => $serial_no,
			'event_id' => $event_id,

		);

		//end log data

		//check
		$updated_s_id = 0;

		// get the record that you want to update
		$this->db->where(array('substr(serial,1,16)'=>$data['serial'], 'event_id' => $event_id,'status' => '0'));
		if ($chk_sub_id){
			$this->db->where_not_in("event_sub_id",explode(",",$chk_sub_id));
		}
		$query_serial = $this->db->get('event_serial');

		// getting the Id
		$serial_result = $query_serial->result_array();
		$updated_s_id = $serial_result[0]['id'];

		if ($updated_s_id)
		{
			$this->db->where(array('substr(serial,1,16)'=>$data['serial'], 'event_id' => $event_id,'status' => '0'));
			$this->db->update('event_serial',array("uid" => $char_id,"status" => 1));

			$data["status"]=1;
			$this->db->insert("log_serial_event", $data);
			die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"兌換成功! 獎項將於 2019/3/15 晚上 23：59 前，以遊戲內郵件發送獎勵至所填寫的角色ID。")));


		}
		else {
			//die(json_encode(array("status"=>"failure", "site"=> $site, "message"=>"兌換失敗，序號錯誤或已被使用，錯誤五次將會鎖定。(剩餘次數:{$try_count})")));
			$this->db->insert("log_serial_event", $data);
			die(json_encode(array("status"=>"failure", "site"=> $site, "message"=>"兌換失敗, 可能是:序號錯誤/已被使用/同組獎品僅限一次。(剩餘次數:{$try_count})")));
		}


	}


}
