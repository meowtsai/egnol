<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$site = $this->_get_site();
		
		$news = $this->db->where("game_id", $site)->order_by("create_time", "desc")->get("news", 10);
		
		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("news")
			->add_css_link("jquery.mCustomScrollbar")
			->set("news", $news)
			->standard_view();
	}
	
	function get_news()
	{
		$site = $this->input->get("site");
		$offset = $this->input->get("o");
		
		$query = $this->db->where("game_id", $site)->order_by("create_time", "desc")->get("news", $offset, 5);
		
		$result = array();
		
		foreach($query->result() as $row)
		{
			$news = array(
				"title"=>$row->title,
				"type"=>$row->type,
				"content"=>$row->content,
				"time"=>$row->create_time,
				"link"=>$row->link
			);
			
			array_push($result, $news);
		}

		die(json_encode($result));
	}
}
