<?
$headings = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');

/* days and weeks vars now ... */
$running_day = date('w',mktime(0,0,0,$month,1,$year));
$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
$days_in_this_week = 1;
$day_counter = 0;
$dates_array = array();

$bs = array();
$be = array();
$es = array();
$ee = array();
$ef = array();
$vs = array();
$ve = array();
    
if ($bulletin_start) {
    foreach($bulletin_start->result() as $row) {
        if (empty($bs[$row->day])) $bs[$row->day] = "";
        
        $bs[$row->day] .= '<b style="color:#006400;">['.$row->game_id.']公告開始 - </b></div><a href="'.site_url("bulletin/edit/{$row->id}?game_id={$row->game_id}&amp;record=").'" class="">'.$row->title.'</a></br>';
    }
}

if ($bulletin_end) {
    foreach($bulletin_end->result() as $row) {
        if (empty($be[$row->day])) $be[$row->day] = "";
        
        $be[$row->day] .= '<b style="color:#ad2121;">['.$row->game_id.']公告結束 - </b><a href="'.site_url("bulletin/edit/{$row->id}?game_id={$row->game_id}&amp;record=").'" class="">'.$row->title.'</a></br>';
    }
}

if ($event_start) {
    foreach($event_start->result() as $row) {
        if (empty($es[$row->day])) $es[$row->day] = "";
        
        $es[$row->day] .= '<b style="color:#006400;">['.$row->game_id.']活動開始 - </b>'.$row->event_name.'</br>';
    }
}

if ($event_end) {
    foreach($event_end->result() as $row) {
        if (empty($ee[$row->day])) $ee[$row->day] = "";
        
        $ee[$row->day] .= '<b style="color:#ad2121;">['.$row->game_id.']活動結束 - </b>'.$row->event_name.'</br>';
    }
}

if ($event_fullfill) {
    foreach($event_fullfill->result() as $row) {
        if (empty($ef[$row->day])) $ef[$row->day] = "";
        
        $ef[$row->day] .= '<b style="color:#FF7913;">['.$row->game_id.']活動發放 - </b>'.$row->event_name.'</br>';
    }
}
    
if ($vip_start) {
    foreach($vip_start->result() as $row) {
        if (empty($vs[$row->day])) $vs[$row->day] = "";
        
        $vs[$row->day] .= '<b style="color:#006400;">['.$row->game_id.']VIP開始 - </b></div><a href="'.site_url("vip/event_view/{$row->id}?ticket_status=1").'" class="">'.$row->title.'</a></br>';
    }
}

if ($vip_end) {
    foreach($vip_end->result() as $row) {
        if (empty($ve[$row->day])) $ve[$row->day] = "";
        
        $ve[$row->day] .= '<b style="color:#ad2121;">['.$row->game_id.']VIP結束 - </b><a href="'.site_url("vip/event_view/{$row->id}?ticket_status=1").'" class="">'.$row->title.'</a></br>';
    }
}
?>

<table cellpadding="0" cellspacing="0" class="calendar">
    <legend><?=$year?>年<?=$month?>月<?=$day?>日</legend>
    <div class="btn-group">
        <a href="<?=site_url("platform/schedule?year={$prev_year}&month={$prev_month}")?>" class="btn btn-primary" role="button">&lt;&lt; 上個月</a>
        <a href="<?=site_url("platform/schedule")?>" class="btn" role="button">本月</a>
        <a href="<?=site_url("platform/schedule?year={$next_year}&month={$next_month}")?>" class="btn btn-primary" role="button">下個月 &gt;&gt;</a>
	</div>
    
    </br></br>
    
	<tr class="calendar-row"><td class="calendar-day-head"><?=implode('</td><td class="calendar-day-head">',$headings)?></td></tr>

	<tr class="calendar-row">

	<? for($x = 0; $x < $running_day; $x++): ?>
		<td class="calendar-day-np"> </td>
	<? $days_in_this_week++;
        endfor; ?>

	<? for($list_day = 1; $list_day <= $days_in_month; $list_day++): ?>
		<td class="calendar-day <?=($list_day==intval($day))?"calendar-today":""?>">
			<div class="day-number"><?=$list_day?></div>
            
            <?=(isset($bs[$list_day]))?$bs[$list_day]:""?>
            <?=(isset($be[$list_day]))?$be[$list_day]:""?>
            <?=(isset($es[$list_day]))?$es[$list_day]:""?>
            <?=(isset($ee[$list_day]))?$ee[$list_day]:""?>
            <?=(isset($ef[$list_day]))?$ef[$list_day]:""?>
            <?=(isset($vs[$list_day]))?$vs[$list_day]:""?>
            <?=(isset($ve[$list_day]))?$ve[$list_day]:""?>
			<?=str_repeat('<p> </p>',2);?>
			
		</td>
	   <? 	if($running_day == 6):?>
    </tr>
            <? if(($day_counter+1) != $days_in_month): ?>
    <tr class="calendar-row">
            <? endif; ?>
		<? 	$running_day = -1;
			$days_in_this_week = 0;
		endif;?>
    <?  $days_in_this_week++; $running_day++; $day_counter++;
    endfor; ?>

	<? if($days_in_this_week < 8): ?>
		<? for($x = 1; $x <= (8 - $days_in_this_week); $x++): ?>
			<td class="calendar-day-np"> </td>
		<? endfor;?>
	<? endif;?>

	</tr>
</table>