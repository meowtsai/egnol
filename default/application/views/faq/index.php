<!doctype html>
<html  lang="zh-Hant-TW">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="Shortcut Icon" type="image/x-icon" href="/p/image/2018/longe_logo.ico" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <title>遊戲FAQ</title>
    <style type="text/css">
        body {
          padding: 0;
          margin: 0;
          height: 100%;
          width: 100%;
        }
        body #content-login {
          width: 100%;
          height: 100%;
        }
        .login-ins .search_box{max-width:725px; width:100%; height:33px; display:block; margin:5px auto;}
        .search_box .search_string{width:73%; height:33px; text-align:left; font-weight: bold; background-color:#<?=$row_theme->input_bgcolor?>;}
        .search_box .search_btn{width:12%; height:33px; text-align:center; font-size:16px; color:#<?=$row_theme->btn_fgcolor?>; background-color:#<?=$row_theme->btn_bgcolor?>; border-bottom-color:#<?=$row_theme->btn_bordercolor?>; border-left-width: 0px; border-top-width: 0px; border-right-width: 0px;}
        body{background-color:#<?=$row_theme->body_bgcolor?>; color:#<?=$row_theme->body_fgcolor?>;}


    </style>
  </head>
  <body>
<div id="content-login">
	<div class="login-ins">
		<div class="login_box">
      <form id="faq_form" class="search_box form-inline"  method="post" action="<?=$longe_url?>faq?site=<?=$site?>">
        <input type="hidden" name="bodyonly" id="bodyonly" value="<?=$bodyonly?>">
        <input type="text" name="search_string" class="search_string form-control" id="search_string"  placeholder="輸入關鍵字">
        <input type="submit" class="btn btn-small btn-inverse search_btn form-control" name="action" value="查詢">
        <? if ($bodyonly):?>
        <input type="button" class="btn btn-small btn-inverse search_btn form-control" onclick="location.href='<?=$longe_url?>faq?site=<?=$site?>&bodyonly=true&hidetitle=true';" value="首頁">
        <? endif;?>
      </form>
      <hr class="my-4">
			<div class="login_member">
				<div class="login_info">

              <div class="hd" >
              </div>
                    <div class="bd" >
                        <? if(!$hidetitle):?>
                        <div style="font-size:18px; color:#222; font-weight:bold;">
                            <?=($row->title=='home')?"":$row->title?>
                        </div>
                        <? endif;?>
                        <div class="fixck">
                            <? if(isset($row->content)):?>
                            <?=$row->content?>
                            <? else:?>
                            查無資料，請嘗試其他關鍵字!
                            <? endif;?>
                        </div>
                    </div>
                    <div class="ft">
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>
<script>
	(function($){
		$(window).load(function(){
			$(".scrollbar").mCustomScrollbar();
		});
	})(jQuery);
</script>
