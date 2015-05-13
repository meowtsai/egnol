<? 
	$redirect_url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	
?>
<div style="padding:0 70px 10px;">
	<img src="/p/img/event/zj_1106/event3.png">
	
	<? if ($this->g_user->check_login()):
	?>
	
	<div style="width:585px; height:571px; color:#000; background:url(/p/img/event/zj_1106/event3_step2_b2_bk.png); margin:30px auto 0;">
		<div style="padding:10px 20px; position:relative;">
		
			<div style="position:absolute; top:148px; left:44px;">
				<a name="tab" href="?t=1#tab"><img src="/p/img/event/zj_1106/event3_step2_b2_1_off.png" class="change"></a><a href="?t=2#tab"><img src="/p/img/event/zj_1106/event3_step2_b2_2_off.png" class="change"></a><a href="?t=3#tab"><img src="/p/img/event/zj_1106/event3_step2_b2_3_off.png" class="change"></a><a href="?t=4#tab"><img src="/p/img/event/zj_1106/event3_step2_b2_4_off.png" class="change"></a><a href="?t=5#tab"><img src="/p/img/event/zj_1106/event3_step2_b2_5_off.png" class="change"></a>
			</div>
			
			<div style="position:absolute; top:24px; left:396px; color:#ff0;">
	<? 
		echo "{$this->g_user->euid} <a href='/gate/logout' style='color:#999; font-size:12px;'>(登出)</a><br>";
	?>
			</div>

			<ul style="position:absolute; top:255px; left:74px;">
				<? foreach($query->result() as $row):

			$url = "http://203.75.245.81:3000/sg_user?serv_id=2&acc_id=".$this->g_user->encode($row->uid);
			$re = my_curl($url);
			$json = json_decode($re);
			if ($json->status === 0) {
				$plv = $json->player->level;
				$nick = $json->player->nick;
			}
			else {
				$nick = '';
				$plv = 0;
			}
			if ($plv >= $lv) {
				$key = $this->g_user->uid.'_'.$row->uid;
				if ($this->code->chk_matched($key)) {
	    			$code = $this->code->get_user_code($key);
	    		}
	    		else {
	    			if ($this->code->chk_code_enough()) {
	    				$this->code->match_code($key);
	    				$code = $this->code->get_user_code($key);
	    			}
	    			else {
	    				$code = "已發放完畢";
	    			}
	    		}   	
			}
			else {
				$code = '';
			}
				
				?>
				<li style="line-height:23px; margin-bottom:2x;">
					<span style="width:163px; display:inline-block"><?=$nick.' lv.'.$plv?></span>
					<span style="width:260px; display:inline-block"><?=$code?></span>				
				</li>
				<? endforeach;?>
			</ul>
		
		</div>
	</div>
	
	<? else:?>
	
	<div style="width:586px; height:174px; background:url(/p/img/event/zj_1106/event2_bk_1.png); margin:30px auto 0; position:relative;">
		<div style="padding:10px 20px;">
		
	   <form id="login_form" method="post" action="http://www.long_e.com.tw/gate/login/zj?redirect_url=<?=$redirect_url?>">
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
		<a href="http://www.long_e.com.tw/gate/login/zj?channel=<?=$channel?>&redirect_url=<?=$redirect_url?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>
	
	<? endforeach;?> 
		</div>	  
		
		</div>
	</div>
	
	<? endif;?>
	
	
</div>

<div style="width:989px; height:77px; background:url(/p/img/event/zj_1106/bk_3_event2_4.jpg) -25px top;">
	&nbsp;
</div>
