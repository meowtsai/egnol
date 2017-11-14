<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("bulletins");
		$this->zacl->check_login(true);
		$this->zacl->check("bulletin", "read");

		if ($this->zacl->check_acl("all_game", "all") == false) {
			if ($this->game_id) $this->zacl->check($this->game_id, "read");
		}
	}

	function index()
	{
		$this->_init_layout()->render();
	}

	function get_list($type=0)
	{
		$this->_chk_game_id();
		$this->_init_layout();

		$this->load->library('pagination');
		$this->pagination->initialize(array(
					'base_url'	=> site_url("bulletin/get_list/{$type}?game_id={$this->game_id}"),
					'total_rows'=> $this->bulletins->get_bulletin_count($this->game_id, $type),
					'per_page'	=> 10
				));

		$this->g_layout
			->add_breadcrumb("最新消息")
			->add_js_include("bulletin/list")
			->set("bulletin_type", $type)
			->set("query", $this->bulletins->get_bulletin_data($this->game_id, $type, 10, $this->input->get("record")))
			->set("bulletin_type_list", $this->config->item('bulletin_type'))
			->render();
	}

	function edit($id)
	{
		$this->zacl->check("bulletin", "modify");

		$this->_chk_game_id();
		$this->_init_layout();
		$bulletin = $this->DB2->where("id", $id)->get("bulletins")->row();

		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("最新消息", "bulletin/get_list?game_id={$this->game_id}")
			->add_breadcrumb("修改")
			->add_js_include("bulletin/form")
			->add_js_include("ckeditor/ckeditor")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("bulletin", $bulletin)
			->set("bulletin_type_list", $this->config->item('bulletin_type'))
			->set("server_list", $this->DB2->where("game_id", $this->game_id)->order_by("server_id")->get("servers"))
			->render("bulletin/form");
	}

	function add()
	{
		$this->zacl->check("bulletin", "modify");

		$this->_chk_game_id();
		$this->_init_layout();

		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("最新消息", "bulletin/get_list?game_id={$this->game_id}")
			->add_breadcrumb("新增")
			->add_js_include("bulletin/form")
			->add_js_include("ckeditor/ckeditor")
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("bulletin", false)
			->set("bulletin_type_list", $this->config->item('bulletin_type'))
			->set("server_list", $this->DB2->where("game_id", $this->game_id)->order_by("server_id")->get("servers"))
			->render("bulletin/form");
	}

	function modify()
	{
		if ( ! $this->zacl->check_acl("bulletin", "modify")) die(json_failure("沒有權限"));

		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('bulletin_title', '標題', 'required|min_length[2]');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{
			$content = str_replace("http://manager.longeplay.com.tw", "https://game.longeplay.com.tw", $this->input->post("bulletin_content"));

			if ($this->input->post("bulletin_type")==99) $content = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($content))));

			$data = array(
				'title'	   => $this->input->post("bulletin_title"),
				'game_id'  => $this->input->post("game_id") ? $this->input->post("game_id") : "",
				'type'	   => $this->input->post("bulletin_type"),
				'content'  => $content,
				'priority' => $this->input->post("priority"),
				'target'   => $this->input->post("target") ? implode(",", $this->input->post("target"))."," : ",",
			);


			if ($this->input->post("start_time")) {
				$data['start_time'] = $this->input->post("start_time");
			}
			if ($this->input->post("end_time")) {
				$data['end_time'] = $this->input->post("end_time");
			}

			if ($bulletin_id = $this->input->post("bulletin_id")) { //修改
				$this->bulletins->update_bulletin($bulletin_id, $data);
			}
			else { //新增
				$insert_id = $this->bulletins->insert_bulletin($data);
			}

			if ($this->input->post("bulletin_type")==99) {
				$filePath = "p/file/";
				$filePath .= ($this->input->post("bulletin_title")) ? $this->input->post("bulletin_title") : "";
				$filePath .= ($this->input->post("game_id")) ? "_".$this->input->post("game_id") : "";
				$filePath .= ".txt";

				$file = fopen($filePath, "w+");

				fwrite($file, $content);

				fclose($file);
			}

			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("picture/get_list?game_id={$this->input->post("game_id")}");

			echo json_message(array("back_url" => $back_url));
			return;
		}
	}

	function delete($id)
	{
		if ( ! $this->zacl->check_acl("bulletin", "delete")) die(json_failure("沒有權限"));

		$row = $this->bulletins->get_bulletin($id);

		if ($this->bulletins->delete_bulletin($id)) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'bulletin', 'delete', "刪除文章 #{$id} {$row->title}");
			echo json_success();
		}
		else echo json_failure();
	}

	function set_priority($id, $val)
	{
		if ( ! $this->zacl->check_acl("bulletin", "modify")) die(json_failure("沒有權限"));

		$this->DB1->where("id", $id)->set("priority", $val)->update("bulletins");
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure("無變更");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
