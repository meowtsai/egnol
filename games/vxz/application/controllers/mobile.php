<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile extends MY_Controller
{
	function index()
	{		
		$this->load->model("g_bulletins");		
		$this->load->model("g_pictures");		
        
		$this->_init_layout()
			->add_css_link(array('mobile/normalize','mobile/animate','mobile/lity','mobile/owl.carousel','mobile/owl.theme','mobile/layout'))
            ->set("news", $this->g_bulletins->get_list("vxz", "", 7, $this->input->get("record")))
            ->set("slider_news_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_news_mobile"))
            ->set("slider_about_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_about_mobile"))
            ->set("slider_video_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_video_mobile"))
            ->set("slider_heroes_mobile", $this->g_pictures->get_list_by_category("vxz", "slider_heroes_mobile"))
            ->view("mobile");
	}
}
