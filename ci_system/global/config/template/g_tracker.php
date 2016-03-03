<?php
// 網頁追蹤碼
$config = array(
	// Google 分析
    'google_analytics' => array(
		// 龍邑網站共用
		'long_e' =>	" (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

					  ga('create', 'UA-65559425-2', 'auto');
					  ga('send', 'pageview');
					",

		// 時空前線
		'stm' => 	" ga('create', 'UA-65559425-1', 'auto', {'name': 'stmTracker'});
					  ga('stmTracker.send', 'pageview');
					",
        
		// 絕代雙驕
		'r2g' => 	" ga('create', 'UA-65559425-4', 'auto', {'name': 'r2gTracker'});
					  ga('r2gTracker.send', 'pageview');
					",
	),
);
?>