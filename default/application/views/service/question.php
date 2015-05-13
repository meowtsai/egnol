<style>
<!--
#main table td {padding:4px;}
.rule li {list-style-type:disc; list-style-position:outside; margin:1px 0 3px 22px;}
-->
</style>

<h3>線上提問</h3>

<div style="font-size:14px; line-height:22px; border-bottom:1px solid #518239; padding-bottom:20px; margin-bottom:20px;">

<p>歡迎您使用客服中心線上提問系統，本功能主要是處理遊戲上無法自行解決之問題，為能盡快解決您問題，在您使用本功能前，務必閱讀下列之使用規範與處理原則，當您提交問題後則視為您同意相關規範。</p>

<h4 style="margin-top:8px;">使用規範</h4>
<ul class="rule">
	<li>您同意問題發生點起算七日內，必須進行回報，逾時問題概不受理，相關損失由您自行負責。</li>
	<li>您同意回報問題時，需清楚完整回報相關問題，道具或NPC名稱請使用正確且完整的稱呼，切勿使用任何口語化或簡稱。</li>
	<li>若需提供截圖部分，請將圖片檔案存取為jpg格式，以免上傳失敗。</li>
	<li>您同意線上提問僅供遊戲遇到無法自行處理之異常問題，若您當作一般留言或攻略詢問使用，則恕本公司不予回覆直接結案。</li>
	<li>您同意遊戲正常設定因素下，相關衍生問題，本公司有權不予處理；遊戲建議部分，會協助提報給予相關單位，但不保證是否會採納。</li>
	<li>線上提問提交客服人員處理後，依問題類型不同，約需3~5個工作天處理，您也可以至線上提問處理查詢頁面查詢處理結果。</li>
	<li>您同意遊戲若牽涉原廠修正或技術考量時，經客服人員回覆後，需等候統一性的處理而延長處理期限。</li>
	<li>您同意不因個人情緒問題，刻意以不實或不雅言論方式攻擊、辱罵處理人員或本公司，如提問涉及上述情況，本公司視情節輕重短暫或永久限制您的帳號使用權且該提問直接結案，請您了解提問是為了幫您解決問題，若情緒不佳，建議您稍後再行提問。</li>
</ul>

</div>

<div style="border-bottom:1px solid #518239; padding:20px 20px 40px; margin-bottom:20px; ">

	<div style="color:red; margin-bottom:8px;">【提問內容】</div>
	
	<form method="post" action="<?=site_url("service/question_ajax")?>" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td>提問類型：</td>
			<td style="width:200px;">
				<select name="question_type" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($this->config->item("question_type") as $id => $type):?>
					<option value="<?=$id?>"><?=$type?></option>
					<? endforeach;?>
				</select>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>遊戲名稱：</td>
			<td>
				<select name="game" class="required" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
			<td>伺服器：</td>
			<td>
				<select name="server" class="required" style="width:150px;">
					<option value="">--請先選擇遊戲--</option>
				</select>
							
				<select id="server_pool" style="display:none;">
					<? foreach($servers->result() as $row):?>
					<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>角色名稱：</td>
			<td><input type="text" name="character_name" class="required" maxlength="30" style="width:150px;"></td>
			<td></td>
			<td></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">提問描述：</td>
			<td colspan="3"><textarea rows="8" minlength="5" maxlength="500" style="width:100%;" name="content" class="required"></textarea></td>
		</tr>		
		<tr>
			<td>截圖：</td>
			<td colspan="3">
				<input type="file" name="file01"><br>
				<input type="file" name="file02"><br>
				<input type="file" name="file03"><br>
				<div style="font-size:13px; color:#251;">(限檔案格式 .jpg .gif .bmp，檔案大小 1MB 以下)</div>
			</td>
		</tr>				
		<tr>
			<td>聯絡電話：</td>
			<td><input type="hidden" name="phone" value="<?=$user->mobile?>">
				<?=$user->mobile?>
			</td>
			<td>E-mail：</td>
			<td><input type="hidden" name="email" value="<?=$user->email?>">
				<?=$user->email?>
			</td>
		</tr>			
		<tr>
			<td></td>
			<td colspan="3">( 聯絡電話及E-mail由系統自動帶入，若要變更請至<a href="<?=site_url("member/update_member_data")?>" target="_blank">會員中心</a>。)</td>
		</tr>
	</table>
	</form>

</div>

<div style="text-align:center">
	<a href="javascript:;" onclick="$('form').submit()"><span style="background-position:-480px 0; height:50px; width:227px; display:inline-block;" class="items"></span></a>
</div>