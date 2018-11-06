<?
    $this->load->library("Parsedown");
    $Parsedown = new Parsedown();
?>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$longe_url?>service_quick?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="" title="提問查詢" rel="v:url" property="v:title">提問查詢</a>
		</div>
		<div class="login-form">
<? if ($question):?>
			<table class="member_info">
				<tr>
					<th>案件編號　|</th>
					<td style="color:#1C319A"><?=$question->id?></td>
				</tr>
				<tr>
					<th>提問類型　|</th>
					<td style="color:#1C319A">
					<?
						$type = $this->config->item("question_type");
						echo $type[$question->type];
					?>
					</td>
				</tr>
				<tr>
					<th>遊戲名稱　|</th>
					<td style="color:#1C319A"><?=$question->game_name?></td>
				</tr>
				<tr>
					<th>伺服器　|</th>
					<td style="color:#1C319A"><?=$question->server_name?></td>
				</tr>
				<tr>
					<th>角色名稱　|</th>
					<td style="color:#1C319A"><?=$question->character_name?></td>
				</tr>
				<tr>
					<th>E-MAIL　|</th>
					<td style="color:#1C319A"><?=$question->email?></td>
				</tr>
				<tr>
					<th>手機號碼　|</th>
					<td style="color:#1C319A"><?=$question->phone?></td>
				</tr>
				<tr>
					<th>提問描述　|</th>
					<td style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;color:#1C319A" >
                        <?//=$question->content?>
                        <? echo $Parsedown->text($question->content);?>
                    </td>
				</tr>
				<tr>
					<th>截圖　|</th>
					<td>
		        	<? if ($question->pic_path1):?>
					<span>
						<a href="<?=$question->pic_path1?>" target="_blank" style="color:blue;font-size:9pt">
							附圖1
						</a>
					</span>
					<? endif;?>
					<? if ($question->pic_path2):?>
					<span>
						<a href="<?=$question->pic_path2?>" target="_blank" style="color:blue;font-size:9pt">
							/ 附圖2
						</a>
					</span>
					<? endif;?>
					<? if ($question->pic_path3):?>
					<span>
						<a href="<?=$question->pic_path3?>" target="_blank" style="color:blue;font-size:9pt">
							/ 附圖3
						</a>
					</span>
					<? endif;?>

          <? $last_reply_id =0;
            $array_pic = array();
          ?>
          <?if ($pic_plus->num_rows() > 0):
            $p_no = 3;
            //print_r($pic_plus->result()) ;
            foreach($pic_plus->result() as $row):
              if ($row->reply_id==0):
          ?>
              <span>
                <a href="<?=$row->pic_path?>" target="_blank" style="color:blue;font-size:9pt">
                  / 附圖<?=++$p_no?>
                </a>
              </span>
            <?
              else:
                $cur_reply_id = $row->reply_id;
                if ($cur_reply_id==$last_reply_id):
                  array_push($array_pic[$cur_reply_id],$row->pic_path);
                else:
                  $array_pic[$cur_reply_id] = [$row->pic_path];
                endif;
                $last_reply_id = $cur_reply_id;

                // $picObj->reply_id = "John";
                // $picObj->age = 30;
                // $picObj->city = "New York";
                //
                // $myJSON = json_encode($myObj);
                //
                // echo $myJSON;
              endif;
              //print_r($array_pic);
            endforeach;
          endif;?>


					</td>
				</tr>
				<tr>
					<th>處理狀態　|</th>
					<td>
					<?
          echo "hi".$replies->num_rows();
          if ($replies->num_rows() == 0) echo '目前尚在處理中';
					$no = $replies->num_rows();
					foreach($replies->result() as $row):?>
					<table class="reply <?=($row->is_official ? 'official' : '') ?>" style="position:relative;width:100%">
						<tr>
							<td style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;">
								<?//=($row->is_official ? '《客服回覆》' : '《再次提問》') ?>
								<div style="background-color: rgba(255, 255, 255, 0.3);border-radius: 5px; padding:5px; word-wrap: break-word; <?=($row->is_official == '1'?"color:#68400A":"color:#1C319A" ) ?>">
								    <?//=$row->content?>
                    <? echo $Parsedown->text($row->content);?>
                    <? if ($array_pic[$row->id]): ?>
                    <?for($count = 0; $count < sizeof($array_pic[$row->id]);$count++):?>
                      <span>
                        <a href="<?=$array_pic[$row->id][$count]?>" target="_blank" style="color:blue;font-size:9pt">
                          <附圖>
                        </a>
                      </span>
                    <?endfor;?>
                    <?endif;?>



								</div>
								<? if ($row->is_official == '1' && $question->status <> '4' && $no == 1):?>
								<div style="float:right; padding:0 0 20px 20px;">
									<a href="javascript:;" url="<?=site_url("service_quick/close_question/{$question->id}")?>" site="<?=$site?>" class="close_question">[我沒問題了]</a>
									<a href="#go_to_reply">[我還有疑問]</a>
								</div>
								<? endif;?>
								<div style="float:right; font-size: 8px; color: #D8D8D8; font-style: italic;">
								<?=date('Y-m-d H:i', strtotime($row->create_time))?>
								</div>
							</td>
						</tr>
					</table>
					<?
						$no--;
					endforeach;?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;">
					<? if ($question->status <> '4' && $replies->num_rows() > -1):?>
					<!-- <form method="post" action="<=site_url("service_quick/insert_reply_json")?>"> -->
          <form enctype="multipart/form-data" method="post" action="<?=$longe_url?>service_quick/insert_reply_json?site=<?=$site?>">
            <input type="hidden" name="question_id" value="<?=$question->id?>">

					<div style="padding:15px;">
						<a name="go_to_reply"></a>
						再次提問（若與本次提問主題不同，請以另開單方式提問）<br>
						<textarea name="content" rows="6" style="width:100%" class="required"></textarea>

					</div>
          <? //if (!$is_ingame):?>

          <div>
            *提醒您：若無法選取檔案回報，請直接利用官網線上提問，謝謝。
            <img src="<?=$longe_url?>p/image/server/server-pic-btn1.png" class="pic_btn" align="middle"> <input type="file" name="reply_file01" class="pic_input" />
          </div>
          <div>
            <img src="<?=$longe_url?>p/image/server/server-pic-btn2.png" class="pic_btn" align="middle"> <input type="file" name="reply_file02" class="pic_input" />
          </div>
          <div>
            <img src="<?=$longe_url?>p/image/server/server-pic-btn3.png" class="pic_btn" align="middle"> <input type="file" name="reply_file03" class="pic_input" />
          </div>
          <? //endif;?>

					<div style="text-align:center; margin-top:20px;">
						<a href="javascript:;" onclick="$('form').submit()"><span style="background-position:-480px 0; height:50px; width:227px; display:inline-block;" class="items">填好了，送出!</span></a>
					</div>
					</form>
					<? endif;?>
					</td>
				</tr>
			</table>
<? else:?>
			<div>問題不存在</div>
<? endif;?>
		</div>

		<div class="login-button">
			<p>
				<input name="doSubmit" type="submit" id="doSubmit" value="" style="display:none;" />
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn2.png" class="button_submit" onclick="javascript:history.back();" />
			</p>
		</div>
	</div>
</div>
