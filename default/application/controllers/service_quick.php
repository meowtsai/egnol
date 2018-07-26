<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_quick extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}

	function index()
	{

		if ($this->input->get("site") == "long_e¶m_game_id=g78naxx2hmt")
		{
			//redirect('/service_quick?site=long_e&param_game_id=g78naxx2hmt', 'location', 301);
			header("Location: /service_quick?site=long_e&param_game_id=g78naxx2hmt");
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

				//echo $_SERVER['SERVER_NAME'];

				$diffgame	= $this->input->get("param_game_id");
				$evt_code	= $this->input->get("evt_code");
				$_SESSION['evt_code']	= $evt_code;

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
						//$usr_device =rawurlencode($usr_device);

						// echo rawurlencode($usr_device).'<br />';
						// echo rawurlencode($usr_device).'<br />';

						$str_to_encrypt = "game_id={$vendor_game_id}&partner_uid={$partner_uid}&in_game_id={$in_game_id}&server_name={$encode_server_name}&character_name={$encode_c_name}&level={$level}&usr_device={$usr_device}&os_ver={$os_ver}&app_ver={$app_ver}&time_zone={$time_zone}&network={$network}&key={$key}";
						//echo $str_to_encrypt.'<br />';
				    $sig = MD5($str_to_encrypt);

						//echo $str_to_encrypt.'<br />';
						//echo $sig.'<br />';

						/// 0212 h35 android sorting problems so we modified the code to fit both scenario

						if ($sig !==$key_string)
						{
							parse_str($_SERVER['QUERY_STRING'],$output_query);
							$str_to_encrypt ='';

							foreach($output_query as $qstringkey => $qstringvalue) {
								//echo "$key is at $value";
								if ($qstringkey!='key')
								{
									$str_to_encrypt.=$qstringkey.'='.rawurlencode($qstringvalue).'&';
								}

							}
							$str_to_encrypt.="key={$key}";
							//echo $str_to_encrypt.'<br />';

							$sig = MD5($str_to_encrypt);
							//echo $sig.'<br />';
							//echo MD5('game_id=g78naxx2hmt&partner_uid=106094919&in_game_id=2004010036&server_name=%E6%AD%A3%E5%BC%8F%E6%9C%8D&character_name=%E4%B8%80%E6%8B%9B%E5%B0%B1%E8%BA%BA&level=6&usr_device=Mi%20A1&os_ver=7.1.2&app_ver=2.1.0&network=1&key=v9nblsfhus').'<br />';

						}

						if ($sig ===$key_string)
						{
							$_SESSION['vendor_game_id']	= $vendor_game_id;
	            $_SESSION['partner_uid']	= $partner_uid;
	            $_SESSION['in_game_id']	    = $in_game_id;
	            $_SESSION['server_name']	= $server_name;
	            $_SESSION['character_name']	= $character_name;
							$_SESSION['q_note']	=  "等級={$level}, 系統={$usr_device}, os={$os_ver}, app_ver={$app_ver},time_zone={$time_zone},network={$network}";
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

		$server = $this->db->from("servers gi")
			->join("games g", "gi.game_id=g.game_id")->get();


		$games = $this->db->from("games")->where("is_active", "1")->get();
		//$games = $this->db->from("games")->where_in("is_active", array("1", "2"))->get();


		$servers = $this->db->where_in("server_status", array("public", "maintaining"))->order_by("server_id")->get("servers");

		// 讀取玩家角色列表
		//$characters = $this->db->from("characters")->where("partner_uid", $partner_uid)->get();

        $is_ingame = ($_SESSION['vendor_game_id']) ? 1 : 0;

		$this->_init_layout("客服中心")
			->add_js_include("jquery.blockUI")
			->add_js_include("service_quick/question")
			->set("games", $games)
			->set("servers", $servers)
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

	 	$if_a = array('1','l','0','o');
		$then_b = array('8','k','f','w');

    $check_id = str_replace($if_a, $then_b, base_convert(time(), 10, 32));
		$ip = $_SERVER['REMOTE_ADDR'];
		$country_name = "";
		if ($ip)
		{
			$country_name =geoip_record_by_name($ip)["country_name"];
		}



		$data = array(
			"uid" => 0,
			"partner_uid" => $_SESSION['partner_uid'],
			'type' => $this->input->post("question_type"),
			"server_id" => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			//"type" => '9',
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
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
                //->where("((email <> '' and email is not null and email='{$email}') or (phone <> '' and phone is not null and phone='{$mobile}'))")
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

		$question_id = $this->input->post("question_id");

		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM question_replies WHERE question_id={$question_id} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問!")));

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
}
