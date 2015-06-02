			<div id="login" class="<?= $this->g_user->check_login() ? 'logined' : ''?> box">
				<div class="bd">
				
<? if ($this->g_user->check_login()):?>

	<div style="color:#97f">-您目前登入帳號-</div>
	<div><?=$this->g_user->display_account();?>
	</div>
	<div style="margin:10px 0 12px; height:27px;">
		<? if ( ! $this->g_user->check_extra_account($this->g_user->account)):?>
		<a href="<?=site_url("member/update_member_data")?>" class="btn01 items">修改資料</a><a href="<?=site_url("member/change_password")?>" class="btn01 items">變更密碼</a>
		<? else:?>
		<a href="<?=site_url("member/bind_account")?>" class="btn04 items"></a>
		<? endif;?>
	</div>
	<div style="color:#ee0">-最近登入過的遊戲伺服器-</div>
	<div style="height:57px">
	<? if ($recent_server->num_rows() > 0):?>		
		<? foreach($recent_server->result() as $row):
			$game_server = "{$row->game_name}：{$row->server_name}";
			$game_server_cut = mb_strimwidth($game_server, 0, 26, '...', 'utf-8')
		?>
		<div style="line-height:19px;">
			<a href="/play_game?sid=<?=$row->id?>" title="<?=$game_server?>"><?=$game_server_cut?></a>
		</div>
		<? endforeach;?>
	<? else: echo "<span style='color:#ddd; font-size:13px;'>尚未登入過遊戲</span>"?>
	<? endif;?>
	</div>
	<div style="margin:12px 0 10px;">
		<a href="<?=site_url("payment")?>" class="btn02 items">儲值購點</a><a href="<?=site_url("wallet/transfer")?>" class="btn02 items">遊戲轉點</a>
	</div>
	<div><a href="<?=site_url("gate/logout")?>" class="btn03 items">帳號登出</a></div>		
	

<? else:?>				
					<form id="login_form" action="<?=site_url("gate/login?redirect_url=".urlencode(current_url()))?>" method="post">
						<ul>
							<li>登入 <span class="input_bg"><input type="text" name="account"></span></li>
							<li>密碼 <span class="input_bg"><input type="password" name="pwd"></span></li>
							<li>
								<a href="javascript:;" onclick="$('#login_form').submit()"><span class="login_btn items"></span></a>
								<a href="<?=site_url("register")?>"><span class="register_btn items"></span></a>
							</li>
						</ul>
				  	</form>
					<div class="channel_login">
	<?php 
		$items = get_channel_items("long_e", "../");
		foreach($items as $channel => $name):
	?>
		<a href="/gate/login/long_e?channel=<?=$channel?>"><img src="/img/login/<?=$channel?>.png" width="36" title="<?=$name?> 帳號登入"/></a>
	<? endforeach;?> 
					</div>
<? endif;?>

				</div>
			</div>