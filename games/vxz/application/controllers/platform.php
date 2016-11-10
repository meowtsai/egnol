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
	
	
	// 粉絲團轉址用 Page Tab
	function facebook_page($tab)
	{
		header('content-type:text/html; charset=utf-8');
		
		echo "<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-65559425-2', 'auto');
ga('send', 'pageview');
	
ga('create', 'UA-65559425-4', 'auto', {'name': 'vxzTracker'});
ga('stmTracker.send', 'pageview');
</script>";
		
		if($tab == "tab1")
		{
			// APK 下載
			echo "<script>window.top.location.href='http://cdn.longeplay.com.tw/r2g/apk/LongE_R2G_0705.APK'</script>";
		}
		else if($tab == "tab2")
		{
			// 下載教學
			echo "<script>window.top.location.href='https://www.facebook.com/notes/%E5%B0%8F%E6%9D%8E%E9%A3%9B%E5%88%80%E6%89%8B%E6%A9%9F%E7%89%88/%E5%B0%8F%E6%9D%8E%E9%A3%9B%E5%88%80%E5%B8%B8%E8%A6%8Bfaq/1860632137494013'</script>";
		}
		else if($tab == "tab3")
		{
			// 儲值教學
			echo "<script>window.top.location.href='https://www.facebook.com/notes/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95%E5%84%B2%E5%80%BC%E6%B5%81%E7%A8%8B%E6%95%99%E5%AD%B8/814485948656231'</script>";
		}
	}
	
	function googleeef6880946397d9e() {
		$this->load->view('googleeef6880946397d9e');
	}
}
