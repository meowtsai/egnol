<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$site = $this->_get_site();
		$bodyonly = $this->input->get_post("bodyonly");
		$hidetitle = $this->input->get_post("hidetitle");
		$search_string = $this->input->get_post("search_string") ? $this->input->get_post("search_string") : "home";

		//$this->load->model("g_bulletins");
        if ($search_string == "home") $this->db->where("title", $search_string);
        elseif (intval($search_string) > 0) $this->db->where("id", $search_string);
        else $this->db->like("title", $search_string);

        $row = $this->db->where("game_id", $site)->where("game_id", $site)->order_by("create_time", "desc")->get("bulletins")->row();

				$theme_id = $row->theme_id;
				if ($theme_id) {
					$row_theme = $this->db->where("id", $theme_id)->get("themes")->row();
				}
				else {
					$row_theme = $this->db->where("id", 1)->get("themes")->row();
				}

        if ($bodyonly) {
            $this->_init_layout()
                ->add_css_link("login")
                ->add_css_link("news")
                ->add_css_link("faq")
                ->add_css_link("jquery.mCustomScrollbar")
			    ->set("bodyonly", $bodyonly)
			    ->set("hidetitle", $hidetitle)
                ->set("row", $row)
								->set("row_theme", $row_theme)
                ->view("faq/index");
        } else {
            $this->_init_layout()
                ->add_css_link("login")
                ->add_css_link("news")
                ->add_css_link("faq")
                ->add_css_link("jquery.mCustomScrollbar")
			    ->set("bodyonly", $bodyonly)
			    ->set("hidetitle", $hidetitle)
                ->set("row", $row)
                ->standard_view();
        }
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
}
