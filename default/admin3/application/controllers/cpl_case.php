<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cpl_case extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->zacl->check_login(true);
		$this->zacl->check("cpl_case", "read");

		$this->load->config("cpl_case");
	}

	function _init_cpl_case_layout()
	{
		return $this->_init_layout()->add_breadcrumb("消保&公函", "cpl_case");
	}

	function todo()
	{
		$_GET['action'] = '查詢';
		$_GET['todo'] = '1';

		header("refresh:30");
		$this->router->method = 'get_list';
		$this->get_list();
	}

	function my()
	{
		$_GET['action'] = '查詢';
		$_GET['allocate_status'] = '1';
		$_GET['allocate_auid'] = $this->input->get("allocate_auid") ? $this->input->get("allocate_auid") : $_SESSION['admin_uid'];

		$this->router->method = 'get_list';
		$this->get_list();
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

		$this->_init_cpl_case_layout()
			->add_breadcrumb("新增消保案件")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("cpl_case/form")
			->set("games", $games)
			->set("case", false)
			->render("cpl_case/form");
	}

	function edit($id)
	{
		$this->zacl->check("cpl_case", "modify");
		$games = $this->DB2->from("games")->order_by("is_active", "desc")->get();
		$admin_users = $this->DB2->select("u.*, ar.role_desc")
            ->from('admin_users u')
			->join("admin_roles ar", "ar.role=u.role")->order_by("u.role")->get();

		$query = $this->DB2->from("cpl_cases cc")->where("id", $id)->get();
		if ($query->num_rows() == 0) die('無此單號');

		$case = $query->row();

		$this->_init_cpl_case_layout()
			->add_breadcrumb("編輯消保案件")
			->add_js_include("cpl_case/form")
			->set("games", $games)
			->set("admin_users", $admin_users)
			->set("case", $case)
			->render("cpl_case/form");
	}

	function modify_json()
	{

		$case_id = $this->input->post("case_id");


		$game_id = $this->input->post("game_id");
		$server_id = $this->input->post("server_id");
		$role_name = $this->input->post("role_name");

		if ($game_id=="" || $server_id==""){
			//.$this->DB2->last_query()
				die(json_failure("遊戲和伺服器必填喔!"));
		}

		$data = array(
			"o_case_id" => $this->input->post("o_case_id"),
			"o_case_date" => $this->input->post("o_case_date"),
			"deadline" => $this->input->post("deadline"),
			"appellant" => $this->input->post("appellant"),
			"reason" => $this->input->post("reason"),
			"phone" => $this->input->post("phone"),
			"game_id" => $game_id,
			"server_id" => $server_id,
			"role_name" => $role_name,
			'admin_uid' => $_SESSION['admin_uid'],
		);
		//die(json_failure(print_r($data)));
		$check_dup = null;
		$this->DB2->select("count(*) as cnt")->from("cpl_cases")->where("o_case_id",$this->input->post("o_case_id"));
		if ($case_id)
		{
			$check_dup = $this->DB2->where("id<>{$case_id}", null, false)->get();

		}
		else {
			$check_dup = $this->DB2->get();
		}
		$cnt =  $check_dup->result()[0]->cnt;
		if ($cnt>0){
			//.$this->DB2->last_query()
				die(json_failure("輸入的[發文字號]已經存在了喔!"));
		}


		if ($case_id) {
			$r = $this->DB1
				->where("id", $case_id)
				->update("cpl_cases", $data);
			if (!$r){
					die(json_failure($this->DB1->_error_message()));
			}
		}
		else {
			$r = $this->DB1
				->insert("cpl_cases", $data);
			if ($r){
					$case_id = $this->DB1->insert_id();
			}
			else {
				die(json_failure($this->DB1->_error_message()));
			}
		}

		die(json_message(array("redirect_url"=> base_url("cpl_case/view/".$case_id), "id"=>$case_id), true));
	}

	function get_list()
	{
		$this->_init_cpl_case_layout();

		if ($this->input->get("action"))
		{
			header("Cache-Control: private");

			$this->DB2->start_cache();

			$this->input->get("o_case_id") && $this->DB2->where("c.o_case_id", $this->input->get("o_case_id"));
			$this->input->get("phone") && $this->DB2->where("c.phone", $this->input->get("phone"));
			$this->input->get("appellant") && $this->DB2->where("c.appellant", $this->input->get("appellant"));
			$this->input->get("game") && $this->DB2->where("c.game_id", $this->input->get("game"));
			$this->input->get("status") && $this->DB2->where("c.status", $this->input->get("status"));

			//select id, o_case_id,o_case_date,appellant,reason,phone,game_id,server_id,role_name,admin_uid,create_time,update_time,close_date,status
			$this->DB2
				->select("c.*,  deadline,  g.name as game_name,  au.name admin_name,gi.name as server_name,",false)
				->select("(select max(contact_time) from `cpl_replies` where case_id=c.id) as last_replied",FALSE)
				->select("(select count(*) from cpl_attachments where case_id=c.id) as has_attached",FALSE)
				->from("cpl_cases c")
        ->join("games g", "g.game_id=c.game_id", "left")
				->join("servers gi", "gi.server_id=c.server_id", "left")
        ->join("admin_users au", "au.uid=c.admin_uid", "left");


			switch ($this->input->get("action"))
			{
				case "查詢":

					if (!isset($_SESSION['page_size'])) {
						$_SESSION['page_size']=10;
					}
					if ($this->input->get("page_size")) {
						$page_size = $this->input->get("page_size");
						$_SESSION['page_size'] = $page_size;
					}
					$this->DB2->stop_cache();

					$total_rows = $this->DB2->count_all_results();
					$sort = $this->input->get("sort") ? $this->input->get("sort") : 'id desc';

					$query = $this->DB2->limit($_SESSION['page_size'], $this->input->get("record"))
								->order_by("{$sort}")->get();

					$get = $this->input->get();
					unset($get["record"]);
					$query_string = http_build_query($get);



					$this->load->library('pagination');
					$this->pagination->initialize(array(
							'base_url'	=> site_url("cpl_case/get_list?".$query_string),
							'total_rows'=> $total_rows,
							'per_page'	=> $_SESSION['page_size']
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
			->add_js_include("cpl_case/get_list")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("fontawesome-all")
			->render();
	}

	function view($id)
	{
		$this->zacl->check("cpl_case", "modify");

		$case = $this->DB2->select("c.*, deadline,  g.name as game_name,  gi.name as server_name,au.name admin_name",false)
		->where("c.id", $id)
		->from("cpl_cases c")
		->join("games g", "g.game_id=c.game_id", "left")
		->join("admin_users au", "au.uid=c.admin_uid", "left")
		->join("servers gi", "gi.server_id=c.server_id", "left")
		->get()->row();
//->select("(select group_concat(ref_id) from case_reference where case_id=c.id) as ref_cases",FALSE)
		$ref_cases = $this->DB2->select("c.o_case_id,cr.ref_id")
			->from("case_reference cr")
			->join("cpl_cases c", "c.id=cr.ref_id", "left")
			->where("cr.case_id", $id)->get();

			$attachments = $this->DB2->select("ca.*")
				->from("cpl_attachments ca")
				->where("ca.case_id", $id)->get();



		$ref_case_list = $this->DB2->select("id,o_case_id,o_case_date,appellant")
		 ->where("game_id=(select game_id from cpl_cases where id={$id})", null)
		 ->from("cpl_cases c")
		 ->where("id<>{$id}",null,false)
		 ->where("id not in(select ref_id from case_reference where case_id={$id})", null)
		 ->order_by("c.id", "desc")->get();


		$replies = $this->DB2
			->select("tr.*, au.name as admin_uname")
			->from("cpl_replies tr")
			->join("admin_users au", "au.uid=tr.admin_uid", "left")
			->where("case_id", $id)->order_by("tr.id", "asc")->get();

		$mediations = $this->DB2
			->select("cm.*, au.name as admin_uname")
			->from("cpl_mediations cm")
			->join("admin_users au", "au.uid=cm.admin_uid", "left")
			->where("case_id", $id)->order_by("cm.id", "asc")->get();


		$this->_init_cpl_case_layout()
			->add_breadcrumb("檢視")
			->add_js_include("cpl_case/view")
			->add_js_include("jquery-ui-timepicker-addon")
			->add_js_include("fontawesome-all")
			->set("case", $case)
			->set("ref_cases", $ref_cases)
			->set("attachments", $attachments)
			->set("ref_case_list", $ref_case_list)
			->set("replies", $replies)
			->set("mediations", $mediations)
			->render();
	}

	function edit_reply($id)
	{
		$row = $this->DB2->where("id", $id)->from("cpl_replies")->get()->row();

		$letters = $this->DB2->select("id,o_letter_id,o_letter_date")
		->where("game_id=(select game_id from cpl_cases where id={$row->case_id})", null)
		->from("gov_letters")
		->get();

		$this->_init_cpl_case_layout()
			->add_breadcrumb("編輯回覆")
			->add_js_include("cpl_case/view")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("row", $row)
			->set("letters", $letters)
			->render();
	}
	function edit_mediation($id)
	{
		$row = $this->DB2->where("id", $id)->from("cpl_mediations")->get()->row();

		$this->_init_cpl_case_layout()
			->add_breadcrumb("編輯調解會紀錄")
			->add_js_include("cpl_case/view")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("row", $row)
			->render();
	}



	function modify_reply_json()
	{
		$case_id = $this->input->post("case_id");
		$id = $this->input->post("reply_id");

		$data = array(
			"case_id" => $case_id,
			'note' => nl2br($this->input->post("note")),
			'contact_time' => $this->input->post("contact_time"),
			'admin_uid' => $_SESSION['admin_uid'],
		);

		if ($id) {
			$this->DB1
				->where("id", $id)
				->update("cpl_replies", $data);
		}
		else {
			$this->DB1
				->insert("cpl_replies", $data);
		}
		die(json_message(array("redirect_url"=> base_url("cpl_case/view/".$case_id), "id"=>$case_id), true));
	}


function add_attachment_json(){
	$case_id = $this->input->post("case_id");
	$attach_title = $this->input->post("attach_title");
	$data = array(
		"case_id" => $case_id,
		'title' => $attach_title,
	);

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

	$data["pic_path"] = $file_path;

	$this->DB1
		->insert("cpl_attachments", $data);

	die(json_message(array("redirect_url"=> base_url("cpl_case/view/".$case_id), "id"=>$case_id), true));


}
function add_ref_case_json(){
	$case_id = $this->input->post("case_id");
	$ref_id = $this->input->post("ref_case_list");

	$data = array(
		"case_id" => $case_id,
		'ref_id' => $ref_id,
	);
	$this->DB1
		->insert("case_reference", $data);

	$data = array(
		"case_id" => $ref_id,
		'ref_id' => $case_id,
	);
	$this->DB1
		->insert("case_reference", $data);

	die(json_message(array("redirect_url"=> base_url("cpl_case/view/".$case_id), "id"=>$case_id), true));

}
	function delete_case_json($id)
	{
		$this->DB1
			->where("case_id", $id)
			->delete("cpl_replies");
		$this->DB1
			->where("case_id", $id)
			->delete("cpl_mediations");
		$this->DB1
			->where("id", $id)
			->delete("cpl_cases");
		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}

	function delete_reply_json($id)
	{
		$this->DB1
			->where("id", $id)
			->delete("cpl_replies");

		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}

	function delete_mediation_json($id)
	{
		$this->DB1
			->where("id", $id)
			->delete("cpl_mediations");

		if ($this->DB1->affected_rows() > 0) echo json_success();
		else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());
	}


	function update_note_json()
	{
		$this->DB1->where("id", $this->input->post("ticket_id"))
			->update("tickets", array('note' => $this->input->post("note")));

		die(json_success());
	}

	function allocate_json()
	{
		$result = $this->input->post("allocate_result").date("Y-m-d H:i")." - ".$_SESSION['admin_name']."轉交：".$this->input->post("result")."<br>";
		$this->DB1->where("id", $this->input->post("ticket_id"))
			->set('allocate_date', 'NOW()', false)
			->set('allocate_result', $result)
			->update("tickets", array('allocate_admin_uid' => $this->input->post("allocate_admin_uid")));

		die(json_success());
	}

	function move_case($id)
	{
		$status = $this->input->get_post("status", TRUE);
		$close_date = $this->input->get_post("close_date", TRUE);
		if($status=="4")
		{
			$this->DB1->set("status", $status)->set("close_date", $close_date)->where("id", $id)->update("cpl_cases");
		}
		else {
			$this->DB1->set("status", $status)->where("id", $id)->update("cpl_cases");
		}

		if ($this->DB1->affected_rows() > 0) {
			die(json_success($id));
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}
	function move_mediation($id)
	{
		$status = $this->input->get_post("status", TRUE);

		$this->DB1->set("status", $status)->where("id", $id)->update("cpl_mediations");
		if ($this->DB1->affected_rows() > 0) {
			die(json_success());
		}
		else {
			die(json_failure("問題尚未處理"));
		}
	}


	function get_servers_json($game_id){
		$servers = $this->DB2->select("server_id,name")->from("servers")->order_by("server_id desc")->where("game_id",$game_id)->get();
		die(json_success($servers->result()));
	}



function modify_mediation_json()
{
	$case_id = $this->input->post("case_id");
	$id = $this->input->post("mediation_id");

	$data = array(
		"case_id" => $case_id,
		"o_case_id" => $this->input->post("o_case_id"),
		"o_case_date" => $this->input->post("o_case_date"),
		"req_date" => $this->input->post("req_date"),
		"req_place" => $this->input->post("req_place"),
		"o_staff" => $this->input->post("o_staff"),
		"o_contact" => $this->input->post("o_contact"),
		"o_phone" => $this->input->post("o_phone"),
		"representative" => $this->input->post("representative"),
		"close_date" => $this->input->post("close_date"),
		'note' => nl2br($this->input->post("note")),
		'admin_uid' => $_SESSION['admin_uid'],
	);

	if ($id) {

		$this->DB1
			->where("id", $id)
			->update("cpl_mediations", $data);
	}
	else {
		$this->DB1
			->insert("cpl_mediations", $data);
	}

	//die(json_success());
	die(json_message(array("redirect_url"=> base_url("cpl_case/view/".$case_id), "id"=>$case_id), true));
}

function remove_case_reference($case_id,$ref_id)
{
	//echo json_failure("case_id=".$case_id.",ref_id=".$ref_id);
		$this->DB1
		->where("case_id", $case_id)
		->where("ref_id", $ref_id)
		->delete("case_reference");

		$this->DB1
		->where("case_id",$ref_id )
		->where("ref_id", $case_id)
		->delete("case_reference");

	if ($this->DB1->affected_rows() > 0) echo json_success();
	else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());


}

function remove_case_attachment($case_id,$attach_id)
{
		$this->DB1
		->where("case_id", $case_id)
		->where("id", $attach_id)
		->delete("cpl_attachments");

	if ($this->DB1->affected_rows() > 0) echo json_success();
	else echo json_failure("資料庫刪除失敗或沒有權限".$this->DB1->last_query());


}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
