<? 
	$result = array();
	$result[0] = array("失敗", "#700");
	$result[1] = array("成功", "#070");
	$result[2] = array("失敗", "#d00");
	$result[3] = array("逾時", "#d00");
	$result[4] = array("失敗", "#000");
	
?>
<style type="text/css">
#main table {width:100%;}
#main table thead {background:#eee; border-bottom:1px solid #ccc;}
#main table td, #main table th {padding:3px; text-align:center; }
#main table td {border-bottom:1px solid #eee; font-size:13px;}
.line_row span {text-align:center; display:inline-block; font-size:13px; line-height:19px;}
.line_row.header span {color:#880;}
</style>

<div class="items" style="width:651px; height:32px; line-height:32px; background-position:0 -630px; margin:20px auto 20px; text-align:center;">
	您可以透過訂單號或時間進行查詢，若有任何問題，歡迎至客服中心提問！
</div>
                
<div style="width:620px; margin:0 auto;">

<div style="margin-bottom:10px;">
	<form action="<?=site_url("member/wallet_log")?>" method="get">
		訂單號  <input type="text" name="id" value="<?=$this->input->get("id")?>" style="width:80px;"> |
		兌換時間 <input type="text" name="start_date" value="<?=$this->input->get("start_date")?>" style="width:130px"> 
			至 <input type="text" name="end_date" value="<?=$this->input->get("end_date")?>" style="width:130px">
		<input type="submit" name="action" value="查詢" class="btn">
	</form>  
</div>

<ul>
	<li class="line_row header">
		<span style="width:140px;">日期</span>
		<span class="line_field" style="width:70px;">訂單號</span>
		<span class="line_field" style="width:60px;">金額</span>
		<span class="line_field" style="width:120px;">遊戲</span>
		<span class="line_field" style="width:120px;">伺服器</span>
		<span class="line_field" style="width:50px;">結果</span>
	</li>
<? 
	foreach($query->result() as $row): fb($row)?>
	<li class="line_row">
		<span style="width:140px;"><?=date("Y-m-d H:i", strtotime($row->create_time))?></span>
		<span class="line_field" style="width:70px;"><?=$row->id?></span>
		<span class="line_field" style="width:60px;"><?=$row->amount?></span>
		<span class="line_field" style="width:120px;"><?=$row->game_name?></span>
		<span class="line_field" style="width:120px;"><?=$row->server_name?></span>
		<span class="line_field" style="color:<?=$result[$row->result][1]?>; width:50px;"><?=$result[$row->result][0]?></span>		
	</li>
	<? endforeach;?>
</ul>

<div style="margin:10px; text-align:center;">
	<?= $this->pagination->create_links()?>
</div>

</div>