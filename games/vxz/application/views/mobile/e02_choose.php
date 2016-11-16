<?php 
header('ALLOW-FROM https://vxz.longeplay.com/'); 
header('Access-Control-Allow-Origin: *');  
?>
<style>
*{
	margin:auto 0;
	}
.content{
	width:573px;
	height:362px;
	background-image:url('<?=base_url()?>p/img/mobile/e02/display.png');
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
	top:20px;
	margin-left:130px;
	}
	
.t2{
	width:150px;
	height:30px;
	position:relative;
	top:27px;
	margin-left:355px;
	}
.t3{
	width:150px;
	height:30px;
	position:relative;
	top:31px;
	margin-left:330px;
	}
.t4{
	position:relative;
	top:140px;
	margin-left:115px;
	}
	
.t5{
	position:relative;
	top:140px;
	margin-left:105px;
	}		
select{
	width:200px;
	height:25px;
	font-size:15px;
	color:#464545;
	font-family:Arial,"微軟正黑體";
	border-style: solid;
    border-color:#A6A4A4;
	left:150px;
	}	
	
.t6{
	width:181px;
	height:41px;
	position:relative;
	top:9px;
	left:190px;
}	
.t7{
	position:relative;
	top:112px;	
	left:190px;
}
</style>

<div class="content">
    <p class="t1"><?=$uid;?></p>
    <p class="t2"><?=$billing_sum;?></p>
    <p class="t3"><?=$billing_sum*0.4;?></p>
    <form id="choose_form" method="post" action="<?=base_url()?>mobile/e02_content">
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
    <a href="<?=base_url()?>mobile/e02_billinglist" class="t6"><img src="<?=base_url()?>p/img/mobile/e02/btn0.png"></a>
	<div class="login-button t7"><img src="<?=base_url()?>p/img/mobile/e02/btn.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')"></div>
</div>