<script type='text/javascript'>
	var agreement1 = '<iframe style="width:100%;height:98%;border:0;margin:0padding:0;" src="<?=$longe_url?>p/agreement/doc1.htm"></iframe>';
	var agreement2 = '<iframe style="width:100%;height:98%;border:0;margin:0padding:0;" src="<?=$longe_url?>p/agreement/doc2.htm"></iframe>';
	var agreement3 = '<iframe style="width:100%;height:98%;border:0;margin:0padding:0;" src="<?=$longe_url?>p/agreement/doc3.htm"></iframe>';
</script>
<form id="register_form" method="post" action="<?=$longe_url?>member/register_json?site=<?=$site?>">
	<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">
	<ul class="le_form">
		<li>會員註冊</li>
		<li>
			<div class="field_name">電子信箱：
			</div><div class="field_input"><input type="text" name="email" class="email" id="email">
		</li>
		<li>
			<div class="field_name">行動電話：
			</div><div class="field_input"><input type="text" name="mobile" class="mobile isMobile" id="mobile">
			</div><div class="field_tip">電子信箱與行動電話至少需填寫其中一個。</div>
		</li>
		<li>
			<div class="field_name">會員密碼：
			</div><div class="field_input"><input type="password" id="pwd" name="pwd" class="required" minlength="6" maxlength="18"></div>
		</li>
		<li>
			<div class="field_name">確認密碼：
			</div><div class="field_input"><input type="password" name="pwd2" class="required" equalTo='#pwd'>
			</div><div class="field_tip">6~18碼。</div>
		</li>
		<li>
			<div class="field_name">驗證碼：
			</div><div class="field_input">
				<div id="captcha_area"></div>
				<input class="required" style="width:50%;" type="text" name="captcha" size="5" maxlength="4" minlength="4" value="" />
			</div>
		</li>
		<li>
			<input name="chk" type="checkbox" class="required">我已閱讀並同意
			<div id="btn_agreement" onclick="javascript:leOpenDocumentViewer(agreement1);">『服務條款』</div>、
			<div id="btn_agreement" onclick="javascript:leOpenDocumentViewer(agreement2);">『個資同意書』</div>及
			<div id="btn_agreement" onclick="javascript:leOpenDocumentViewer(agreement3);">『隱私權政策』</div>
		</li>
		<li>
			<input tabindex="3" name="doLogin" type="submit" id="doLogin3" value="確定" />&nbsp;
			<input name="cancel" type="button" value="取消" onclick="javascript:history.back();" />
		</li>
		<li>
			<div></div>
		</li>
	</ul>
</form>
