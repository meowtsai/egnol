<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller
{
	function index()
	{		
		$this->load->model("g_bulletins");		
		$this->load->model("g_pictures");		
        
		$this->_init_layout()
			->add_css_link(array('normalize','animate','lity','flexslider','owl.carousel','owl.theme','layout'))
            ->set("news", $this->g_bulletins->get_list("vxz", "", 7, $this->input->get("record")))
            ->set("slider_news", $this->g_pictures->get_list_by_category("vxz", "slider_news"))
            ->set("slider_about", $this->g_pictures->get_list_by_category("vxz", "slider_about"))
            ->set("slider_video", $this->g_pictures->get_list_by_category("vxz", "slider_video"))
            ->set("slider_heroes", $this->g_pictures->get_list_by_category("vxz", "slider_heroes"))
            ->api_view("index");
	}
}
