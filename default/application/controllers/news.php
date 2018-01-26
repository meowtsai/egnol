<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$site = $this->_get_site();
		$type = $this->input->get_post("type") ? $this->input->get_post("type") : 0;

		$this->load->model("g_bulletins");
		$this->load->library('pagination');

		$this->pagination->initialize(array(
				'base_url'	=> site_url("news?site={$site}&type={$type}"),
				'total_rows'=> $this->g_bulletins->get_count($site, $type),
				'per_page'	=> 7
		));

        $this->_init_layout()
            ->add_css_link("login")
            ->add_css_link("news")
            ->add_css_link("jquery.mCustomScrollbar")
            ->set("news", $this->g_bulletins->get_list($site, $type, 7, $this->input->get("record")))
            ->standard_view();
	}

	function preview($id)
	{
		$site = $this->_get_site();

		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_preview($site, $id);
		if ($row == false) {die("無此記錄");}

		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("news")
			->set("row", $row)
			->standard_view("news/detail");
	}

	function detail($id)
	{
		$site = $this->_get_site();

		$bodyonly = $this->input->get("bodyonly");

		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_bulletin($site, $id);
		if ($row == false || $row->priority == 0) {die("無此記錄");}

        if ($bodyonly) {
            $this->_init_layout()
                ->add_css_link("login")
                ->add_css_link("news")
                ->set("row", $row)
                ->view("news/detail_bodyonly");
        } else {
            $this->_init_layout()
                ->add_css_link("login")
                ->add_css_link("news")
                ->set("row", $row)
                ->standard_view();
        }
	}

	function get_news_list($site)
	{
		//公告=1 活動=2 系統=3
		$query = $this->db->query("(SELECT id, game_id, title,type,DATE_FORMAT(start_time,'%Y-%m-%d') as start_time FROM bulletins where game_id ='{$site}' and type=1 limit 5)
		union (select id, game_id, title,type,DATE_FORMAT(start_time,'%Y-%m-%d') as start_time from bulletins where game_id ='{$site}' and type=2  limit 5)
		union (select id, game_id, title,type,DATE_FORMAT(start_time,'%Y-%m-%d') as start_time from bulletins where game_id ='{$site}' and type=3 limit 5);");

		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'title' => $row->title,
				'date' =>  date("Y/m/d", strtotime($row->start_time)),
				'category' => ($row->type == 1? "news": ($row->type == 2?"event":"notice") ),
			);
		}

		header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
	}

	function get_news_list_preview($site)
	{

		//公告=1 活動=2 系統=3
		$query = $this->db->query("SELECT id, game_id, title,type,DATE_FORMAT(start_time,'%Y-%m-%d') as start_time, MID(content,instr(content,'src=')+5,84) as hero_image,MID(content,1,100) as preview_content,type FROM bulletins WHERE game_id ='{$site}'");
//{id:"568", title:" 古龍《三少爺的劍》手遊事前登錄展開", start_time:"2018-01-23", hero_image:"https://game.longeplay.com.tw/p/upload/bulletin/d91fbb4bc1096c42bb4aa30b62705b07.jpg", preview_content:"參加《三少爺的劍》事前登錄搶先拿下紫色武功"},
		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'title' => $row->title,
				'date' =>  date("Y/m/d", strtotime($row->start_time)),
				'category' => ($row->type == 1? "news": ($row->type == 2?"event":"notice") ),
				'hero_image' => (strrpos($row->hero_image, ".jpg" ,80)? $row->hero_image: ""),
				'preview_content' => strip_tags($row->preview_content),
			);
		}

		header('Access-Control-Allow-Origin: *');
		die(json_encode($data));
	}

	function get_news_content($news_id)
	{
		if (is_numeric($news_id))
		{
		//公告=1 活動=2 系統=3
		//die($id);
		$query = $this->db->query("SELECT title, DATE_FORMAT(start_time,'%Y-%m-%d') as start_time, content, CASE WHEN type=1 THEN 'news' WHEN type=2 THEN 'event' WHEN type=3 THEN 'notice'	END AS category from bulletins where id='{$news_id}'");

		//$row = $this->db->select("title, DATE_FORMAT(start_time,'%Y-%m-%d') as start_time, content, CASE WHEN type=1 THEN 'news' WHEN type=2 THEN 'event' WHEN type=3 THEN 'notice'	END AS category")->from("bulletins")->where("id", $news_id)->get()->row();
		header('Access-Control-Allow-Origin: *');
		die(json_encode($query->result()[0]));
		}
		else {
			die("no data");
		}
	}
}
