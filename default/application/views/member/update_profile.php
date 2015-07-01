<div style="padding:20px 0;width:80%; max-width:480px; margin: 0 auto; text-align: center;">
	<form name="member_update" id="member_update" method="post" action="<?=site_url("member/update_profile_json")?>">
		<input type="hidden" size="0" name="uid" value="<?=$data->uid?>">
		<ul class="le_form">
			<li>
				<div class="field_name">姓名：
				</div><div class="field_input"><input type="text" size="12"  maxlength="12" name="name" value="<?=$data->name?>" class="required"></div>
			</li>
			<li>
				<div class="field_name">性別：
				</div><div class="field_input">
                  	<input id="sex_0" type="radio" value="1" name="sex" <?=($data->sex == '1' || empty($data->sex)) ? "checked='checked'" : ""?> />
                  	<label for="sex_0">男生</label>
                  	<input id="sex_1" type="radio" value="2" name="sex" <?=($data->sex == '2') ? "checked='checked'" : ""?> />
                    <label for="sex_1">女生</label>
				</div>
			</li>
			<li>
				<div class="field_name">生日：
				</div><div class="field_input">
					<?
						if ( ! empty($data->birthday)) {
							$split_birthday = explode("-", $data->birthday);
							$year = $split_birthday[0];
							$month = $split_birthday[1];
							$day = $split_birthday[2];
						}
						else {
							$year = $month = $day = false;
						}
					?>
					<select name="birthday_y" style="width:80%;">
						<? if ($year):?>
						<option value="<?=$year?>"><?=$year?></option>
						<? else:?>
						<option value="">--</option>
						<? endif;?>
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
					<select name="birthday_m" style="width:80%;">
						<? if ($month):?>
						<option value="<?=$month?>"><?=$month?></option>
						<? else:?>
						<option value="">--</option>
						<? endif;?>
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
					<select name="birthday_d" style="width:80%;">
						<? if ($day):?>
						<option value="<?=$day?>" selected="selected"><?=$day?></option>
						<? else:?>
						<option value="">--</option>
						<? endif;?>
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
				</div>
			</li>
			<li>
				<div class="field_name">住址：
				</div><div class="field_input"><input type='text' size='25'  maxlength='30' name='address_road' value="<?//=$data->address_road?>"></div>
			</li>
			<li>
				<div class="field_name">e-mail：
				</div><div class="field_input"><input type="text" size="25"  maxlength="50" name="email" value="<?=$data->email?>" class="required email"></div>
			</li>
			<li>
				<div class="field_name">行動電話：
				</div><div class="field_input"><input type="text" size="25"  maxlength="10" name="mobile" value="<?=$data->mobile?>" class="isMobile"></div>
			</li>
			<li>
                <input TYPE="submit" value="確認送出" class="btn"/>
			</li>
		</ul>
	</form>
</div>
