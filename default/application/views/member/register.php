<script src='http://www.long_e.com.tw/p/js/member/register.js'></script>
<style type="text/css">
#long_e_register {background:#fff; width:500px; margin-top:20px; padding:20px; font-size:14px; text-align:left;}
#long_e_register a {color:#00a;}
#long_e_register table td {padding:3px;}
#long_e_register .tip {font-size:13px; color:#095;}
#detail_switch {cursor:pointer; border:1px solid #ccc; padding:0 22px; margin-bottom:20px; color:#666;}
#detail_switch:hover {border:1px solid #c98;}
</style>

<div id="long_e_register">

<form id="register_form" method="post" action="/member/register_json">
<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">

<table>
	<tr>
		<td style="width:110px"><img src="http://www.long_e.com.tw/img/ball-blue.gif"> 會員帳號：</td>
		<td><input type="text" name="account" class="required" minlength="6" maxlength="18">
			<div class="tip">6~18碼英文(系統會自動將大寫轉小寫)或數字組合。</div>
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-blue.gif"> 會員密碼：</td>
		<td><input type="password" id="pwd" name="pwd" class="required" minlength="6" maxlength="18">
			<div class="tip">6~18碼。</div>
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-blue.gif"> 確認密碼：</td>
		<td><input type="password" name="pwd2" class="required" equalTo='#pwd'></td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-blue.gif"> 電子信箱：</td>
		<td><input type="text" name="email" class="required email">
			<div class="tip">活動或忘記密碼時使用。</div>
		</td>
	</tr>
</table>

<? if ( ! $this->input->get("ajax")):?>
<div style="margin:25px 0 5px; color:#333; font-weight:bold;">進階資料：(非必填，僅作為客服、帳務、活動用途，仍建議日後填寫)</div>

<div id="detail_switch">
+
</div>

<table id="detail" style="display:none; margin-bottom:20px;">
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 您的姓名：</td>
		<td><input type="text" name="name">
			<div class="tip">請輸入您的真實姓名，作為客服聯繫時使用。</div>
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 身分證號：</td>
		<td><input type="text" size="25" maxlength="20" name="ident" class="twId"></td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 您的生日：</td>
		<td>
				  <select name="birthday_y" style="width:80px">
				    <option value=''>--</option>
					<option value='1940'>1940</option>
					<option value='1941'>1941</option>
					<option value='1942'>1942</option>
					<option value='1943'>1943</option>
					<option value='1944'>1944</option>
					<option value='1945'>1945</option>
					<option value='1946'>1946</option>
					<option value='1947'>1947</option>
					<option value='1948'>1948</option>
					<option value='1949'>1949</option>
					<option value='1950'>1950</option>
					<option value='1951'>1951</option>
					<option value='1952'>1952</option>
					<option value='1953'>1953</option>
					<option value='1954'>1954</option>
					<option value='1955'>1955</option>
					<option value='1956'>1956</option>
					<option value='1957'>1957</option>
					<option value='1958'>1958</option>
					<option value='1959'>1959</option>
					<option value='1960'>1960</option>
					<option value='1961'>1961</option>
					<option value='1962'>1962</option>
					<option value='1963'>1963</option>
					<option value='1964'>1964</option>
					<option value='1965'>1965</option>
					<option value='1966'>1966</option>
					<option value='1967'>1967</option>
					<option value='1968'>1968</option>
					<option value='1969'>1969</option>
					<option value='1970'>1970</option>
					<option value='1971'>1971</option>
					<option value='1972'>1972</option>
					<option value='1973'>1973</option>
					<option value='1974'>1974</option>
					<option value='1975'>1975</option>
					<option value='1976'>1976</option>
					<option value='1977'>1977</option>
					<option value='1978'>1978</option>
					<option value='1979'>1979</option>
					<option value='1980'>1980</option>
					<option value='1981'>1981</option>
					<option value='1982'>1982</option>
					<option value='1983'>1983</option>
					<option value='1984'>1984</option>
					<option value='1985'>1985</option>
					<option value='1986'>1986</option>
					<option value='1987'>1987</option>
					<option value='1988'>1988</option>
					<option value='1989'>1989</option>
					<option value='1990'>1990</option>
					<option value='1991'>1991</option>
					<option value='1992'>1992</option>
					<option value='1993'>1993</option>
					<option value='1994'>1994</option>
					<option value='1995'>1995</option>
					<option value='1996'>1996</option>
					<option value='1997'>1997</option>
					<option value='1998'>1998</option>
					<option value='1999'>1999</option>
					<option value='2000'>2000</option>
					<option value='2001'>2001</option>
					<option value='2002'>2002</option>
					<option value='2003'>2003</option>
					<option value='2004'>2004</option>
					<option value='2005'>2005</option>
					<option value='2006'>2006</option>
					<option value='2007'>2007</option>
					<option value='2008'>2008</option>                    
				</select>
                    年 
                <select name="birthday_m" style="width:80px">
                	<option value=''>--</option>
                    <option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>				
                </select>
                    月 
                <select name="birthday_d" style="width:80px">
                	<option value=''>--</option>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					<option value='13'>13</option>
					<option value='14'>14</option>
					<option value='15'>15</option>
					<option value='16'>16</option>
					<option value='17'>17</option>
					<option value='18'>18</option>
					<option value='19'>19</option>
					<option value='20'>20</option>
					<option value='21'>21</option>
					<option value='22'>22</option>
					<option value='23'>23</option>
					<option value='24'>24</option>
					<option value='25'>25</option>
					<option value='26'>26</option>
					<option value='27'>27</option>
					<option value='28'>28</option>
					<option value='29'>29</option>
					<option value='30'>30</option>
					<option value='31'>31</option>
				</select>
                    日		
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 性別：</td>
		<td>
			<input id="sex_0" type="radio" value="1" name="sex" />
			<label for="sex_0">男生</label>
            <input id="sex_1" type="radio" value="2" name="sex" />
            <label for="sex_1">女生</label>
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 手機號碼：</td>
		<td>
			<input type="text" size="25"  maxlength="10" name="mobile" class="isMobile">
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 聯絡電話：</td>
		<td>
			<input type="text" size="25"  maxlength="16" name="phone_address" class="isPhone">
		</td>
	</tr>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-gray.gif"> 通訊地址：</td>
		<td>
			<input type='text' size='25'  maxlength='30' name='address_road'>	
		</td>
	</tr>
</table>
<? endif;?>

<table>
	<tr>
		<td><img src="http://www.long_e.com.tw/img/ball-blue.gif"> 驗證碼：</td>
		<td>
			<div id="captcha_area"></div>
			<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="required" value="" />
		</td>
	</tr>		
	<tr>
		<td></td>
		<td>
			<input name="chk" type="checkbox" class="required"> 我同意 <a href="<?=site_url("platform/member_rule")?>" target="_blank">會員條款</a>	
		</td>
	</tr>	
</table>

<div style="text-align:center; margin:5px;">
	<input type="image" src="http://www.long_e.com.tw/img/member-icon-3.gif" width="107" height="25" >
</div>

</form>

</div>