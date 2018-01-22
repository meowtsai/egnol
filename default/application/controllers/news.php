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
		$query = $this->db->query("SELECT c1.id, c1.title, c1.game_id,c1.type,c1.start_time, c1.is_active
			FROM bulletins c1
			LEFT OUTER JOIN bulletins c2
			  ON (c1.type  = c2.type  AND c1.start_time < c2.start_time)
			where c1.game_id ='{$site}' and c2.game_id='{$site}' and c1.type <>'99' AND c1.start_time < now()
			GROUP BY c1.id
			HAVING COUNT(*) < 5;");

		$data = array();
		foreach($query->result() as $row) {
			$data[] = array(
				'id' => $row->id,
				'title' => $row->title,
				'date' =>  date("Y/m/d", strtotime($row->create_time)),
				'category' => ($row->type == 1? "news": ($row->type == 2?"event":"notice") ),
			);
		}


		die(json_encode($data));
	}

	function get_news_content($news_id)
	{
		//公告=1 活動=2 系統=3
		$row = $this->db->from("bulletins")->where("id", $news_id)->get()->row();
		die(json_encode($row));
	}
}
