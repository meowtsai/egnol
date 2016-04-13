<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_api extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if(!IN_OFFICE)
			die();

		$site = $this->input->get("site");
		
		// 讀取活動資料
		$query = $this->db->from("events")
			->where("game_id", $site)
			->where("url is not null", null, false)
			->order_by("priority", "desc")
			->get();
		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html> 
			<head> 
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
				<title></title>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
				<style>
					table, th, td {
						border: 1px solid #000;
					}
					td { padding:5px; }
				</style>
			</head>
			<body>
				<table>
					<tr><th>ID</th><th>名稱</th><th>起始時間</th><th>結束時間</th><th>狀態</th><th>看版圖</th><th>連結網址</th><th>指令</th></tr>
					<?
						foreach($query->result() as $row)
						{
							$img = sprintf("%'.04d", $row->id);
							
							echo "<tr><td>{$row->id}</td><td>{$row->event_name}</td><td>{$row->begin_time}</td><td>{$row->end_time}</td>";
							echo "<td>{$row->status}</td>";
							echo "<td><img src='https://{$site}.longeplay.com.tw/p/image/banner/{$img}.jpg' style='height:40px;' /></td>";
							echo "<td>{$row->url}</td><td><a href='banner_api/modify_event?site=r2g&event={$row->id}'>編輯</a></td></tr>";
						}
					?>
					<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><a href="banner_api/add_event?site=r2g">新增活動</a></td></tr>
				</table>
			</body>
			</html>
		<?
	}
	
	function modify_event()
	{
		if(!IN_OFFICE)
			die();
		
		$site = $this->input->get("site");
		$event_id = $this->input->get("event");
		
		// 讀取活動資料
		$event = $this->db->from("events")
			->where("id", $event_id)
			->get()->row();

		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html> 
			<head> 
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
				<title></title>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
			</head>
			<body>
				<form id="banner_form" enctype="multipart/form-data" method="post" action="update_event?site=<?=$site?>&event=<?=$event_id?>">
					<table style="width:500px;border:0">
						<tr>
							<th>活動名稱</th>
							<td><input type="text" maxlength="20" name="event_name" value="<?=$event->event_name?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>類型</th>
							<td>
								<select name="event_type" style="width:90%;">
									<option value="0" <?= ($event->type === "0") ? "selected='selected'" : "" ?>>序號發送</option>
									<option value="99" <?= ($event->type === "99") ? "selected='selected'" : "" ?>>其他</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>狀態</th>
							<td>
								<select name="event_status" style="width:90%;">
									<option value="0" <?= ($event->status === "0") ? "selected='selected'" : "" ?>>關閉</option>
									<option value="1" <?= ($event->status === "1") ? "selected='selected'" : "" ?>>開啟</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>起始時間</th>
							<td><input type="text" maxlength="20" name="begin_time" value="<?=$event->begin_time?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>結束時間</th>
							<td><input type="text" maxlength="20" name="end_time" value="<?=$event->end_time?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>圖片</th><td><input type="file" name="event_banner" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>連結</th><td><input type="text" maxlength="256" name="event_url" value="<?=$event->url?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th></th><td style="text-align:right;padding-top:10px;"><input type="submit" value="更新" />&nbsp;&nbsp;<input type="button" value="取消" onclick="javascript:window.history.back()" /></td>
						</tr>
					</table>
				</form>
			</body>
			</html>			
		<?
	}
	
	function update_event()
	{
		if(!IN_OFFICE)
			die();
		
		$site = $this->input->get("site");
		$event_id = $this->input->get("event");
		$event_name = $this->input->post("event_name");
		$event_type = $this->input->post("event_type");
		$event_status = $this->input->post("event_status");
		$begin_time = $this->input->post("begin_time");
		$end_time = $this->input->post("end_time");
		$event_url = $this->input->post("event_url");
		
		// 讀取活動資料
		$event = $this->db->from("events")
			->where("id", $event_id)
			->get()->row();
		
		if(!file_exists($_FILES['event_banner']['tmp_name']) || !is_uploaded_file($_FILES['event_banner']['tmp_name']))
		{
			// 沒有上傳檔案
		}
		else
		{
			// 有上傳檔案
			if ($_FILES["event_banner"]["error"] > 0)
			{
				// 檔案傳輸失敗
				die("上傳檔案失敗!");
			}
			
			$file = $_FILES["event_banner"]["tmp_name"];
		}

		if(!empty($event_name) && $event_name !== $event->event_name)
			$this->db->set("event_name", $event_name);
		if(!empty($begin_time) && $begin_time !== $event->begin_time)
			$this->db->set("begin_time", $begin_time);
		if(!empty($end_time) && $end_time !== $event->end_time)
			$this->db->set("end_time", $end_time);
		if(!empty($event_url) && $event_url !== $event->url)
			$this->db->set("url", $event_url);

		$this->db->set("type", $event_type);
		$this->db->set("status", $event_status);
		$this->db->where("id", $event_id)->update("events");

		if(isset($file))
		{
			$folder = $_SERVER['DOCUMENT_ROOT'] . "/games/" . $site . "/p/image/banner/";

			move_uploaded_file($file, $folder . sprintf("%'.04d.jpg", $event_id));
		}
		
		die("<script>alert('更新成功!');window.location.href='/banner_api?site={$site}';</script>");
	}
	
	function add_event()
	{
		if(!IN_OFFICE)
			die();
		
		$site = $this->input->get("site");
		
		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
			<html> 
			<head> 
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
				<title></title>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
			</head>
			<body>
				<form id="banner_form" enctype="multipart/form-data" method="post" action="new_event?site=<?=$site?>">
					<table style="width:500px;border:0">
						<tr>
							<th>活動名稱</th>
							<td><input type="text" maxlength="20" name="event_name" value="" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>類型</th>
							<td>
								<select name="event_type" style="width:90%;">
									<option value="0">序號發送</option>
									<option value="99" selected="selected">其他</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>狀態</th>
							<td>
								<select name="event_status" style="width:90%;">
									<option value="0">關閉</option>
									<option value="1">開啟</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>起始時間</th>
							<td><input type="text" maxlength="20" name="begin_time" value="<?=date("Y-m-d H:i:s")?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>結束時間</th>
							<td><input type="text" maxlength="20" name="end_time" value="<?=date("Y-m-d H:i:s")?>" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>圖片</th><td><input type="file" name="event_banner" style="width:90%;" /></td>
						</tr>
						<tr>
							<th>連結</th><td><input type="text" maxlength="256" name="event_url" value="" size="33" style="width:90%;" /></td>
						</tr>
						<tr>
							<th></th><td style="text-align:right;padding-top:10px;"><input type="submit" value="新增" />&nbsp;&nbsp;<input type="button" value="取消" onclick="javascript:window.history.back()" /></td>
						</tr>
					</table>
				</form>
			</body>
			</html>			
		<?
	}
	
	function new_event()
	{
		if(!IN_OFFICE)
			die();
		
		$site = $this->input->get("site");
		$event_name = $this->input->post("event_name");
		$event_type = $this->input->post("event_type");
		$event_status = $this->input->post("event_status");
		$begin_time = $this->input->post("begin_time");
		$end_time = $this->input->post("end_time");
		$event_url = $this->input->post("event_url");
		
		if(!file_exists($_FILES['event_banner']['tmp_name']) || !is_uploaded_file($_FILES['event_banner']['tmp_name']))
		{
			// 沒有上傳檔案
			die("<script>alert('必須有看版檔案!');window.location.href='/banner_api?site={$site}';</script>");
		}
		else
		{
			// 有上傳檔案
			if ($_FILES["event_banner"]["error"] > 0)
			{
				// 檔案傳輸失敗
				die("<script>alert('上傳檔案失敗!');window.location.href='/banner_api?site={$site}';</script>");
			}
		}

		if(empty($event_name))
			die("<script>alert('活動名稱不可空白!');window.location.href='/banner_api?site={$site}';</script>");
		if(empty($event_url))
			die("<script>alert('連結不可空白!');window.location.href='/banner_api?site={$site}';</script>");

		if(empty($begin_time))
			$begin_time = "2016-01-01 00:00:00";
		if(empty($end_time))
			$end_time = "2099-12-31 23:59:59";
		
		$this->db->set("game_id", $site);
		$this->db->set("event_name", $event_name);
		$this->db->set("type", $event_type);
		$this->db->set("status", $event_status);
		$this->db->set("begin_time", $begin_time);
		$this->db->set("end_time", $end_time);
		$this->db->set("url", $event_url);
		$this->db->insert("events");
		
		$event_id = $this->db->insert_id();

		$file = $_FILES["event_banner"]["tmp_name"];
		$folder = $_SERVER['DOCUMENT_ROOT'] . "/games/" . $site . "/p/image/banner/";

		move_uploaded_file($file, $folder . sprintf("%'.04d.jpg", $event_id));
		
		die("<script>alert('新增成功!');window.location.href='/banner_api?site={$site}';</script>");
	}
}
