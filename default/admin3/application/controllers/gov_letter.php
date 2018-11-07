<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gov_letter extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->zacl->check_login(true);
		$this->zacl->check("cpl_case", "read");

		$this->load->config("cpl_case");
	}

	function _init_letter_layout()
	{
		return $this->_init_layout()->add_breadcrumb("公函管理", "gov_letter");
	}


	function index()
	{
		$this->router->method = 'get_list';
		$this->get_list();
	}

	function add()
	{
		$this->zacl->check("cpl_case", "modify");
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		// $admin_users = $this->DB2->select("u.*, ar.role_desc")
    //         ->from('admin_users u')
		// 	->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();

		$this->_init_letter_layout()
			->add_breadcrumb("新增公函")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("gov_letter/form")
			->set("games", $games)
			->set("letter", false)
			->render("gov_letter/form");
	}

	function edit($id)
	{
		$this->zacl->check("cpl_case", "modify");
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();

		$query = $this->DB2->from("gov_letters cc")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');

		$letter = $query->row();

		$this->_init_letter_layout()
			->add_breadcrumb("編輯公函案件")
			->add_js_include("gov_letter/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("letter", $letter)
			->render("gov_letter/form");
	}

	function modify_json()
	{

		$letter_id = $this->input->post("letter_id");

		$data = array(
			"o_letter_id" => $this->input->post("o_letter_id"),
			"o_letter_date" => $this->input->post("o_letter_date"),
			"close_date" => $this->input->post("close_date"),
			"contact" => $this->input->post("contact"),
			'note' => nl2br($this->input->post("note")),
			"deadline" => $this->input->post("deadline"),
			"status" => $this->input->post("status"),
			"game_id" => $this->input->post("game_id"),
			"server_id" => $this->input->post("server_id"),
			"role_name" => $this->input->post("role_name"),
			'admin_uid' => $_SESSION['admin_uid'],
		);
		$check_dup = null;
		$this->DB2->select("count(*) as cnt")->from("gov_letters")->where("o_letter_id",$this->input->post("o_letter_id"));
		if ($letter_id)
		{
			$check_dup = $this->DB2->where("id<>{$letter_id}", null, false)->get();

		}
		else {
			$check_dup = $this->DB2->get();
		}
		$cnt =  $check_dup->result()[0]->cnt;
		if ($cnt>0){
			//.$this->DB2->last_query()
				die(json_failure("輸入的[發文字號]已經存在了喔!"));
		}

		$file_path="";
		if ( ! empty($_FILES["file01"]['name']))
		{
			$this->load->library('upload', array("upload_path"=>realpath("p/upload/gov_letters"), "allowed_types"=>"*", 'encrypt_name'=>TRUE));

			if ( ! $this->upload->do_upload("file01"))
			{
				$msg[] = $this->upload->display_errors('', '');
			}
			else
			{
				//rsync_to_slave();
				$upload_data = $this->upload->data();
				$file_path = site_url("p/upload/gov_letters/{$upload_data['file_name']}");
			}
		}
		else {
			$file_path = $this->input->post("file_path");
		}

		$data["file_path"] = $file_path;

		if ($letter_id) {
			$r = $this->DB1
				->where("id", $letter_id)
				->update("gov_letters", $data);
			if (!$r){
					die(json_failure($this->DB1->_error_message()));
			}
		}
		else {
			$r = $this->DB1
				->insert("gov_letters", $data);
			if ($r){
					$letter_id = $this->DB1->insert_id();
			}
			else {
				die(json_failure($this->DB1->_error_message()));
			}
		}

		die(json_message(array("redirect_url"=> base_url("gov_letter/view/".$letter_id), "id"=>$letter_id), true));
	}

	function get_list()
	{
		$this->_init_letter_layout();

		if ($this->input->get("action"))
		{
			header("Cache-Control: private");

			$this->DB2->start_cache();

			$this->input->get("o_letter_id") && $this->DB2->where("c.o_letter_id", $this->input->get("o_letter_id"));
			$this->input->get("role_name") && $this->DB2->like("c.role_name", $this->input->get("role_name"));
			$this->input->get("contact") && $this->DB2->like("c.contact", $this->input->get("contact"));
			$this->input->get("game") && $this->DB2->where("c.game_id", $this->input->get("game"));
			$this->input->get("status") && $this->DB2->where("c.status", $this->input->get("status"));

			//select id, o_case_id,o_case_date,appellant,reason,phone,game_id,server_id,role_name,admin_uid,create_time,update_time,close_date,status
			$this->DB2
				->select("c.*, g.name as game_name,  au.name admin_name,gi.name as server_name,",false)
				->select("(select group_concat(case_id) from cpl_replies where ref_gov_letter=c.id) as ref_cases",FALSE)
				->from("gov_letters c")
        ->join("games g", "g.game_id=c.game_id", "left")
				->join("servers gi", "gi.server_id=c.server_id", "left")
        ->join("admin_users au", "au.uid=c.admin_uid", "left");


			switch ($this->input->get("action"))
			{
				case "查詢":
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$sort = $this->input->get("sort") ? $this->input->get("sort") : 'id desc';

					$query = $this->DB2->limit(10, $this->input->get("record"))
								->order_by("{$sort}")->get();

					$get = $this->input->get();
					unset($get["record"]);
					$query_string = http_build_query($get);

					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("gov_letter/get_list?".$query_string),
							'total_rows'=> $total_rows,
							'per_page'	=> 10
						));

					$this->g_layout->set("total_rows", $total_rows);
					break;
			}

			$this->DB2->stop_cache();
			$this->DB2->flush_cache();
		}
		else {
			$default_value = array(
				'use_default' => true,
				'start_date' => date('Y-m-d')." 00:00",
			);
			$_GET = $default_value;
		}

		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();


		$this->g_layout
			->add_breadcrumb("查詢")
			->set("query", isset($query) ? $query : false)
			->set("games", $games)
			->add_js_include("fontawesome-all")
			->render();
	}

	function view($id)
	{
		$this->zacl->check("cpl_case", "modify");

		$letter = $this->DB2->select("c.*,  g.name as game_name,  gi.name as server_name,au.name admin_name",false)
		->where("c.id", $id)
		->from("gov_letters c")
		->join("games g", "g.game_id=c.game_id", "left")
		->join("admin_users au", "au.uid=c.admin_uid", "left")
		->join("servers gi", "gi.server_id=c.server_id", "left")
		->get()->row();


		$this->_init_letter_layout()
			->add_breadcrumb("檢視")
			->add_js_include("gov_letter/view")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("letter", $letter)
			->render();
	}




	function delete_letter_json($id)
	{

		$this->DB1
			->where("id", $id)
			->delete("gov_letters");
		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}




	function get_servers_json($game_id){
		$servers = $this->DB2->select("server_id,name")->from("servers")->order_by("server_id desc")->where("game_id",$game_id)->get();
		die(json_success($servers->result()));
	}



}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
