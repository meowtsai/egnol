<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>無標題文件</title>
<style>
*{
	margin:auto 0;
	}
.content{
	width:885px;
	height:368px;
	background-image:url('<?=base_url()?>p/img/event/e02/display.png');
	}
	
.t1, .t2, .t3{
	font-size:24px;
	font-family:Arial,"微軟正黑體";
	text-align:center;
	color:#FFF;
	}	
	
.t1{
	width:350px;
	height:30px;
	position:relative;
	top:35px;
	margin-left:190px;
	}
	
.t2{
	width:150px;
	height:30px;
	position:relative;
	top:48px;
	margin-left:405px;
	}
.t3{
	width:150px;
	height:30px;
	position:relative;
	top:54px;
	margin-left:380px;
	}
.t4{
	position:relative;
	top:127px;
	margin-left:400px;
	}
	
.t5{
	position:relative;
	top:127px;
	margin-left:130px;
	}		
select{
	width:180px;
	height:30px;
	font-size:15px;
	color:#464545;
	font-family:Arial,"微軟正黑體";
	border-style: solid;
    border-color:#A6A4A4;
	}	
	
.t6{
	position:relative;
	top:-55px;
	left:617px;
}	
.t7{
	position:relative;
	top:110px;
	left:339px;	
}		

</style>
</head>

<body>
<div class="content">
    <p class="t1"><?=$uid;?></p>
    <p class="t2"><?=$billing_sum;?></p>
    <p class="t3"><?=$billing_sum*0.4;?></p>
    <form id="choose_form" method="post" action="<?=base_url()?>event/e02_content">
    	<select name="character_id" id="character_id" class="t4 required">
        	<option value="">請選擇</option>
			<?if($characters->num_rows > 0): ?>
			    <?foreach($characters->result() as $row):?>
					<option value="<?=$row->id?>"><?=$row->character_name.'('.$row->server_name.')';?></option>
				<?endforeach;?>
			<?endif;?>
        </select>
		<input name="doLogin" type="submit" id="doSubmit" value="" class="button_submit" style="display:none;" />
    </form>
    <a href="<?=base_url()?>event/e02_billinglist" class="t6"><img src="<?=base_url()?>p/img/event/e02/btn0.png"></a>
	<div class="login-button t7"><img src="<?=base_url()?>p/img/event/e02/btn.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')"></div>
</div>
</body>
</html>