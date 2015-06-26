<? 
	$redirect_url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	
?>
<div style="padding:0 70px 10px;">
	<img src="/p/img/event/zj_1106/event3.png">
	
	<? if ($this->g_user->check_login()):
			$url = "http://203.75.245.81:3000/sg_user?serv_id=2&acc_id=".$this->g_user->euid;
			$re = my_curl($url);
			$json = json_decode($re);
			if ($json->status === 0) {
				$lv = $json->player->level;
				$nick = $json->player->nick;
			}
	?>
	
	<div style="width:586px; height:174px; color:#eee; background:url(/p/img/event/zj_1106/<?=($record ? 'event2_bk.png' : 'event3_step2_b1_bk.png')?>); margin:30px auto 0;">
		<div style="padding:10px 20px;">
		
	<? 
		//echo "玩家ID：{$this->g_user->euid} <a href='/gate/logout' style='color:#999; font-size:12px;'>(登出)</a><br>";
		if ($record):
		
			if ($this->code->chk_matched($this->g_user->uid)) {
    			$code = $this->code->get_user_code($this->g_user->uid);
    		}
    		else {
    			if ($this->code->chk_code_enough()) {
    				$this->code->match_code($this->g_user->uid);
    				$code = $this->code->get_user_code($this->g_user->uid);
    			}
    			else {
    				echo "<b style='color:#ffff00'>已發放完畢</b>";
    			}
    		}   	
    		if ( ! empty($code)) {
				echo "活動序號：<br><b style='color:#ffff00'>{$code}</b>";
			}
	?>
			
		<? else:?>
			<div style="position:relative;">
				<form id="form" method="post">
					<input type="hidden" name="flag" value="1">
					<input type="text" name="euid" style="width:120px; position:absolute; left:295px; top:60px">
					<a href="javascript:$('form').submit()" style="position:absolute; left:434px; top:48px;">
						<img src="/p/img/event/zj_1106/event3_step2_b1_off.png" class="change">
					</a>
				</form>
			</div>
		
		<? endif;?>
		</div>
	</div>
	
	<? else:?>
	
	<div style="width:586px; height:174px; background:url(/p/img/event/zj_1106/event2_bk_1.png); margin:30px auto 0; position:relative;">
		<div style="padding:10px 20px;">
		
	   <form id="login_form" method="post" action="http://www.longeplay.com.tw/gate/login/zj?redirect_url=<?=$redirect_url?>">
	   	<div style="position:absolute; left:147px; top:14px;">
			<input name="account" type="text" tabindex="1"/><br>
			<input name="pwd" type="password" tabindex="2"/>
		</div>
		<a href="javascript:$('#login_form').submit()" tabindex="3" style="position:absolute; right:70px; top:16px;"><img src="/p/img/event/zj_1106/login_off.png" class="change"></a>
	  </form>
	  
		<div style="position:absolute; top:117px; left:46px;">
	<?php 
		$items = get_channel_items('zj', "../");
		foreach($items as $channel => $name):
	?>
		<a href="http://www.longeplay.com.tw/gate/login/zj?channel=<?=$channel?>&redirect_url=<?=$redirect_url?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>
	
	<? endforeach;?> 
		</div>	  
		
		</div>
	</div>
	
	<? endif;?>
	
	
</div>

<div style="width:989px; height:77px; background:url(/p/img/event/zj_1106/bk_3_event2_4.jpg) -25px top;">
	&nbsp;
</div>
