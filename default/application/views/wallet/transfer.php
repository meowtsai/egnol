<style type="text/css">
.field {width:150px; text-align:right; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
</style>

<div style="padding-top:30px;">

                <? if ($channel != 'omg'):?>
                <div class="items" style="width:651px; height:32px; line-height:32px; background-position:0 -630px; margin:0 auto 20px;">
                	<div style="padding:0 100px; font-weight:bold;">
                		您目前龍邑點數剩餘 <b style="color:red"><?=$remain?></b> 點
                	</div>
                </div>   
                <? endif;?>
                
		<form name="form1" id="form1" method="post" action="<?=site_url("wallet/recheck_transfer")?>">
			<input type="hidden" name="channel" value="<?=$channel?>">
			
			<div style="width:500px; margin:0 auto;">
			<ul>
				<li class="line_row">
					<span class="field">請選擇儲值的遊戲</span>
					<span class="line_field">
						<select name="game" class="required">
							<option value="">--請選擇--</option>
							<? foreach($games->result() as $row):
									//if ( ! IN_OFFICE && strpos($row->tags, "手遊") !== false) continue;
							?>
							<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?> <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
							<? endforeach;?>
						</select>						
					</span>
				</li>
				<li class="line_row">
					<span class="field">請選擇儲值的伺服器</span>
					<span class="line_field">
	                    <select name="server" class="required">
	                      <option value="">--請先選擇遊戲--</option>
	                    </select>
	                    
	                    <select id="server_pool" style="display:none;">
							<? foreach($servers->result() as $row):
								if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
							<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
							<? endforeach;?>
						</select>						
					</span>
				</li>
				<li class="line_row">
					<span class="field">請選擇儲值金額</span>
					<span class="line_field amount_block">
	                      <? $i=1; foreach($product_point as $price):?>
	                      <label style="display:block;">
	                      	<input type="radio" name="price" id="price" class="required"  value="<?=$price?>" <?=($i++==1 ? 'checked="checked"' : '')?>>
	                      	<?=$price?>
	                      </label>
	                      <? endforeach;?>
	                    
	                    <label id="gain_tip"></label>					
					</span>
				</li>
				<li class="line_row" style="height:25px;">
				</li>
				<li class="line_row" style="text-align:center; padding-top:13px;">
					<a href="javascript:;" onclick="$('#form1').submit();">
						<img src="/p/img/payment/btn.png">
					</a>
				</li>
			</ul>
			
			<? $this->load->view("wallet/_note")?>			
			
			</div>
		</form>

</div>