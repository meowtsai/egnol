<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller {
	
	var $bulletin_cnt = 8;
	
	function index()
	{		
		$this->_init_layout();
		$this->load->model(array("g_bulletins", "g_pictures", "g_guides"));

		$this->load->helper("g_picture");
		$this->g_layout
			->set("pic_slide", $this->g_pictures->get_list($this->game, 27))
			->set("pic_icon", $this->g_pictures->get_list($this->game, 28));
		
		$this->g_layout
			->add_js_include("platform/index")
			->add_css_link('index')
			->render();		
	}
	
	function register_form()
	{
		$this->_init_layout();
		$this->g_layout->view();
	}
	
	function register_json()
	{
		return $this->g_user->register_json('my');
	}
	
	function get_bulletin($category_id=0) 
	{		
		$this->_init_layout();
		$this->load->model("g_bulletins");
		$bulletins = $this->g_bulletins->get_list($this->game, $category_id, $this->bulletin_cnt);		
		$str = '';
		foreach($bulletins->result() as $row) {
			$str .= 
			'<li>
				<span class="text3">
					<a href="'.site_url("bulletin/detail/{$row->id}").'"> <font >'.mb_strimwidth(strip_tags($row->bulletin_title), 0, 50, '...', 'utf-8').'</font></a>					
				</span>
				<span class="text2" style="float:right;">'.date("Y-m-d", strtotime($row->publish_date)).'</span>
			</li>';  
		}
		die($str);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */