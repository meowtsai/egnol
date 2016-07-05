<div id="func_bar">
	<a href="<?=site_url("event/add_code")?>" class="btn btn-primary"><i class="icon-plus icon-white"></i> 新增序號</a>
	<!--a href="javascript:;" class="json_del btn btn-danger" url="<?=site_url("event/delete_remain_codes/{$title}")?>"><i class="icon-trash icon-white"></i> 刪除未發放的序號</a-->
</div>

<form class="form-search" method="post">
    <input type="text" name="title" value="<?=$this->input->get("title")?>" class="input-medium required" placeholder="名稱">
    遊戲
    <select name="game" class="span2 required">
        <option value="">--</option>
        <? foreach($games->result() as $row):?>
        <option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
        <? endforeach;?>
    </select>
		
    
    <select name="is_active" style="width:100px">
        <option value="">已啟用</option>
        <option value="not" <?=($this->input->get("is_active")=='not' ? 'selected="selected"' : '')?>>已停用</option>
        <option value="all" <?=($this->input->get("is_active")=='all' ? 'selected="selected"' : '')?>>全顯示</option>
    </select>	
    
	<button type="submit" class="btn"><i class="icon-search"></i> 查詢</button>
	<? if ($this->input->post()):?>
	<a class="btn btn-link" href="<?=current_url()?>"><i class="icon-remove"></i> 取消查詢</a>
	<? endif;?>
</form>

<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料</div>

<? else:?>

<div>
	序號總數 <?=$total_rows?>組
</div>
	<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped">
	<thead>
		<tr>
            <td style="width:160px;text-align:center">遊戲</td>
            <td style="width:300px;text-align:center">兌換內容</td>
            <td style="width:80px;text-align:center">總數</td>
            <td style="width:80px;text-align:center">已領取</td>
            <td style="width:120px;text-align:center">狀態</td>
        </tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):?>
		<tr>
			<td style="text-align:center;"><?=$row->game_id?></td>
			<td style="text-align:center;"><?=$row->title?></td>
			<td style="text-align:right;"><?=$row->total?></td>
			<td style="text-align:right;"><?=$row->used?></td>
			<td style="text-align:center;">
                <?if($row->is_active):?>
                    已啟用｜<a href="javascript:;" class="toggle" url="<?=site_url("event/toggle_code?game_id={$row->game_id}&title={$row->title}&is_active=0")?>">停止</a>
                <?else:?>
                    已停止｜<a href="javascript:;" class="toggle" url="<?=site_url("event/toggle_code?game_id={$row->game_id}&title={$row->title}&is_active=1")?>">啟用</a>
                <?endif;?>
            </td>
		</tr>
	<? endforeach;?>
	</tbody>
</table>

<? endif;?>