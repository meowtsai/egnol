<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller {
	
	var $bulletin_cnt = 6;
	
	function index()
	{		
		$this->_init_layout();
		$this->load->model(array("g_bulletins", "g_pictures", "g_guides"));
		$this->load->helper("g_picture");
		
		$this->g_layout
			->set("pic_icon", $this->g_pictures->get_list($this->game, 33))	//icon
			->add_js_include("platform/index")
			->render();		
	}
	
	function get_bulletin($category_id=0) 
	{		
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$bulletins = $this->g_bulletins->get_list($this->game, $category_id, $this->bulletin_cnt);
		
		$str = '';		
		foreach($bulletins->result() as $row) {
			if ($row->category=='公告') $icon = "icon1";
			elseif ($row->category=='系統') $icon = "icon2";
			elseif ($row->category=='活動') $icon = "icon3";
			else $icon = $row->category;			
			$str .= 
			'<div class="news">
			  <a href="'.site_url("bulletin/detail/{$row->id}").'">
				<img src="/p/img/'.$icon.'.gif" width="39" height="13" />
				<span>'.mb_strimwidth($row->bulletin_title, 0, 60, '...', 'utf-8').'</span>
			  </a>
			  </div>
			</li>';  
		}
		die($str);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */