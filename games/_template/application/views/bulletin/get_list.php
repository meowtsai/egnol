
<table width="625" border="0" align="center" cellpadding="0" cellspacing="0">
	<? foreach($query->result() as $row):?>
              <tr>
                <td width="535" height="20" class="text4">
                	<span style="padding-right:8px; font-size:12px; color:#333;"><?=$row->category?></span>
                	<a href="<?=site_url("bulletin/detail/{$row->id}")?>"><?=$row->bulletin_title?></a>
                </td>
                <td width="90" class="text2"><?=date("Y-m-d", strtotime($row->create_date))?></td>
              </tr>
              <tr>
                <td height="20" colspan="2">-----------------------------------------------------------------------------------------------------</td>
              </tr>
	<? endforeach;?>
</table>

<?=$this->pagination->create_links();?>