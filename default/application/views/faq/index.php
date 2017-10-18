<? if ($bodyonly):?>
<style type="text/css">
    .login-ins .search_box{max-width:725px; width:100%; height:33px; display:block; margin:5px auto;}
    .search_box .search_string{width:85%; height:33px; text-align:left; font-weight: bold; background-color:#E7DAB8;}
    .search_box .search_btn{width:12%; height:33px; text-align:center; font-size:16px; color:#E8C899; background-color:#784C25; border-bottom-color:#92714B; border-left-width: 0px; border-top-width: 0px; border-right-width: 0px;}
    body{background-color:#FEF7DE; color:#5C5C5C; font-size:18px;}
</style>
<? endif;?>
<div id="content-login">
	<div class="login-ins">
		<div class="login_box">
                <form id="faq_form" class="search_box" enctype="multipart/form-data" method="post" action="<?=$longe_url?>faq?site=<?=$site?>">
                    <input type="hidden" name="bodyonly" id="bodyonly" value="<?=$bodyonly?>">
                    <input type="text" name="search_string" class="search_string" id="search_string">
                    <input type="submit" class="btn btn-small btn-inverse search_btn" name="action" value="查詢">
                </form>
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
                            <?=$row->content?>
                        </div>
                    </div>
                    <div class="ft">
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function($){
		$(window).load(function(){
			$(".scrollbar").mCustomScrollbar();
		});
	})(jQuery);
</script>
