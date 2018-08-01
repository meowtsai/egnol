<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game_faq extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("game_faqs");
    $this->load->config("g_service");

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

		$keyword = $this->input->post('input_keyword', TRUE);
    $type = $this->input->post('type', TRUE);
    $game = $this->input->post('game', TRUE);

    $this->DB2->start_cache();

    $this->DB2->select("f.* ,fg.games,ft.type_ids")
    ->from("faq f")
    ->join("(SELECT faq_id, group_concat(game_id) as games from faq_games group by faq_id) fg","f.id = fg.faq_id","left")
    ->join("(select faq_id, group_concat(type_id) as type_ids from faq_types group by faq_id) ft","f.id= ft.faq_id","left");

    $keyword && $this->DB2->like("f.title", $keyword);



    $type && $this->DB2->where("FIND_IN_SET('{$type}' ,ft.type_ids)");
    $game && $this->DB2->where("FIND_IN_SET('{$game}' ,fg.games)");



    $this->DB2->stop_cache();
  	$total_rows = $this->DB2->count_all_results();

    $query = $this->DB2->limit(10, $this->input->get("record"))
          ->get();
    $this->DB2->flush_cache();
		$this->load->library('pagination');
		$this->pagination->initialize(array(
					'base_url'	=> site_url("game_faq/get_list/{$type}?game_id={$this->game_id}"),
					'total_rows'=> $total_rows,
					'per_page'	=> 10
				));


    $games = $this->DB2->select("game_id,name")->where("is_active","1")->from("games")->get();
    $question_type = $this->config->item('question_type');


		//add keyword search
		//echo '[keyword]'.$keyword;
		$this->g_layout
			->add_breadcrumb("最新消息")
			->add_js_include("bulletin/list")
			->set("question_type", $question_type)
			->set("keyword", $keyword)
      ->set("type", $type)
      ->set("game",$game)
      ->set("games",$games)
			->set("query", $query)
			->render();
	}

	function edit($id)
	{
		$this->zacl->check("bulletin", "modify");

		$this->_chk_game_id();
		$this->_init_layout();

		$faq = $this->DB2->where("f.id", $id)
		->select("f.* ,fg.games,ft.type_ids")
    ->from("faq f")
    ->join("(SELECT faq_id, group_concat(game_id) as games from faq_games group by faq_id) fg","f.id = fg.faq_id","left")
    ->join("(select faq_id, group_concat(type_id) as type_ids from faq_types group by faq_id) ft","f.id= ft.faq_id","left")->get()->row();




		$this->load->library("user_agent");
    $question_type = $this->config->item('question_type');

		$this->g_layout
			->add_breadcrumb("客服快問快答", "game_faq/get_list?game_id={$this->game_id}")
			->add_breadcrumb("修改")
      ->add_js_include("game_faq/form")
			->add_js_include("simplemde.min")
      ->add_css_link('simplemde.min')
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("faq", $faq)
			->set("question_type", $question_type)
			->render("game_faq/form");
	}

	function add()
	{
		$this->zacl->check("bulletin", "modify");

		$this->_chk_game_id();
		$this->_init_layout();

		$this->load->library("user_agent");

		$this->g_layout
			->add_breadcrumb("客服快問快答", "game_faq/get_list?game_id={$this->game_id}")
			->add_breadcrumb("新增")
			->add_js_include("game_faq/form")
      ->add_js_include("simplemde.min")
      ->add_css_link('simplemde.min')
			->add_js_include("jquery-ui-timepicker-addon")
			->set("back_url", $this->agent->is_referral() ? $this->agent->referrer() : "")
			->set("faq", false)
			->set("question_type", $this->config->item('question_type'))
			->render("game_faq/form");
	}

	function modify()
	{
		if ( ! $this->zacl->check_acl("bulletin", "modify")) die(json_failure("沒有權限"));

		$this->load->helper('form');
		$this->load->library(array('form_validation'));
		$this->form_validation->set_rules('faq_title', '標題', 'required|min_length[2]|max_length[75]');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_failure(validation_errors(' ', ' '));
			return;
		}
		else
		{
			$content = str_replace("https://manager.longeplay.com.tw", "https://game.longeplay.com.tw", $this->input->post("faq_content"));



			$data = array(
				'title'	   => $this->input->post("faq_title"),
				'content'  => $content,
				'priority' => $this->input->post("priority"),
			);



			if ($this->input->post("start_time")) {
				$data['start_time'] = $this->input->post("start_time");
			}
			if ($this->input->post("end_time")) {
				$data['end_time'] = $this->input->post("end_time");
			}

      $faq_id = $this->input->post("faq_id");

      if ($faq_id) {
        $this->DB1
          ->where("id", $faq_id)
          ->update("faq", $data);
      }
      else {
        isset($data['start_time']) or $data['start_time'] = now();
        isset($data['end_time']) or $data['end_time'] = '2038-01-01 00:00:00';


        $this->DB1
          ->set("create_time", "now()", false)
          ->set("update_time", "now()", false)
          ->insert("faq", $data);
        $faq_id = $this->DB1->insert_id();
      }

			//更新問題類型 faq_types
			//$array_post_types  = explode("," , $this->input->post("q_type"));
			$array_post_types  =  $this->input->post("q_type");

			$updateSql="INSERT INTO faq_types (faq_id,type_id) VALUES";
			for ($i=0; $i <count($array_post_types) ; $i++) {
				if ($i>0) $updateSql .= ",";
				$updateSql .= "({$faq_id} ,'{$array_post_types[$i]}')";
			}
			$updateSql .= " ON DUPLICATE KEY UPDATE type_id=type_id";

			$this->DB1->query($updateSql);

			//更新選擇遊戲
			$array_games = $this->input->post("target");
			$updateSql="INSERT INTO faq_games (faq_id,game_id) VALUES";
			for ($i=0; $i <count($array_games) ; $i++) {
				if ($i>0) $updateSql .= ",";
				$updateSql .= "({$faq_id} ,'{$array_games[$i]}')";
			}
			$updateSql .= " ON DUPLICATE KEY UPDATE game_id=game_id";

			$this->DB1->query($updateSql);


			$back_url = $this->input->post("back_url") ?
							$this->input->post("back_url") :
							site_url("game_faq/get_list?game_id={$this->input->post("game_id")}");

			echo json_message(array("back_url" => $back_url));
			return;
		}
	}

	function delete($id)
	{
    //echo json_failure($id);

		if ( ! $this->zacl->check_acl("bulletin", "delete")) die(json_failure("沒有權限"));

		$row = $this->DB1->where("id", $id)->from("faq")->get()->row();
    $this->DB1->where('id', $id)->delete('faq');

		if ($this->DB1->affected_rows()) {
			$this->load->model("log_admin_actions");
			$this->log_admin_actions->insert_log($_SESSION["admin_uid"], 'faq', 'delete', "刪除faq #{$id} {$row->title}");
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
