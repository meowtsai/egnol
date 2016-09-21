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
		
		//$news = $this->db->where("game_id", $site)->order_by("create_time", "desc")->get("news", 10);
		if ($this->input->get_post("format")=="txt") {
            echo "一些冠冕堂皇的說詞
            更多冠冕堂皇的說詞";
        } else {
            $this->_init_layout()
                ->add_css_link("login")
                ->add_css_link("news")
                ->add_css_link("jquery.mCustomScrollbar")
                ->set("news", $this->g_bulletins->get_list($site, $type, 7, $this->input->get("record")))
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
        
		$this->load->model("g_bulletins");
		$row = $this->g_bulletins->get_bulletin($site, $id);
		if ($row == false || $row->priority == 0) {die("無此記錄");}
		
		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("news")
			->set("row", $row)
			->standard_view();
	}
}
