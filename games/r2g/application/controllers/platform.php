<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Platform extends MY_Controller
{
	function index()
	{
		$this->_init_layout()->standard_view("index");
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
	
ga('create', 'UA-65559425-4', 'auto', {'name': 'r2gTracker'});
ga('stmTracker.send', 'pageview');
</script>";
		
		if($tab == "tab1")
		{
			// APK 下載
			echo "<script>window.top.location.href='https://app.appsflyer.com/com.longe.NetEase.R2Gcb-officalapk?pid=webdownload&af_r=http://cdn.longeplay.com.tw/r2g/apk/LongE_R2G.APK'</script>";
		}
		else if($tab == "tab2")
		{
			// 下載教學
			echo "<script>window.top.location.href='https://www.facebook.com/notes/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95faq/806632942774865'</script>";
		}
		else if($tab == "tab3")
		{
			// 儲值教學
			echo "<script>window.top.location.href='https://www.facebook.com/notes/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95/%E7%B5%95%E4%BB%A3%E9%9B%99%E9%A9%95%E5%84%B2%E5%80%BC%E6%B5%81%E7%A8%8B%E6%95%99%E5%AD%B8/814485948656231'</script>";
		}
	}
}
