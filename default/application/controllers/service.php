<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->config("service");
	}

	function index()
	{
		$this->_require_login();

		//$this->g_user->check_account_channel('service');
		
		$question_cnt = $this->db->where("uid", $this->g_user->uid)->where("status", "1")
			->from("questions")->count_all_results();
		
		$not_read_cnt = $this->db->where("uid", $this->g_user->uid)->where("status", "2")->where("is_read", "0")
			->from("questions")->count_all_results();
		
		$this->_init_layout()
			->set("not_read_cnt", $not_read_cnt)
			->set("question_cnt", $question_cnt)
			->standard_view();
	}

	function question()
	{
		$this->_require_login();
		
		$server = $this->db->from("servers gi")
			->join("games g", "gi.game_id=g.game_id")->get();
		
		$games = $this->db->from("games")->where("is_active", "1")->get();
		$servers = $this->db->where_in("server_status", array("public", "maintenance"))->order_by("server_id")->get("servers");	
		
		// 讀取玩家角色列表
		$characters = $this->db->from("characters")->where("uid", $this->g_user->uid)->get();

		$this->_init_layout()
			->add_js_include("service/question")
			->set("games", $games)
			->set("servers", $servers)
			->set("characters", $characters)
			->standard_view();
	}
	
	function question_ajax()
	{
		if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));
		if ( ! $this->input->post("content")) die(json_encode(array("status"=>"failure", "message"=>"無內文")));
		
		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM questions WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");		
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問，若有未說明問題，請以原提問進行補述!")));
		
		$data = array(
			"uid" => $this->g_user->uid,				
			'type' => $this->input->post("question_type"),
			'server_id' => $this->input->post("server"),
			'character_name' => htmlspecialchars($this->input->post("character_name")),
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);
		
		$this->load->library('upload');
		$config['upload_path'] = realpath("p/upload");
		$config['allowed_types'] = 'gif|jpg|bmp';
		$config['max_size']	= '1024'; //1MB
		$config['max_width'] = '2048';
		$config['max_height'] = '2048';		
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
		
		die(json_encode(array("status"=>"success")));
	}
	
	function listing()
	{
		$this->_require_login();
		
		$this->db->select("q.*")
			->where("q.uid", $this->g_user->uid)->from("questions q")
			->order_by("id", "desc");
		
		if ($this->input->get("status")) {
			$this->db->where("status", $this->input->get("status"));
		}
		else {
			$this->db->where("status >", "0");
		} 
		
		$query = $this->db->get();
		
		$this->_init_layout()
			->set("query", $query)
			->standard_view();
	}
	
	function view($id)
	{
		$this->_require_login();
		
		$question = $this->db->select("q.*, g.name as game_name, gi.name as server_name, u.mobile, u.email")
					->where("q.uid", $this->g_user->uid)
					->where("q.id", $id)
					->where("q.status >", "0")
					->from("questions q")
					->join("servers gi", "gi.server_id=q.server_id")
					->join("games g", "g.game_id=gi.game_id")
					->join("users u", "u.uid=q.uid")
					->get()->row();
		
		if ($question)
		{
			if ($question->status == '2' || $question->status == '4') {
				$this->db->where("id", $id)->update("questions", array("is_read"=>'1'));
			}		
			$replies = $this->db->from("question_replies")->where("question_id", $id)->order_by("id", "desc")->get();
		}
		else {
			$replies = false;
		}
		
		$this->_init_layout()
			->add_js_include("service/view")
			->set("question", $question)
			->set("replies", $replies)
			->standard_view();
	}
	
	function insert_reply_json()
	{
		if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));
		
		$query = $this->db->query("SELECT count(*) > (3-1) as chk FROM question_replies WHERE uid={$this->g_user->uid} and create_time > date_sub(now(), INTERVAL 1 MINUTE)");		
		if ($query->row()->chk) die(json_encode(array("status"=>"failure", "message"=>"請勿重覆提問!")));		
		
		$question_id = $this->input->post("question_id");
		
		$data = array(
			"uid" => $this->g_user->uid,
			"question_id" => $question_id,
			'content' => nl2br(htmlspecialchars($this->input->post("content"))),
		);		
		
		$this->db
			->set("create_time", "now()", false)
			->insert("question_replies", $data);
		
		$this->db->where("id", $id)->update("questions", array("is_read"=>'0', "status"=>'1'));		
		
		die(json_encode(array("status"=>"success")));		
	}
	
	function close_question($id)
	{
		if ( ! $this->g_user->is_login()) die(json_encode(array("status"=>"failure", "message"=>"請先登入")));
		
		$question = $this->db->where("id", $id)->from("questions q")->get()->row();
		if ($question->uid <> $this->g_user->uid) die(json_encode(array("status"=>"failure", "message"=>"權限不足")));
		
		$this->db->set("status", "4")->where("id", $id)->update("questions");
		die(json_encode(array("status"=>"success")));	
	}
}
