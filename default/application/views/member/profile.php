<ul class="le_form">
	<li>
		<div>視覺圖片...</div>
	</li>
	<li>會員資料</li>
	<li>
		<div class="field_name">帳號類型
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">e-mail
		</div><div class="field_input">&nbsp;<?=$email?></div>
	</li>
	<li>
		<div class="field_name">行動電話
		</div><div class="field_input">&nbsp;<?=$mobile?></div>
	</li>
	<li>
		<div class="field_name">姓名
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">性別
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">生日
		</div><div class="field_input"></div>
	</li>
	<li>
		<div class="field_name">住址
		</div><div class="field_input"></div>
	</li>
	<li>
		<div style="float:left;">
			<input type="button" value="修改資料" onclick="javascript:location.href='/member/update_profile'" />
			<input type="button" value="修改密碼" onclick="javascript:location.href='/member/change_password'" />
			<input type="button" value="綁定帳號" onclick="javascript:location.href='/member/bind_account'" />
          </div>
		<div style="float:right;">
			<input type="button" value="登出" onclick="javascript:location.href='/member/logout'" />
		</div>
		<div style="clear:both;"></div>
	</li>
	<li>
		<div>提示訊息...</div>
	</li>
</ul>
