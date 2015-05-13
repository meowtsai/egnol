<style>
<!--

#service .item {width:271px; height:165px; display:inline-block; background:url(/p/img/service02.png); margin:10px;}
#service .item .title {color:#78be44; font-size:16px; font-weight:bold; display:block; margin:20px;}
#service .item .content {color:#222; display:block; margin:20px; line-height:20px; font-size:14px;}
-->
</style>
<div id="service" style="margin:0 auto; width:271px;">
	
	<a href="<?=site_url("service/question?game=".$this->input->get("game"))?>">
		<span class="item">
			<span class="title">線上提問</span>
			<span class="content">
				如果您在遊戲內或儲值、轉點過程中遇到問題、BUG，請使用線上提問，我們將盡速回覆您。
			</span>
		</span>
	</a>
	
	<a href="<?=site_url("service/listing")?>">
		<span class="item">
			<span class="title">提問查詢</span>
			<span class="content">
				在此可查詢您曾經提問的回覆紀錄<br>
				目前回覆狀況：<br>
				- 您有 <b><?=$question_cnt?></b>條提問尚未處理。<br>
				- 您有 <b><?=$not_read_cnt?></b>條提問回覆尚未讀取			
			</span>
		</span>
	</a>
	
	</div>

</div>