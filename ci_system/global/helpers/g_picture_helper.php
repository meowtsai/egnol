<?php 

function make_pic_item($row)
{
	if (pathinfo($row->src, PATHINFO_EXTENSION) == "swf") {
		return flash_format($row);
	}
	$item = $size = '';
	if ($row->link) $item .= "<a href='{$row->link}' target='_blank'>";
	if ($row->width) $size = " width='{$row->width}' height='{$row->height}' ";
	if ($row->title) $size = " title='{$row->title}' ";
	$item .= "<img src='{$row->src}' {$size}/>";
	if ($row->link) $item .= "</a>";
	return $item;
}

function flash_format($row)
{
	$format = '
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$row->width.'" height="'.$row->height.'">
            <param name="movie" value="'.$row->src.'">
            <param name="play" value="true">
            <param name="loop" value="true">
            <param name="quality" value="High">
            <param name="src" value="'.$row->src.'">
            <param name="WMode" value="Window">
            <param name="Menu" value="true">
            <param name="AllowScriptAccess" value="always">
            <param name="Scale" value="ShowAll">
            <param name="DeviceFont" value="false">
            <param name="EmbedMovie" value="false">
            <param name="SeamlessTabbing" value="true">
	<embed  src="'.$row->src.'" width="'.$row->width.'" height="'.$row->height.'" play="true" loop="true" quality="High" WMode="Window" Menu="true" AllowScriptAccess="always" Scale="ShowAll" DeviceFont="false" EmbedMovie="false" SeamlessTabbing="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>
	';
	return $format;
}