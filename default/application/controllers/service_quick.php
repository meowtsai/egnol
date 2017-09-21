<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_quick extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}

	function index()
	{
        $vendor_game_id = $this->input->get_post("game_id");
        $partner_uid    = $this->input->get_post("partner_uid");
        $in_game_id	    = $this->input->get_post("in_game_id");
        $server_name	= $this->input->get_post("server_name");
        $character_name	= $this->input->get_post("character_name");

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
            $_SESSION['vendor_game_id']	= $vendor_game_id;
            $_SESSION['partner_uid']	= $partner_uid;
            $_SESSION['in_game_id']	    = $in_game_id;
            $_SESSION['server_name']	= $server_name;
            $_SESSION['character_name']	= $character_name;
        }
        
		$game_info = $this->db->from("games")->where("vendor_game_id", $vendor_game_id)->get()->row();
        
        $_SESSION['game_name']	= $game_info->name;
        
        if (!empty($server_name) && !empty($character_name)) {
            $server_info = $this->db->from("servers")->where("game_id", $game_info->game_id)->where("name", $server_name)->get()->row();
            $character_info = $this->db->from("characters")->where("server_id", $server_info->server_id)->where("in_game_id", $in_game_id)->get()->row();
            
            if (isset($server_info->server_id)) $_SESSION['server_id'] = $server_info->server_id;
                                          
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
        
		$this->_init_layout()
			->set("site", $game_info->game_id)
			->set("not_read_cnt", $not_read_cnt)
			->set("question_cnt", $question_cnt)
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
		$servers = $this->db->where_in("server_status", array("public", "maintaining"))->order_by("server_id")->get("servers");

		// 讀取玩家角色列表
		//$characters = $this->db->from("characters")->where("partner_uid", $partner_uid)->get();

		$this->_init_layout()
			->add_js_include("service_quick/question")
			->set("games", $games)
			->set("servers", $servers)
			->set("partner_uid", $_SESSION['partner_uid'])
			->set("server_id", $_SESSION['server_id'])
			->set("server_name", $_SESSION['server_name'])
			->set("character_name", $_SESSION['character_name'])
			->add_css_link("login")
			->add_css_link("money")
			->add_css_link("server")
			->mobile_view();
	}
    
	function question_ajax()
	{
		$site = $this->_get_site();

		//if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));
		if ( ! $this->input->post("server")) die(json_encode(array("status"=>"failure", "message"=>"尚未選擇伺服器")));
		if ( ! $this->input->post("question_type")) die(json_encode(array("status"=>"failure", "message"=>"尚未選擇問題類型")));
		if ( ! $this->input->post("content")) die(json_encode(array("status"=>"failure", "message"=>"尚未填寫問題描述")));
		if ( ! $this->input->post("partner_uid") && ! $this->input->post("mobile") &&  ! $this->input->post("email") ) die(json_encode(array("status"=>"failure", "message"=>"請擇一填寫電子郵件信箱或手機")));
		
		/*
        $query = $this->db->query("SELECT count(*) > (3-1) as chk FROM questions WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");		
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問，若有未說明問題，請以原提問進行補述!")));
        */
		
        $check_id = base_convert(time(), 10, 32);
        
		$data = array(
			"uid" => 0,
			"partner_uid" => $_SESSION['partner_uid'],
			'type' => $this->input->post("question_type"),
			"server_id" => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			//"type" => '9',
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
			//'admin_uid' => $_SESSION['admin_uid'],
			'phone' => $this->input->post("mobile"),
			'email' => $this->input->post("email"),
			'check_id' => $check_id,
			'is_quick' => 1,
		);	
        
        /*
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
		$config['allowed_types'] = 'gif|jpg|bmp|png';
		$config['max_size']	= '6144'; //1MB
		$config['max_width'] = '6144';
		$config['max_height'] = '6144';
		$config['encrypt_name'] = true;
		
		$upload_cnt = 0;
		if ( ! empty($_FILES["file01"]["name"]))
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("file01"))
			{
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
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
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
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
				die(json_encode(array("status"=>"failure", "message"=>$this->upload->display_errors('', ''))));
			}
			else
			{
				$upload_data = $this->upload->data();
				$data['pic_path'.(++$upload_cnt)] = site_url("p/upload/{$upload_data['file_name']}");					
			}
		}

		$this->db
			->set("create_time", "now()", false)
			->set("update_time", "now()", false)
			->insert("questions", $data);
        
        if (!$this->input->post("partner_uid")) {

            header('content-type:text/html; charset=utf-8');

            if(filter_var($this->input->post("email"), FILTER_VALIDATE_EMAIL))
            {
                $msg = "後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件：<br>".$check_id;
                
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
		            die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件：".$check_id)));
                }
                else
                {
				    die(json_encode(array("status"=>"failure", "message"=>"E-Mail 發送失敗。請確認E-mail為有效信箱。")));
                }
            }
            else
            {
                // 手機號碼的話要發送簡訊
                $msg = "後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件：".$check_id;

                $this->load->library("g_send_sms");

                if($this->g_send_sms->send($site, $this->input->post("mobile"), $msg))
                {
                    $_SESSION['check_id'] = $check_id;
                    $_SESSION['email'] = $this->input->post("email");
                    $_SESSION['mobile'] = $this->input->post("mobile");
		            die(json_encode(array("status"=>"success", "site"=> $site, "message"=>"後續追蹤客服問題請用提問時信箱或手機及以下代碼查詢原案件：".$check_id)));
                }
                else
                {
				    die(json_encode(array("status"=>"failure", "message"=>"簡訊發送失敗。請確認輸入手機為有效號碼。")));
                }
            }
        } else {
		    die(json_encode(array("status"=>"success", "site"=> $site)));
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
		
		$this->_init_layout()
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
		
		if ($question)
		{
			if ($question->status == '2' || $question->status == '4') {
				$this->db->where("id", $id)->update("questions", array("is_read"=>'1'));
			}		
			$replies = $this->db->from("question_replies")->where("question_id", $id)->order_by("id", "asc")->get();
		}
		else {
			$replies = false;
		}
		
		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("server")
			->add_js_include("service_quick/view")
			->add_js_include("jquery.blockUI")
			->add_js_include("default")
			->set("replies", $replies)
			->set("question", $question)
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
		
		$this->db
			->set("create_time", "now()", false)
			->insert("question_replies", $data);
		
		$this->db->where("id", $question_id)->update("questions", array("is_read"=>'0', "status"=>'1'));		
		
		die(json_encode(array("status"=>"success")));		
	}
	
	function close_question($id)
	{
		//if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));
		
		$question = $this->db->where("id", $id)->from("questions q")->get()->row();
		//if ($question->uid <> $this->g_user->uid) die(json_encode(array("status"=>"failure", "message"=>"權限不足")));
		
		$this->db->set("status", "4")->where("id", $id)->update("questions");
		die(json_encode(array("status"=>"success")));	
	}
}
