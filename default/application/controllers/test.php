<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$site = $this->_get_site();

		$news = $this->db->where("game_id", $site)->order_by("create_time", "desc")->get("news", 10);

		$this->_init_layout()
			->add_css_link("login")
			->add_css_link("news")
			->add_css_link("jquery.marquee")
			->add_css_link("jquery.mCustomScrollbar")
			->add_js_include("jquery.marquee.min")
			->set("news", $news)
			->standard_view();
	}


	function test_array()
	{
		//0523 per cs request - add 3 addtional pics columns
		$addtional_pics = array();
		array_push($addtional_pics,"pic1");
		//array_push($addtional_pics,"pic2");
		array_push($addtional_pics,"pic3");


		$queryId = 123;

		for($count = 0; $count < sizeof($addtional_pics);$count++)
		{
			//$this->db->insert("question_pictures", ("question_id"=>$queryId,"pic_path"=>$addtional_pics[i]));
			print_r(array("question_id"=>$queryId,"pic_path"=>$addtional_pics[$count]));
			//echo $addtional_pics[$count];
		}

			die();

	}

	function get_news()
	{
		$site = $this->input->get("site");
		$offset = $this->input->get("o");

		$query = $this->db->where("game_id", $site)->order_by("create_time", "desc")->get("news", $offset, 5);

		$result = array();

		foreach($query->result() as $row)
		{
			$news = array(
				"title"=>$row->title,
				"type"=>$row->type,
				"content"=>$row->content,
				"time"=>$row->create_time,
				"link"=>$row->link
			);

			array_push($result, $news);
		}

		die(json_encode($result));
	}

	function _make_trade_seq()
	{
		$rnd = rand(1000, 9999);
  		$trade_seq = "FREE".date("YmdHis")."{$rnd}";

		return $trade_seq;
	}

	function ap()
	{
		if(!IN_OFFICE)
			die();
		?>
<html>
<head>
</head>
<body>
	<form action="free_points" method="post">
		<p>UID: <input type="text" name="uid"/></p>
		<p>Server Id: <input type="text" name="server_id"/></p>
		<p>Points: <input type="text" name="points"/></p>
		<p><input type="submit" value="Submit"></p>
	</form>
</body>
</html>
		<?
	}

	function free_points()
	{
		if(!IN_OFFICE)
			die();

		$uid = intval($this->input->post("uid"));
		$server_id = $this->input->post("server_id");
		$points = intval($this->input->post("points"));

		if(empty($uid) || empty($server_id) || empty(points))
		{
			die("參數錯誤!");
		}
		if($points < 1 || $points > 10000)
		{
			die("點數超出範圍!");
		}

		$this->load->model("games");
		$server = $this->games->get_server($server_id);
		if(empty($server))
		{
			die("遊戲伺服器不存在");
		}

		$character = $this->db->where("server_id", $server_id)->where("uid", $uid)->from("characters")->get()->row();
		if(empty($character))
		{
			die("沒有角色!");
		}

//		$order_no = $this->_make_trade_seq();

		echo "帳號: {$uid}<br/>";
		echo "伺服器: {$server_id}<br/>";
		echo "角色 ID: {$character->id}<br/>";
		echo "角色名稱: {$character->name}<br/>";
		echo "點數: {$points}<br/>";

		$this->load->library("g_wallet");

		$order_id = $this->g_wallet->produce_order($uid, "free_points", "4", $points, $server_id, '', $character->id);//, $order_no);
		$order = $this->g_wallet->get_order($order_id);

		echo "交易序號: {$order_id}<br/>";

		// 先看是否有遊戲入點機制, 若有則轉點, 無則設為尚未轉進遊戲
		$this->load->library("game_api");
		if($this->game_api->has_billing($server->game_id))
		{
			// 呼叫遊戲入點機制
			$this->load->library("game_api/{$server->game_id}");
			$res = $this->{$server->game_id}->transfer($server, $order, $points, $game->exchange_rate);
			$error_message = $this->{$server->game_id}->error_message;

			if ($res === "1") {
				$this->g_wallet->complete_order($order);
				echo "贈點成功!";
			}
			else if ($res === "-1") {
				$this->g_wallet->cancel_timeout_order($order);
				echo "遊戲伺服器沒有回應(錯誤代碼: 002)";
			}
			else if ($res === "-2") {
				$this->g_wallet->cancel_other_order($order, $error_message);
				echo "{$error_message}(錯誤代碼: 003)";
			}
			else {
				$this->g_wallet->cancel_order($order, $error_message);
				echo "{$error_message}";
			}
		}
	}
}
